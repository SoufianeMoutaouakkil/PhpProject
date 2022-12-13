<?php

namespace Core\Html;

use Core\Entity\Entity;

abstract class BaseField
{

    public Entity $entity;
    public string $attribute;
    public string $type;

    public function __construct(Entity $entity, string $attribute)
    {
        $this->entity = $entity;
        $this->attribute = $attribute;
    }

    public function __toString()
    {
        return sprintf(
            '<div class="form-group">
                <label>%s</label>
                %s
                <div class="invalid-feedback" id="%s">%s</div>
            </div>',
            $this->entity->getLabel($this->attribute),
            $this->renderInput(),
            "error-" . $this->attribute,
            $this->entity->getFirstError($this->attribute)
        );
    }

    abstract public function renderInput();
}