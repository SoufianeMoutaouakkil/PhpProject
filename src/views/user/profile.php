<?php

use Altendev\Entity\UserEntity;
use Core\Html\Form;

if (!isset($entity) || is_null($entity)) {
    $entity = new UserEntity();
}
?>

<h1>Profile</h1>

<div class="alert alert-success mt-2 mb-3 d-none" id="message"></div>

<?php $form = Form::begin('', 'post'); ?>
    <?php
        $props = ["firstname", "lastname", "login", "mail"];
        foreach ($props as $prop) {
            echo $form->field($entity, $prop)->on("keyup", "checkChanges");
        }
    ?>
    <button class="btn btn-success d-none" id="btn-save" onclick="save()">Enregistrer</button>
<?php Form::end() ?>

<script src="/js/user/profile.js"></script>
