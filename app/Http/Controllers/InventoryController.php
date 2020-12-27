<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;

/**
 * init routing for inventory service actions
 */
class InventoryController extends Controller
{
    /**
     * Inventory service instance
     *
     * @var InventoryService $inventoryService
     */
    private $inventoryService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InventoryService $is)
    {
        $this->inventoryService = $is;
    }

    /**
     * handle fetch for inventory via inventory service
     *
     * @return void
     */
    public function get()
    {
        return $this->inventoryService->get();
    }

    /**
     * handle buying inventory via inventory service
     *
     * @param Item $id
     * @return void
     */
    public function put($id)
    {
        return $this->inventoryService->put($id);
    }
}
