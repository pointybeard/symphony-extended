<?php

declare(strict_types=1);

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
