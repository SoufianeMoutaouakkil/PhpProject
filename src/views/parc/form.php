<?php use Core\Html\Form; ?>

<h2>Test AJAX</h2>
<?php $form = Form::begin('', 'GET') ?>
    <?php echo $form->field($entity, 'pm') ?>
    <?php echo $form->field($entity, 'pon') ?>
    <?php echo $form->field($entity, 'oh') ?>
    <button class="btn btn-success" onclick="getPmInfo()">Get info</button>
<?php Form::end() ?>

<script src="js/ajax.js"></script>