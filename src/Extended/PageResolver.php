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

use SymphonyPDO;

class PageResolver
{
    private $request;

    public function __construct(string $request)
    {
        $this->request = $request;
    }

    public static function getCurrentPath(): string
    {
        [,$path] = explode(dirname(server_safe('SCRIPT_NAME')), server_safe('REQUEST_URI'), 2);
        [$split,] = explode('?', $path, 2);

        return '/'.trim($split, '/');
    }

    public function resolve(): ?\stdClass
    {
        if (true == empty($this->request) || '//' == $this->request) {
            $page = $this->getIndexPage();
        } else {
            $extraBits = [];
            $pathArray = preg_split('/\//', trim($this->request, '/'), -1, PREG_SPLIT_NO_EMPTY);
            $currentHandle = array_pop($pathArray);

            do {
                $currentPath = implode('/', $pathArray);

                if ($page = $this->getPageByHandleAndPath($currentHandle, $currentPath)) {
                    $pathArray[] = $currentHandle;
                    break;
                } else {
                    $extraBits[] = $currentHandle;
                }
            } while (($currentHandle = array_pop($pathArray)) !== null);

            if (true == empty($pathArray)) {
                $page = $this->getIndexPage();
            }

            if (false == ($this->checkPageParamsAreValid($page['params'], $extraBits))) {
                return null;
            }

            $page['type'] = \PageManager::fetchPageTypes($page['id']);
            $page['filelocation'] = \PageManager::resolvePageFileLocation($page['path'], $page['handle']);
        }

        return (object) $page;
    }

    private function checkPageParamsAreValid(?string $paramsDefinition, array $params): bool
    {
        return count(preg_split('/\//', (string) $paramsDefinition, -1, PREG_SPLIT_NO_EMPTY)) >= count($params);
    }

    private function getIndexPage(): ?array
    {
        return array_pop($this->fetchPagesByType('index'));
    }

    private function fetchPagesByType(string $type): ?array
    {
        return SymphonyPDO\Loader::instance()->query(sprintf(
            "SELECT p.* 
            FROM `tbl_pages_types` as `t` 
            LEFT JOIN `tbl_pages` as `p` ON t.page_id = p.id
            WHERE t.type = '%s' ",
            $type
        ))->fetchAll();
    }

    private function getPageByHandleAndPath(string $handle, ?string $path = null): ?array
    {

        // Normalise the path input to either a non-zero length string or null
        $path = 0 == strlen(trim((string)$path)) ? null : $path;

        $query = SymphonyPDO\Loader::instance()->prepare(sprintf(
            'SELECT * FROM `tbl_pages` WHERE `path` %s AND `handle` = :handle LIMIT 1',
            null != $path ? '= :path' : 'IS NULL'
        ));

        $query->bindParam(':handle', $handle, \PDO::PARAM_STR);

        if (null != $path) {
            $query->bindParam(':path', $path, \PDO::PARAM_STR);
        }

        try {
            $query->execute();
            $result = $query->fetch();
        } catch (\Exception $e) {
            $result = null;
        }

        return false === $result || null === $result ? null : $result;
    }
}
