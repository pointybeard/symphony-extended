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

use FieldManager;
use SectionDatasource;

abstract class AbstractSectionDatasource extends SectionDatasource implements Interfaces\SectionDatasourceInterface
{
    public function __construct($env = null, $process_params = true)
    {
        $this->dsParamFILTERS = isset($this->dsParamFILTERS) && is_array($this->dsParamFILTERS)
            ? $this->convertDsParamFilterFieldElementNameToId($this->dsParamFILTERS)
            : []
        ;

        parent::__construct($env, $process_params);
    }

    /**
     * Prevents the Symphony admin Data Source editor from parsing any datasource
     * that extends this class.
     *
     * @return bool always returns false
     */
    final public function allowEditorToParse(): bool
    {
        return false;
    }

    /**
     * Looks at $this->dsParamFILTERS and converts field elements names to
     * their ID equivalent. Allows datasources that extend this class to be
     * more flexible.
     *
     * @return array all field element names converted to their ID value
     */
    protected function convertDsParamFilterFieldElementNameToId(array $filters): array
    {
        $result = [];

        // This for loop will examine all dsParamFILTERS and convert element
        // names into field ID values.
        foreach ($filters as $identifier => $value) {
            // Leave numeric values alone
            if (true == is_numeric($identifier)) {
                continue;
            }

            $fieldId = FieldManager::fetchFieldIDFromElementName($f, static::getSource());
            $result[$fieldId] = $value;
        }

        return $result;
    }
}
