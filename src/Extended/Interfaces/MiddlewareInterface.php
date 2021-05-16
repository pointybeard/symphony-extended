<?php

declare(strict_types=1);

namespace pointybeard\Symphony\Extended\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Closure;

interface MiddlewareInterface
{
    public function handle(Request $request, Response $response, Closure $next);
}
