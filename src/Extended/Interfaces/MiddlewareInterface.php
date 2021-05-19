<?php

declare(strict_types=1);

/*
 * This file is part of the "Extended Base Class Library for Symphony CMS" repository.
 *
 * Copyright 2020-2021 Alannah Kearney <hi@alannahkearney.com>
 *
 * For the full copyright and license information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace pointybeard\Symphony\Extended\Interfaces;

use Closure;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, Response $response, Closure $next);
}
