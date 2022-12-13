<?php

use Core\Html\Form;

?>

<h1>Login</h1>

<div class="alert alert-success mt-2 mb-3 d-none" id="message"></div>

<?php $form = Form::begin('', 'post') ?>
    <?php echo $form->field($entity, 'login') ?>
    <?php echo $form->field($entity, 'password')->passwordField() ?>
    <button class="btn btn-success mt-2" onclick="userLogin()" id="btn-login">Se conecter</button>
<?php Form::end() ?>


<script src="/js/user-login.js"></script>