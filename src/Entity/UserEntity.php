<?php

namespace Altendev\Entity;

use Core\Entity\Entity;

class UserEntity extends Entity
{
    public $id;
    public $login;
    public $mail;
    public $password;
    public $firstname;
    public $lastname;

    public function rules()
    {
        return [
            'login' => [self::RULE_REQUIRED],
            'mail' => [self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED],
            'confirmPassword' => [[self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function labels()
    {
        return [
            'login' => "Login",
            'mail' => "E-mail",
            'password' => "Mot de passe",
            'confirmPassword' => "Confirmation de mot de passe"
        ];
    }
}
