<?php

namespace Core\Html;

use Core\Entity\Entity;

class Field extends BaseField
{
    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_FILE = 'file';

    public function __construct(Entity $entity, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($entity, $attribute);
    }

    public function renderInput()
    {
        return sprintf(
            '<input type="%s" class="form-control %s" name="%s" id="%s" value=%s %s>',
            $this->type,
            $this->entity->hasError($this->attribute) ? 'is-invalid' : '',
            $this->attribute,
            $this->attribute,
            $this->entity->{$this->attribute},
            $this->getEvent(),
        );
    }

    public function on(string $event, string $fnName)
    {
        $this->event = $event;
        $this->fnName = $fnName;
        return $this;
    }

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }

    public function fileField()
    {
        $this->type = self::TYPE_FILE;
        return $this;
    }
}
