<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'agama-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<div class="row">
		<?php echo $form->labelEx($model,'nama'); ?>
		<?php echo $form->textField($model,'nama',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'nama'); ?>
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