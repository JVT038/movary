<?php declare(strict_types=1);

namespace Movary\Application\User\Exception;

class InvalidPassword extends InvalidCredentials
{
    public static function create() : self
    {
        return new self('Password wrong.');
    }
}
