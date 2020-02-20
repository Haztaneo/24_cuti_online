<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cuti-bersama-form',
	'enableAjaxValidation'=>false,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
    <div class="row">
		<?php echo $form->labelEx($model,'tgl'); ?>
		<?php $thisYear = date("Y", strtotime(date("Y-m-d"))); ?> 
		<?php $yearRange = $thisYear .':' .$thisYear; ?>
		<?php $minDate = $thisYear ."-01-01"; ?>
		<?php $maxDate = $thisYear ."-12-31"; ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', 
				   array('model' => $model,
				        'name' => 'tgl',
						'attribute' => 'tgl',
						'htmlOptions' => array('class'=>'span2 form-control',
												'size' => '10',
												'maxlength' => '10',
												'placeholder'=>' yyyy-mm-dd',
												'readonly'=>true,),
						'options' => array ('dateFormat' => 'yy-mm-dd','autoclose' => true,
											'changeMonth'=>true,
											'changeYear'=>true,
											'yearRange' => $yearRange, 
											'minDate' => $minDate, 
											'maxDate' => $maxDate,
										   ),
				));	?>
		<?php echo $form->error($model,'tgl'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'keterangan'); ?>
		<?php echo $form->textArea($model,'keterangan',array('rows'=>3, 'class'=>'span4')); ?>
		<?php echo $form->error($model,'keterangan'); ?>
	</div>
	<div class="row buttons">
		<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'submit',
				'caption'=>'Simpan',
				'htmlOptions'=>array('class'=>'btn btn-primary'),
				));	?>
	</div>
<?php $this->endWidget(); ?>
</div>