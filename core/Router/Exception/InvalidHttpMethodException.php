<?php

declare(strict_types=1);

namespace Core\Router\Exception;

use InvalidArgumentException;

final class InvalidHttpMethodException extends InvalidArgumentException implements ExceptionInterface
{
    # to simplify implimentation
}
