<?php

namespace Core\Html;

use Core\Entity\Entity;

abstract class BaseField
{

    public Entity $entity;
    public string $attribute;
    public string $type;
    public ?string $event = null;
    public ?string $fnName = null;

    public function __construct(Entity $entity, string $attribute)
    {
        $this->entity = $entity;
        $this->attribute = $attribute;
    }

    protected function getEvent()
    {
        return is_null($this->event) ? "" : 'on'.$this->event.'="'.$this->fnName.'()"';
    }

    protected function getErrorTagId()
    {
        return "error-" . $this->attribute;
    }
    
    public function __toString()
    {
        return sprintf(
            '<div class="form-group mb-3">
                <label for="%s">%s</label>
                %s
                <div class="invalid-feedback" id="%s">%s</div>
            </div>',
            $this->attribute,
            $this->entity->getLabel($this->attribute),
            $this->renderInput(),
            $this->getErrorTagId(),
            $this->entity->getFirstError($this->attribute)
        );
    }

    abstract public function renderInput();
}