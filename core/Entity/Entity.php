<?php

namespace Core\Entity;

use Core\Database\Database;

abstract class Entity
{
    const RULE_REQUIRED = 'required';
    const RULE_EMAIL = 'email';
    const RULE_MIN = 'min';
    const RULE_MAX = 'max';
    const RULE_MATCH = 'match';
    const RULE_UNIQUE = 'unique';

    public array $errors = [];

    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function properties();
    abstract public function labels();
    abstract public function rules();

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($rule)) {
                    $ruleName = $rule[0];
                }
                $ruleMethod = "rule" . ucfirst($ruleName);
                if (method_exists($this, $ruleMethod)) {
                    $this->{$ruleMethod}($rule, $attribute, $value);
                }
            }
        }

        return !empty($this->errors);
    }

    private function ruleRequired($rule, $attribute, $value)
    {
        if (!$value) {
            $this->addErrorByRule($attribute, self::RULE_REQUIRED);
        }
    }

    private function ruleEmail($rule, $attribute, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addErrorByRule($attribute, self::RULE_EMAIL);
        }
    }

    private function ruleMin($rule, $attribute, $value)
    {
        if (strlen($value) < $rule['min']) {
            $this->addErrorByRule($attribute, self::RULE_MIN, ['min' => $rule['min']]);
        }
    }

    private function ruleMax($rule, $attribute, $value)
    {
        if (strlen($value) > $rule['max']) {
            $this->addErrorByRule($attribute, self::RULE_MAX);
        }
    }

    private function ruleMatch($rule, $attribute, $value)
    {
        if ($value !== $this->{$rule['match']}) {
            $this->addErrorByRule($attribute, self::RULE_MATCH, ['match' => $rule['match']]);
        }
    }
    
    private function ruleUnique($rule, $attribute, $value)
    {
        $className = $rule['class'];
        $uniqueAttr = $rule['attribute'] ?? $attribute;
        $tableName = $className::tableName();
        $db = Database::dbFactory();
        $statement = $db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :$uniqueAttr");
        $statement->bindValue(":$uniqueAttr", $value);
        $statement->execute();
        $record = $statement->fetchObject();
        if ($record) {
            $this->addErrorByRule($attribute, self::RULE_UNIQUE);
        }
    }

    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'Ce champ est obligatoire.',
            self::RULE_EMAIL => 'Ce champ doit être une adresse mail valide.',
            self::RULE_MIN => 'Le nombre minimum de charactère de ce champ est : {min}.',
            self::RULE_MAX => 'Le nombre maximum de charactère de ce champ est : {max}.',
            self::RULE_MATCH => 'Ce champ doit correspondre au champ {match}.',
            self::RULE_UNIQUE => 'Ce champ doit être unique. Un enregistrement avec sa valeur existe déjà.',
        ];
    }

    public function errorMessage($rule)
    {
        return $this->errorMessages()[$rule];
    }

    protected function addErrorByRule(string $attribute, string $rule, $params = [])
    {
        $params['field'] ??= $attribute;
        $errorMessage = $this->errorMessage($rule);
        foreach ($params as $key => $value) {
            $errorMessage = str_replace("{{$key}}", $value, $errorMessage);
        }
        $this->errors[$attribute][] = $errorMessage;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        $errors = $this->errors[$attribute] ?? [];
        return $errors[0] ?? '';
    }

    public function toArray()
    {
        $res = [];
        foreach ($this->properties() as $prop) {
            $res[$prop] = [];
            $res[$prop]["label"] = $this->labels()[$prop];
            $res[$prop]["errors"] = $this->errors[$prop] ?? null;
            $res[$prop]["value"] = $this->{$prop};
        }

        return $res;
    }
}
