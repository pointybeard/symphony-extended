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

use pointybeard\Symphony\Extended\Route;
use Symfony\Component\HttpFoundation;

interface RouterInterface
{
    public function find(HttpFoundation\Request $request): Route;

    public function add(Route $route): self;
}
