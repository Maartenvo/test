<?php

namespace App\Http\Middleware;

use App\Interfaces\RequestHandler;
use Closure;
use Illuminate\Http\JsonResponse;

class VerifyApiAdmin
{
    private $requestHandler;
    private $apiUrl;

    /**
     * VerifyAdmin constructor.
     * @param RequestHandler $requestHandler
     */
    public function __construct(RequestHandler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
        $this->apiUrl = config('app.api.base_url') . '/v' . config('app.api.version');
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
        // Store previous url and intended redirect route information in order to redirect appropriately after log in
        \Session::put('previousUrl', \URL::previous());

        \Session::put('redirectRouteName', $request->route()->getName());
        \Session::put('redirectRouteMethod', $request->getMethod());
        \Session::put('redirectRouteParameters', $request->route()->parameters());

        $apiToken = \Auth::user()->getApiToken();

        if (!isset($apiToken) || trim($apiToken) === '') {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Please log in with an administrative account in order to to perform this action',
            ];

            return redirect()->route('api.login.show')->with('alerts', $alerts);
        }

        try {
            $this->requestHandler->makeGet(
                $this->apiUrl . '/auth/user?token=' . $apiToken,
                [
                    'Content-type' => 'application/json'
                ]
            );

            $response = $this->requestHandler->getJsonContents();

            if (!isset($response['result']) ||
                !isset($response['result']['roles'][0]['name']) ||
                $response['result']['roles'][0]['name'] != 'admin') {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => 'Please log in with an administrative account in order to to perform this action',
                ];

                return redirect()->route('api.login.show')->with('alerts', $alerts);
            }
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Please log in with an administrative account in order to to perform this action',
                'information' => $exception->getMessage()
            ];

            return redirect()->route('api.login.show')->with('alerts', $alerts);
        }

        return $next($request);
    }
}
