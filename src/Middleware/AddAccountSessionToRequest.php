<?php

declare(strict_types=1);

namespace ArtisanBuild\Turbulence\Middleware;

use ArtisanBuild\Turbulence\Support\AccountSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddAccountSessionToRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('turbulence.account_session_urls.enabled')) {
            $request->attributes->set('account', data_get($request->user(), 'account'));

            return $next($request);
        }

        $identifier = $request->route('account_identifier', config('turbulence.account_session_urls.index_key'));
        $request->attributes->set('account', $identifier === config('turbulence.account_session_urls.index_key')
            ? AccountSession::loadFromIndex($request->integer('account_id'))
            : AccountSession::load($request->integer('account_id')));

        return $next($request);
    }
}
