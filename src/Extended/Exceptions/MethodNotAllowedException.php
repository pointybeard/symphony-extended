<?php

declare(strict_types=1);

namespace pointybeard\Symphony\Extended\Exceptions;

class MethodNotAllowedException extends SymphonyExtendedException
{
    protected $method;

    public function getHttpMethod(): ?string
    {
        return $this->method;
    }

    public function __construct(string $method, int $code = 0, \Exception $previous = null)
    {
        $this->method = $method;
        parent::__construct("Method '{$this->method}' is not allowed by route.", $code, $previous);
    }
}
