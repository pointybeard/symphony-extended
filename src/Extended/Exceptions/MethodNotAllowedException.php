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

class MethodNotAllowedException extends SymphonyExtendedException
{
    protected $method;

    public function getHttpMethod(): ?string
    {
        return $this->method;
    }

    public function __construct(string $method, int $code = 0, \Exception $previous = null)
    {
        $this->method = $method;
        parent::__construct("Method '{$this->method}' is not allowed by route.", $code, $previous);
    }
}
