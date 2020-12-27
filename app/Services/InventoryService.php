<?php

namespace App\Services;

use App\Exceptions\InsufficientFundsException;
use App\Exceptions\InventoryException;
use App\Models\Coins;
use App\Models\Item;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;

/**
 * handle inventory actions
 */
class InventoryService
{
    /**
     * error logger
     *
     * @var Logger $logger
     */
    private $logger;

    /**
     * A coin service instance
     *
     * @var CoinService $coinService
     */
    private $coinService;

    /**
     * an item model
     *
     * @var Item $item
     */
    private $item;

    public function __construct(Logger $logger, CoinService $cs)
    {
        $this->logger = $logger;
        $this->coinService = $cs;
    }

    /**
     * fetch inventory levels
     *
     * @return void
     */
    public function get() : Response
    {
        try {
            $inventoryCollection =
                Item::all()
                ->pluck('available')
                ->toArray();

            return response(
                $inventoryCollection,
                200
            );
        } catch (Exception $e) {
            $this->logger->error([
                'inventory_fetch_error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response(
                'unhandled inventory fetch error',
                400
            );
        }
    }

    /**
     * handle an item, purchase and business rules checks
     *
     * @param string $item
     * @return void
     */
    public function put($item) : Response
    {
        try {
            $this->item = Item::find($item);
            $this->checkItemPurchaseBounds($item);
            $this->makeItemPurchase();
            return response(
                1,
                200,
                [
                    'X-Coins' => $this->coinService->handleCoinReset(),
                    'X-Inventory-Remaining' => $this->item->available
                ]
            );

        } catch (InventoryException $e) {
            $this->logger->error([
                'inventory_purchase_error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response(
                'insufficient inventory stock purchase error',
                404,
                [
                    'X-Coins' => $this->coinService->getCurrenCoinCount(),
                ]
            );
        } catch (InsufficientFundsException $e) {
            $this->logger->error([
                'insufficient_funds_error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response(
                'insufficient funds inventory purchase error',
                403,
                [
                    'X-Coins' => $this->coinService->getCurrenCoinCount(),
                ]
            );
        }  catch (Exception $e) {
            $this->logger->error([
                'inventory_purchase_error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response(
                'unhandled inventory purchase error',
                400
            );
        }
    }

    /**
     * can an item be purchase based on business rules
     *
     * @return void
     */
    private function checkItemPurchaseBounds() : void
    {
        if ($this->item->available < 1) {
            throw new InventoryException('quantity not available');
        }

        if ($this->coinService->getCurrenCoinCount() < $this->item->cost) {
            throw new InsufficientFundsException('insufficient funds available');
        }
    }

    /**
     * purchase an item and adjust stock and refund coins
     *
     * @return void
     */
    private function makeItemPurchase() : void
    {
        $this->item->available = $this->item->available - 1;
        $this->coinService->setCoinCount(
            $this->coinService->getCurrenCoinCount() - $this->item->cost
        );
        $this->item->save();
    }
}
