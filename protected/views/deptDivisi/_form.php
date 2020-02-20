<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'dept-divisi-form',
	'enableAjaxValidation'=>false,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
    <div class="row">
		<?php echo $form->labelEx($model,'kode'); ?>
		<?php echo $form->textField($model,'kode',array('size'=>5,'maxlength'=>5)); ?>
		<?php echo $form->error($model,'kode'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nama'); ?>
		<?php echo $form->textField($model,'nama',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'nama'); ?>
	</div>
	<!--<div class="row">
		<?php //echo $form->labelEx($model,'max_cuti_tahunan'); ?>
		<?php //echo $form->textField($model,'max_cuti_tahunan'); ?>
		<?php //echo $form->error($model,'max_cuti_tahunan'); ?>
	</div>-->
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', array('1'=>'AKTIF','0'=>'TIDAK AKTIF'),array('class'=>'span3')); ?> 
		<?php echo $form->error($model,'status'); ?>
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