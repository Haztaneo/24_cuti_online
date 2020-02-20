<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'konfig-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<div class="row">
		<?php echo $form->labelEx($model,'jumlah_cuti_setahun'); ?>
		<?php echo $form->textField($model,'jumlah_cuti_setahun',array('class'=>'span1','maxlength'=>3))." <b>Hari</b>"; ?>
		<?php echo $form->error($model,'jumlah_cuti_setahun'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'max_ambil_cuti'); ?>
		<?php echo $form->textField($model,'max_ambil_cuti',array('class'=>'span1','maxlength'=>3))." <b>Hari</b>"; ?>
		<?php echo $form->error($model,'max_ambil_cuti'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'tgl_min_doj'); ?>
		<?php echo $form->textField($model,'tgl_min_doj',array('class'=>'span1','maxlength'=>2)); ?>
		<?php echo $form->error($model,'tgl_min_doj'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'min_tgl_pengajuan'); ?>
		<?php echo $form->textField($model,'min_tgl_pengajuan',array('class'=>'span1','maxlength'=>2)); ?>
		<?php echo $form->error($model,'min_tgl_pengajuan'); ?>
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