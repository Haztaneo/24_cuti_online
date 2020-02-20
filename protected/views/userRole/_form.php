<div class="form" style="padding:10px">
<?php 
	$new = false;
	if(!isset($form)){
		$new = true;
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'role-form',			
			'enableAjaxValidation'=>false,
		));
	}
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50, 'class'=>'span6' )); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span6' )); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	<hr/>
	<div>
		<h3>Set Permission</h3>
	</div>
</div><!-- form -->