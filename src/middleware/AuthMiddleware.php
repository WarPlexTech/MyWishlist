<?php


namespace MyWishlist\middleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AuthMiddleware
 * @package MyWishlist\middleware
 */
class AuthMiddleware extends Middleware
{
    public function __invoke(ServerRequestInterface $request,ResponseInterface $response, $next)
    {
        if(!$this->container->auth->isSigned()){
            return $response->withRedirect($this->container->router->pathFor('login'));
        }

        $response = $next($request, $response);
        return $response;
    }

}