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

namespace pointybeard\Symphony\Extended;

use Exception;
use Psr;
use ReflectionClass;
use ReflectionMethod;

class ServiceContainer implements Psr\Container\ContainerInterface
{
    protected static $instance = null;

    protected $instances = [];

    protected $services = [];

    protected $methods = [];

    public static function getInstance()
    {
        if (true == (null === static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function get(string $id)
    {
        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return true == isset($this->services[$id]) || true == isset($this->instances[$id]) || true == isset($this->methods[$id]);
    }

    private function registerMethod($id, $concrete)
    {
        $concrete = $concrete ?? $id;

        $def = new ReflectionMethod($concrete);
        $class = $def->getDeclaringClass()->getName();
        $method = $def->getName();

        if (false == $this->has($class)) {
            $this->register($class);
        }

        $this->methods[$id] = compact('concrete', 'class', 'method');
    }

    public function register($id, $concrete = null, bool $shared = false): void
    {
        $concrete = $concrete ?? $id;

        // Trying to register an already instaniated class
        if (false == is_string($concrete)) {
            $this->deregister($id);
            $this->instances[$id] = $concrete;

            return;
        }

        if (true == is_callable($concrete)) {
            $this->registerMethod($id, $concrete);

            return;
        }

        $this->services[$id] = compact('concrete', 'shared');
    }

    public function deregister($id)
    {
        unset($this->services[$id]);
        unset($this->methods[$id]);
        unset($this->instances[$id]);
    }

    private function resolveInstance($id)
    {
        return $this->instances[$id];
    }

    private function resolveMethod($id)
    {
        $instance = $this->resolveService($this->methods[$id]['class']);

        $def = new ReflectionMethod($this->methods[$id]['concrete']);

        if (0 == $def->getNumberOfParameters()) {
            return $def->invoke($instance);
        }

        return $def->invoke($instance, ...$this->buildMethodParameters($def));
    }

    private function resolveService($id)
    {
        $def = (new ReflectionClass($this->services[$id]['concrete']));
        $constructor = $def->getConstructor();

        if (true == (null === $constructor) || 0 == $constructor->getNumberOfParameters()) {
            $instance = $def->newInstance();
        } else {
            $instance = $def->newInstance(...$this->buildMethodParameters($constructor));
        }

        if (true == $this->services[$id]['shared']) {
            $this->register($id, $instance, true);
        }

        return $instance;
    }

    private function buildMethodParameters($method)
    {
        if (true == ($method instanceof ReflectionMethod)) {
            foreach ($method->getParameters() as $p) {
                try {
                    $params[] = $this->resolve($p->getName());
                } catch (Exceptions\ServiceContainerEntryNotFoundException $ex) {
                    // Nothing was located. See if this is an optional parameter, otherwise, throw an exception
                    if (false == $p->isOptional()) {
                        // A value is required. We'll have to throw an exception.
                        throw new Exceptions\ServiceContainerException("Unable to instanciate {$id}. Required parameter {$p->getName()} could not be resolved.");
                    }
                    // Since this is optional, but there is no value set, we need to stop.
                    break;
                }
            }
        }

        return $params;
    }

    protected function resolve($id)
    {
        if (false == $this->has($id)) {
            throw new Exceptions\ServiceContainerEntryNotFoundException("No entry {$id} could be located.");
        }

        // Check to see if this is a singleton
        if (true == isset($this->instances[$id])) {
            return $this->resolveInstance($id);
        }

        // Check to see if this is a method
        if (true == isset($this->methods[$id])) {
            return $this->resolveMethod($id);
        }

        return $this->resolveService($id);
    }
}
