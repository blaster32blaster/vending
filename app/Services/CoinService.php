<?php

namespace App\Services;

use App\Exceptions\CoinException;
use App\Models\Coins;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Log\Logger;

/**
 * handle coin actions
 */
class CoinService
{
    /**
     * error logger
     *
     * @var Logger $logger
     */
    private $logger;

    /**
     * The current coin instance
     *
     * @var Coins $coin
     */
    private $coin;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
        $this->setCoin();
    }

    /**
     * handle adding coins
     *
     * @param Request $request  http request to with body {coin : #}
     * @return Response
     */
    public function put(Request $request) : Response
    {
        try {
            $numCoins = $request->input('coin');
            $this->setCoinCount($numCoins + $this->getCurrenCoinCount());

            return response(
                $this->coin->count,
                204,
                [
                    'X-Coins' => $numCoins
                ]
            );
        } catch (Exception $e) {
            $this->logger->error([
                'coin_add_error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response(
                'unhandled coin add error',
                400
            );
        } catch (CoinException $e) {
            $this->logger->error([
                'coin_add_error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response(
                'unhandled coin add error',
                400
            );
        }
    }

    /**
     * handle deletion of coins
     *
     * @return Response
     */
    public function delete() : Response
    {
        try {
            $responseCount = $this->handleCoinReset();
            return response(
                $responseCount,
                204,
                [
                    'X-Coins' => $responseCount
                ]
            );
        } catch (Exception $e) {
            $this->logger->error([
                'coin_delete_error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response(
                'unhandled coin delete error',
                400
            );
        } catch (CoinException $e) {
            $this->logger->error([
                'coin_delete_error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);

            return response(
                'unhandled coin delete error',
                400
            );
        }
    }

    /**
     * set the coin model
     *
     * @return void
     */
    public function setCoin() : void
    {
        try {
            $this->coin = Coins::firstOrCreate();
        } catch (Exception $e) {
            $this->logger->error([
                'coin_set_error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            throw new CoinException('error setting coin');
        }
    }

    /**
     * get the current coin count
     *
     * @return integer
     */
    public function getCurrenCoinCount() : int
    {
        try {
            return $this->coin->count;
        } catch (Exception $e) {
            throw new CoinException('error fetching coin count');
        }
    }

    /**
     * set the current coints count
     *
     * @param integer $number
     * @return void
     */
    public function setCoinCount(int $number) : void
    {
        try {
            $this->coin->count = $number;
            $this->coin->save();
        } catch (Exception $e) {
            throw new CoinException('error setting coin count');
        }
    }

    /**
     * set the coin to 0, return how many coins to refund
     *
     * @return integer
     */
    public function handleCoinReset() : int
    {
        $responseCount = $this->getCurrenCoinCount();
        $this->setCoinCount(0);
        return $responseCount;
    }
}
