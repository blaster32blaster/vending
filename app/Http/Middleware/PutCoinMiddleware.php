<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Log\Logger;

class PutCoinMiddleware
{
    /**
     * error logger
     *
     * @var Logger $logger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (
            !$request->has('coin') ||
            !is_numeric($request->input('coin'))
        ) {
            $this->logger->error([
                'put_coins_error' => 'coins either not numeric or missing entirely'
            ]);
            return response('coins either not numeric or missing', 400);
        }
        return $next($request);
    }
}
