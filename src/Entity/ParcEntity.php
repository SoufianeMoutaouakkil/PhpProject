<?php

namespace Altendev\Entity;

use Core\Entity\Entity;

class ParcEntity extends Entity
{
    public $id;
    public $pm;
    public $pon;
    public $oh;

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
            'pm' => "PM",
            'pon' => "PON",
            'oh' => "Partenaire d'adduction",
        ];
    }
}
