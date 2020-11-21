<?php

declare(strict_types=1);

namespace pointybeard\Symphony\Extended\Exceptions;

class MatchingRouteNotFound extends SymphonyExtendedException
{
    public function __construct(int $code = 0, \Exception $previous = null)
    {
        parent::__construct("No route found for request", $code, $previous);
    }
}
