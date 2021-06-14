<?php
/** @var $this \app\core\View */
/** @var $model \app\models\ContactForm */

use app\core\forms\TextField;

$this->title = 'Contact';
?>
<h1>Contact Us</h1>
<?php $form = \app\core\forms\Forms::begin('', 'post')?>
<?php echo $form->field($model, 'subject') ?>
<?php echo $form->field($model, 'email') ?>
<?php echo new TextField($model, 'body') ?>
<button type="submit" class="btn btn-primary">Submit</button>
<?php \app\core\forms\Forms::end(); ?>

