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

namespace pointybeard\Symphony\Extended\Exceptions;

class MiddlewareInvalidException extends SymphonyExtendedException
{
    public function __construct($middleware, int $code = 0, \Exception $previous = null)
    {
        parent::__construct('Middleware for route is invalid. Must be a Closure, a class that implements MiddlewareInterface, or an array containing any combination of these. Middleware: '.var_export($middleware, true), $code, $previous);
    }
}
