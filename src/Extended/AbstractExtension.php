<?php

declare(strict_types=1);

/*
 * This file is part of the "Extended Base Class Library for Symphony CMS" repository.
 *
 * Copyright 2020 Alannah Kearney <hi@alannahkearney.com>
 *
 * For the full copyright and license information, please view the LICENCE
 * file that was distributed with this source code.
 */

namespace pointybeard\Symphony\Extended;

use Extension;
use pointybeard\Helpers\Functions\Json;
use pointybeard\Symphony\ExtensionAssetManagement\Iterators;
use pointybeard\Symphony\SectionBuilder;

abstract class AbstractExtension extends Extension implements Interfaces\ExtensionInterface
{
    private $about = null;

    public function init(): void
    {
        return;
    }

    public function enable()
    {
        $this->enableAllAssets();

        return true;
    }

    public function disable()
    {
        $this->disableAllAssets();

        return true;
    }

    public function uninstall()
    {
        // @todo: Check that other extensions don't depend on this one

        // @todo: Remove any sections created during install

        $this->uninstallAllAssets();

        return true;
    }

    public function install()
    {
        if (true == isset($this->about()->require) && false == empty($this->about()->require)) {
            foreach ($this->about()->require as $handle) {
                if (false == $this->checkDependency($handle)) {
                    throw new \Exception("Extenson '{$handle}' must be installed before '{$this->about()->name}' can be installed.");
                }
            }
        }

        if (true == file_exists(self::dir().'/src/Install/sections.json')) {
            SectionBuilder\Import::fromJsonFile(
                self::dir().'/src/Install/sections.json',
                SectionBuilder\Import::FLAG_SKIP_ORDERING
            );
        }

        $this->installAllAssets();

        return true;
    }

    protected function enableAllAssets()
    {
        foreach ($this->getAssetIterators() as $item) {
            $item->enable();
        }
    }

    protected function disableAllAssets()
    {
        foreach ($this->getAssetIterators() as $item) {
            $item->disable();
        }
    }

    protected function installAllAssets()
    {
        foreach ($this->getAssetIterators() as $item) {
            $item->install();
        }
    }

    protected function uninstallAllAssets()
    {
        foreach ($this->getAssetIterators() as $item) {
            $item->uninstall();
        }
    }

    protected function getAssetIterators(): \AppendIterator
    {
        $it = new \AppendIterator();
        $it->append(new Iterators\DatasourceAssetsIterator());
        $it->append(new Iterators\EventsAssetsIterator());
        $it->append(new Iterators\FieldsAssetsIterator());
        $it->append(new Iterators\ContentAssetsIterator());
        $it->append(new Iterators\CommandsAssetsIterator());

        return $it;
    }

    final public static function status(): string
    {
        \ExtensionManager::about(static::handle())['status'];
    }

    final public static function handle(): string
    {
        return basename(self::dir());
    }

    final public static function about(): \stdClass
    {
        try {
            return Json\json_decode_file(self::dir().'/extension.json');
        } catch (\JsonException $ex) {
            throw new \Exception('Unable to call the about method on extension '.static::handle().'. Returned: '.$ex->getMessage());
        }
    }

    private static function dir(): string
    {
        return dirname((new \ReflectionClass(static::class))->getFileName());
    }

    private function checkDependency(string $handle): bool
    {
        $about = \ExtensionManager::about($handle);
        if (true == empty($about) || false == in_array(\Extension::EXTENSION_ENABLED, $about['status'])) {
            return false;
        }

        return true;
    }
}
