<?php

declare(strict_types=1);

namespace Core\Router\Exception;

use InvalidArgumentException;

final class NotFoundException extends InvalidArgumentException implements ExceptionInterface
{
    protected $message = 'Page not found';
    protected $code = 404;
}
