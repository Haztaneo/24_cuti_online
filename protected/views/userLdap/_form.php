<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-ldap-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('disabled'=>true)); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nama'); ?>
		<?php echo $form->textField($model,'nama',array('disabled'=>true)); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'is_visible'); ?>
		<?php echo $form->radioButtonList($model,'is_visible', array('1'=>'YES','0'=>'NO'), 		array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
					   ,'labelOptions'=>array('style'=>'display:inline'))); ?>
		<?php echo $form->error($model,'is_visible'); ?>
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