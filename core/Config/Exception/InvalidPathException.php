<?php

declare(strict_types=1);

namespace Core\Config\Exception;

use InvalidArgumentException;

final class InvalidPathException extends InvalidArgumentException implements ExceptionInterface
{
    # to simplify implimentation
}
