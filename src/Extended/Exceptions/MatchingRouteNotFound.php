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

class MatchingRouteNotFound extends SymphonyExtendedException
{
    public function __construct(int $code = 0, \Exception $previous = null)
    {
        parent::__construct('No route found for request', $code, $previous);
    }
}
