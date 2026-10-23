<?php

declare(strict_types=1);

use ArtisanBuild\Turbulence\Middleware\AddAccountSessionToRequest;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

it('adds the authenticated users account when account session urls are disabled', function (): void {
    config()->set('turbulence.account_session_urls.enabled', false);

    $account = new stdClass;
    $user = new User;
    $user->setRelation('account', $account);

    $request = Request::create('/');
    $request->setUserResolver(fn (): User => $user);

    (new AddAccountSessionToRequest)->handle($request, function (Request $request) use ($account): Response {
        expect($request->get('account'))->toBe($account);

        return new Response;
    });
});
