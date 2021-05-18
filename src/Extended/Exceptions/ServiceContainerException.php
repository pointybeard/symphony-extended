<?php

declare(strict_types=1);

namespace pointybeard\Symphony\Extended\Exceptions;

use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerInterface;

use Exception;

class ServiceContainerException extends SymphonyExtendedException implements ContainerExceptionInterface {}