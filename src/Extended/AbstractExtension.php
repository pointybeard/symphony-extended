<?php

declare(strict_types=1);

namespace pointybeard\Symphony\Extended;

use pointybeard\Helpers\Functions\Json;

abstract class AbstractExtension extends \Extension implements Interfaces\ExtensionInterface
{
    private $about = null;

    public function init(): void
    {
        return;
    }

    public function install()
    {
        foreach ($this->about()->require as $handle) {
            if (false == $this->checkDependency($handle)) {
                throw new \Exception("Extenson '{$handle}' is not installed but is required to install extension '{$this->about()->name}'.");
            }
        }

        return true;
    }

    final public static function status(): string {
        \ExtensionManager::about(static::handle())["status"];
    }

    final public static function handle(): string
    {
        return basename(self::dir());
    }

    final public static function about(): \stdClass
    {
        try {
            return Json\json_decode_file(self::dir()."/extension.json");
        } catch (\JsonException $ex) {
            throw new \Exception("Unable to call the about method on extension ".static::handle().". Returned: ".$ex->getMessage());
        }
    }

    private static function dir(): string
    {
        return dirname((new \ReflectionClass(static::class))->getFileName());
    }

    private function checkDependency(string $handle): bool
    {
        $about = \ExtensionManager::about($handle);
        if (true == empty($about) || false == in_array(\Extension::EXTENSION_ENABLED, $about["status"])) {
            return false;
        }

        return true;
    }
}
