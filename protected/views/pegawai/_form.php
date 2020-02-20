<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pegawai-form',
	'enableAjaxValidation'=>false,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php $minYear = date("Y", strtotime(date("Y-m-d") ."-70 year")); ?>
	<?php $maxYear = date("Y", strtotime(date("Y-m-d") ."-10 year")); ?>
	<?php $maxYear2 = date("Y", strtotime(date("Y-m-d"))); ?>
	<?php $yearRange = $minYear .':' .$maxYear; ?>
	<?php $yearRange2 = $minYear .':' .$maxYear2; ?>
	<?php $minDate = $minYear ."-01-01"; ?>	
	<?php $maxDate = $maxYear ."-12-31"; ?>	
	<?php $maxDate2 = $maxYear2 ."-12-31"; ?>	
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php /*$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
					'name'=>'term',
					'model'=>$model,
					'attribute'=>'uid',
					'value'=>$model->uid, 
					'source' => $this->createUrl('userLdap/loadData'),						
					'options' => array(
						'minLength' => '1',
					),
					'htmlOptions' => array(
						'placeholder' => 'Email Associate'
					),
				)); echo "&nbsp;&nbsp;<b>".Yii::app()->params['ldap_domain']."</b>";*/
		
              if( $flag==1){
				  echo $form->textField($model,'email',array('class'=>'span3','disabled'=>true )); 
		      }else{
				  $this->widget('ext.combobox.EJuiComboBox', array(
					'model' => $model,
					'attribute' => 'uid',
					'value'=>$model->uid, 
					'data' => UserLdap::model()->getOptions(),
					'options' => array(
						'allowText' => false,
					),
					'htmlOptions' => array('placeholder' => 'Email Associate', 'class'=>'span3'),
				)); echo "&nbsp;&nbsp;<b>".Yii::app()->params['ldap_domain']."</b>";
			}?>		
		<?php echo $form->error($model,'uid'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nik'); ?>
		<?php echo $form->textField($model,'nik',array('class'=>'span2','maxlength'=>50)); ?>
		<?php echo $form->error($model,'nik'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nama_lengkap'); ?>
		<?php echo $form->textField($model,'nama_lengkap',array('class'=>'span5','maxlength'=>50)); ?>
		<?php echo $form->error($model,'nama_lengkap'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nama_panggilan'); ?>
		<?php echo $form->textField($model,'nama_panggilan',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'nama_panggilan'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'doj'); ?>
		<?php if( $flag==1){
				  echo $form->textField($model,'doj',array('class'=>'span2','disabled'=>true )); 
		      }else{
				  $this->widget('zii.widgets.jui.CJuiDatePicker', 
				   array('model' => $model,
				        'name' => 'doj',
						'attribute' => 'doj',
						'htmlOptions' => array('class'=>'span2 form-control',
												'size' => '10',
												'maxlength' => '10',
												'placeholder'=>' yyyy-mm-dd',
												'readonly'=>true,),
						'options' => array ('dateFormat' => 'yy-mm-dd',
											'autoclose' => true,
											'changeMonth'=>true,
											'changeYear'=> true,
											'yearRange' => $yearRange2, //'1945:2015'
											'minDate' => $minDate, //1945-01-01
											'maxDate' => $maxDate2, //2015-12-31
										   ),
				));
			}?>	
		<?php echo $form->error($model,'doj'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'dept_divisi_id'); ?>
		<?php echo $form->dropDownList($model,'dept_divisi_id', DeptDivisi::model()->getOptions(),array('class'=>'span3', 'prompt'=>'-- Pilih Divisi --')); ?> 
		<?php echo $form->error($model,'dept_divisi_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'lokasi_id'); ?>
		<?php echo $form->dropDownList($model,'lokasi_id', Lokasi::model()->getOptions(),array('class'=>'span3', 'prompt'=>'-- Pilih Lokasi --')); ?> 
		<?php echo $form->error($model,'lokasi_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'sex'); ?>
		<?php echo $form->radioButtonList($model,'sex', array('L'=>'Laki-laki','P'=>'Perempuan'), 		array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
					   ,'labelOptions'=>array('style'=>'display:inline'))); ?>
		<?php echo $form->error($model,'sex'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'agama_id'); ?>
		<?php //echo $form->dropDownList($model,'agama_id', Agama::model()->getOptions(),array('class'=>'span3', 'prompt'=>'-- Pilih Agama --')); ?> 
		<?php echo $form->radioButtonList($model,'agama_id', Agama::model()->getOptions(), 		
					array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
					   ,'labelOptions'=>array('style'=>'display:inline'))); ?>
		<?php echo $form->error($model,'agama_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'tgl_lahir'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', 
				   array('model' => $model,
				        'name' => 'tgl_lahir',
						'attribute' => 'tgl_lahir',
						'htmlOptions' => array('class'=>'span2 form-control',
												'size' => '10',
												'maxlength' => '10',
												'placeholder'=>' yyyy-mm-dd',
												'readonly'=>true,),
						'options' => array ('dateFormat' => 'yy-mm-dd',
											'autoclose' => true,
											'changeMonth'=>true,
											'changeYear'=> true,
											'yearRange' => $yearRange, //'1945:2015'
											'minDate' => $minDate, //1945-01-01
											'maxDate' => $maxDate, //2015-12-31
										   ),
				));	?>
		<?php echo $form->error($model,'tgl_lahir'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'ktp'); ?>
		<?php echo $form->textField($model,'ktp',array('size'=>16,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'ktp'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'alamat_ktp'); ?>
		<?php echo $form->textArea($model,'alamat_ktp',array('rows'=>6, 'cols'=>50, 'class'=>'span6')); ?>
		<?php echo $form->error($model,'alamat_ktp'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'hp'); ?>
		<?php echo $form->textField($model,'hp',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'hp'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'nama_atasan'); ?>
		<?php /*$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
					'name'=>'termAtasan',
					'model'=>$model,
					'attribute'=>'nama_atasan',
					'value'=>$model->nama_atasan, 
					'source' => $this->createUrl('atasan/loadNama'),						
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
				'attribute' => 'nama_atasan',
				'value'=>$model->nama_atasan, 
				'data' => Atasan::model()->getOptions(),
				'options' => array(
					'allowText' => false,
				),
				'htmlOptions' => array('placeholder' => 'Nama Associate', 'class'=>'span3'),
			));
		?>		
		<?php echo $form->error($model,'nama_atasan'); ?>
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