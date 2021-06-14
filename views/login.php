<?php
/** @var $model \app\models\Users */
/** @var $this \app\core\View */
$this->title = 'Login';
?>
<h1>Login</h1>
<?php $form = \app\core\forms\Forms::begin('', 'post' )?>


<?php echo $form->field($model, 'email')?>
<?php echo $form->field($model, 'password')->typePassword() ?>



<button type="submit" class="btn btn-primary">Submit</button>
<?php echo \app\core\forms\Forms::end()?>
