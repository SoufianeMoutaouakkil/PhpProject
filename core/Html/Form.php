<?php

namespace Core\Html;

use Core\Entity\Entity;

class Form
{
    public static function begin($action, $method, $options = [])
    {
        $attributes = [];
        foreach ($options as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }
        echo sprintf(
            '<form action="%s" method="%s" %s>',
            $action,
            $method,
            implode(" ", $attributes)
        );
        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public function field(Entity $entity, $attribute)
    {
        return new Field($entity, $attribute);
    }

}
