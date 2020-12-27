<?php

namespace App\Http\Controllers;

use App\Services\CoinService;
use Illuminate\Http\Request;

/**
 * handle init routing for coin transactions
 */
class CoinController extends Controller
{
    /**
     * a coin service instance
     *
     * @var CoinService $coinService
     */
    private $coinService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CoinService $cs)
    {
        $this->coinService = $cs;
    }

    /**
     * handle coin addition via the coin service
     *
     * @param Request $request
     * @return void
     */
    public function put(Request $request)
    {
        return $this->coinService->put($request);
    }

    /**
     * handle coin deletion via the coin service
     *
     * @return void
     */
    public function delete()
    {
        return $this->coinService->delete();
    }
}
