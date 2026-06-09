<?php

namespace Tests\Unit;

use App\Http\Middleware\EnsureUserIsActive;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EnsureUserIsActiveTest extends TestCase
{
    public function test_allows_active_user(): void
    {
        $user = new User();
        $user->is_active = true;

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);

        $request = Request::create('/profile', 'GET');
        
        $middleware = new EnsureUserIsActive();
        
        $response = $middleware->handle($request, function ($req) {
            return response('next');
        });

        $this->assertEquals('next', $response->getContent());
    }

    public function test_blocks_inactive_user_on_restricted_route(): void
    {
        $user = new User();
        $user->is_active = false;

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);

        $request = Request::create('/profile', 'GET');
        $route = new Route('GET', '/profile', []);
        $route->name('profile.edit');
        $request->setRouteResolver(fn() => $route);



        $middleware = new EnsureUserIsActive();
        
        $response = $middleware->handle($request, function ($req) {
            return response('next');
        });

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('dashboard'), $response->headers->get('Location'));
    }

    public function test_allows_inactive_user_on_dashboard_and_logout(): void
    {
        $user = new User();
        $user->is_active = false;

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn($user);

        foreach (['dashboard', 'logout'] as $allowedRoute) {
            $request = Request::create('/' . $allowedRoute, 'GET');
            $route = new Route('GET', '/' . $allowedRoute, []);
            $route->name($allowedRoute);
            $request->setRouteResolver(fn() => $route);

            $middleware = new EnsureUserIsActive();

            $response = $middleware->handle($request, function ($req) {
                return response('next');
            });

            $this->assertEquals('next', $response->getContent());
        }
    }
}
