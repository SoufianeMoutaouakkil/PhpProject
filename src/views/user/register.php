<?php

use Altendev\Entity\UserEntity;
use Core\Html\Form;

if (!isset($entity) || is_null($entity)) {
    $entity = new UserEntity();
}
?>

<h1>Register</h1>

<?php $form = Form::begin('', 'post') ?>
    <div class="row">
        <div class="col">
            <?php echo $form->field($entity, 'login') ?>
        </div>
        <div class="col">
            <?php echo $form->field($entity, 'lastname') ?>
        </div>
    </div>
    <?php echo $form->field($entity, 'mail') ?>
    <?php echo $form->field($entity, 'password')->passwordField() ?>
    <?php echo $form->field($entity, 'confirmPassword')->passwordField() ?>
    <button class="btn btn-success">Submit</button>
<?php Form::end() ?>
