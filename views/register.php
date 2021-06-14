<?php
/** @var $model \app\models\Users */
/** @var $this \app\core\View */
$this->title = 'Register';
?>
<h1>Register</h1>
<?php $form = \app\core\forms\Forms::begin('', 'post' )?>
    <div class = "row">
        <div class="col"> <?php echo $form->field($model, 'firstname')?></div>

        <div class="col"> <?php echo $form->field($model, 'lastname')?></div>
    </div>

    <?php echo $form->field($model, 'email')?>
    <?php echo $form->field($model, 'password')->typePassword() ?>
    <?php echo $form->field($model, 'ConfirmPassword')->typePassword() ?>


    <button type="submit" class="btn btn-primary">Submit</button>
<?php echo \app\core\forms\Forms::end()?>
