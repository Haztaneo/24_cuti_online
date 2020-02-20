<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tipe-izin-form',
	'enableAjaxValidation'=>false,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<div class="row">
		<?php echo $form->labelEx($model,'nama'); ?>
		<?php echo $form->textField($model,'nama',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'nama'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'is_potong_cuti'); ?>
		<?php echo $form->radioButtonList($model,'is_potong_cuti', array('1'=>'YA','0'=>'TIDAK'), 			array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
				   ,'labelOptions'=>array('style'=>'display:inline'))); ?>
		<?php echo $form->error($model,'is_potong_cuti'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'is_must_attach'); ?>
		<?php echo $form->radioButtonList($model,'is_must_attach', array('1'=>'YA','0'=>'TIDAK'), 			array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
				   ,'labelOptions'=>array('style'=>'display:inline'))); ?>
		<?php echo $form->error($model,'is_must_attach'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php /*echo $form->radioButtonList($model,'status', array('1'=>'AKTIF','0'=>'TIDAK AKTIF'), 			array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
				   ,'labelOptions'=>array('style'=>'display:inline')));*/ ?>
		<?php echo $form->dropDownList($model,'status', array('1'=>'AKTIF','0'=>'TIDAK AKTIF'),array('class'=>'span2')); ?>
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