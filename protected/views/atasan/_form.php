<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'atasan-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<div class="row">
		<?php echo $form->labelEx($model,'dept_divisi_id'); ?>
		<?php echo $form->dropDownList($model,'dept_divisi_id', DeptDivisi::model()->getOptions(),array('class'=>'span3')); ?> 
		<?php echo $form->error($model,'dept_divisi_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nama'); ?>
		<?php /*$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
					'name'=>'term',
					'model'=>$model,
					'attribute'=>'nama',
					'value'=>$model->nama, 
					'source' => $this->createUrl('userLdap/loadNama'),						
					'options' => array(
						'minLength' => '1',
					),
					'htmlOptions' => array(
						'placeholder' => 'Nama Associate',
						'class'=>'span4'
					),
				));*/	?>
		<?php $this->widget('ext.combobox.EJuiComboBox', array(
				'model' => $model,
				'attribute' => 'nama',
				'value'=>$model->nama, 
				'data' => UserLdap::model()->getOptionsAtasan(),
				'options' => array(
					'allowText' => false,
				),
				'htmlOptions' => array('placeholder' => 'Nama Associate', 'class'=>'span3'),
			));
		?>		
		<?php echo $form->error($model,'nama'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'keterangan'); ?>
		<?php echo $form->textArea($model,'keterangan',array('class'=>'span4', 'rows'=>4, 'cols'=>50, 'style' => 'resize:none')); ?>
		<?php echo $form->error($model,'keterangan'); ?>
	</div>
	<!--<div class="row">
		<?php //echo $form->labelEx($model,'is_manager'); ?>
		<?php //echo $form->radioButtonList($model,'is_manager', array('1'=>'YA','0'=>'TIDAK'), 			array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
				  // ,'labelOptions'=>array('style'=>'display:inline'))); ?>
		<?php //echo $form->error($model,'is_manager'); ?>
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
</div>