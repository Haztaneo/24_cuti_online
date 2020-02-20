<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lokasi-form',
	'enableAjaxValidation'=>false,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<div class="row">
		<?php echo $form->labelEx($model,'nama'); ?>
		<?php echo $form->textField($model,'nama',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'nama'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', array('1'=>'Aktif','0'=>'Non Aktif'),array('class'=>'span3')); ?> 
		<?php echo $form->error($model,'status'); ?>
	</div>	
	<hr/>
	<h5><u>Akhir Pekan</u></h5>	
	<div class="row">
		<?php echo $form->checkBox($model,'senin',array('value' => '1', 'uncheckValue'=>'-1'))." <b>Senin</b>"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $form->checkBox($model,'selasa',array('value' => '2', 'uncheckValue'=>'-1'))." <b>Selasa</b>"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $form->checkBox($model,'rabu',array('value' => '3', 'uncheckValue'=>'-1'))." <b>Rabu</b>"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $form->checkBox($model,'kamis',array('value' => '4', 'uncheckValue'=>'-1'))." <b>Kamis</b>"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $form->checkBox($model,'jumat',array('value' => '5', 'uncheckValue'=>'-1'))." <b>Jumat</b>"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $form->checkBox($model,'sabtu',array('value' => '6', 'uncheckValue'=>'-1'))." <b>Sabtu</b>"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $form->checkBox($model,'minggu',array('value' => '0', 'uncheckValue'=>'-1'))." <b>Minggu</b>"; ?>
	</div><br/>
	<div class="row buttons">
		<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'submit',
				'caption'=>'Simpan',
				'htmlOptions'=>array('class'=>'btn btn-primary'),
				));	?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->