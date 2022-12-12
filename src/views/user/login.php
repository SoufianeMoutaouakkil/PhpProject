<?php

use Core\Html\Form;

?>

<h1>Login</h1>

<?php $form = Form::begin('', 'post') ?>
    <?php echo $form->field($entity, 'login') ?>
    <?php echo $form->field($entity, 'password')->passwordField() ?>
    <button class="btn btn-success">Submit</button>
<?php Form::end() ?>
