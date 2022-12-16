<?php

namespace Altendev\Entity;

use Core\Entity\Entity;

class UserEntity extends Entity
{
    public $login;
    public $mail;
    public $password;
    public $confirmPassword;
    public $firstname;
    public $lastname;

    public function properties()
    {
        return [
            "login",
            "mail",
            "password",
            "confirmPassword",
            "firstname",
            "lastname",
        ];
    }
    public function rules()
    {
        return [
            'login' => [self::RULE_REQUIRED],
            'firstname' => [self::RULE_REQUIRED],
            'mail' => [self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class, "id" => $this->id
            ]],
            'password' => [self::RULE_REQUIRED],
            'confirmPassword' => [[self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function labels()
    {
        return [
            'login' => "Login",
            'password' => "Mot de passe",
            'confirmPassword' => "Confirmation de mot de passe",
            'mail' => "E-mail",
            'lastname' => "Nom",
            'firstname' => "Pénom",
        ];
    }
}
