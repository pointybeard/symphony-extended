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

interface ExtensionInterface
{
    public function init(): void;

    public static function about(): \stdClass;

    public static function status(): string;

    public static function handle(): string;

    public function enable();

    public function disable();

    public function install();

    public function uninstall();
}
