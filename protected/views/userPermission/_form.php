<div class="form" style="padding:10px">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-permission-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50, 'class'=>'span5')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<div class="row buttons">
		<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'submit',
				'caption'=>'Simpan',
				'htmlOptions'=>array('class'=>'btn btn-primary'),
				));	?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->