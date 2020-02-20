<div class="form" style="padding:10px">
<?php 
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'user-admin-form',
		'enableAjaxValidation'=>false,
	)); 
?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
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
				'data' => UserLdap::model()->getOptionsAdmin(),
				'options' => array(
					'allowText' => false,
				),
				'htmlOptions' => array('placeholder' => 'Nama Associate', 'class'=>'span3'),
			));
		?>		
		<?php echo $form->error($model,'nama'); ?>		
		<?php //echo $form->labelEx($model,'email'); ?>
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
				)); echo "&nbsp;&nbsp;<b>".Yii::app()->params['ldap_domain']."</b>"; */
		?>
		<?php //echo $form->error($model,'uid'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'is_receive_email'); ?>
		<?php echo $form->radioButtonList($model,'is_receive_email', array('1'=>'Ya','0'=>'Tidak'), 
								array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
								,'labelOptions'=>array('style'=>'display:inline'))); ?>
		<?php echo $form->error($model,'is_receive_email'); ?>
	</div>	
	<div class="row">
		<?php echo $form->labelEx($model,'show_admin_page'); ?>
		<?php echo $form->radioButtonList($model,'show_admin_page', array('1'=>'Ya','0'=>'Tidak'), 
							array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
							,'labelOptions'=>array('style'=>'display:inline'))); ?>
		<?php echo $form->error($model,'show_admin_page'); ?>
	</div>		
	<hr/>
	<div class="row">
		<?php echo $form->labelEx($model,'show_report'); ?>
		<?php echo $form->radioButtonList($model,'show_report', array('1'=>'Ya','0'=>'Tidak'), 
							array( 'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;'
							,'labelOptions'=>array('style'=>'display:inline', 'id'=>'tes'))); ?>
		<?php echo $form->error($model,'show_report'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'akses_report',array( 'id'=>'txtAkses') ); ?>
		<?php echo $form->dropDownList($model,'akses_report', array( '2'=>'All Division (Semua associate di semua divisi)', '1'=>'Private Division (Semua associate dalam 1 divisi)', '0'=>'Private (Hanya diri sendiri)'),array('class'=>'span5')); ?> 
		<?php echo $form->error($model,'show_report'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'user_role_id',array( 'id'=>'txtRole') ); ?>
		<?php echo $form->dropDownList($model,'user_role_id', CHtml::listData(UserRole::model()->findAll( ), 'id', 'name' ) ,array('class'=>'span3')); ?> 
		<?php echo $form->error($model,'user_role_id'); ?>
	</div>
	<hr/>
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', array('1'=>'Aktif','0'=>'Non Aktif'),array('class'=>'span2')); ?> 
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

<script>
$(document).ready(function() {
		
				// alert( $("#UserAdmin_akses_report").val() ); // or $(this).val()
		
		var flag = $("#UserAdmin_show_report input[type=radio]:checked").val();
		if( flag == 0 ){
			$("#UserAdmin_akses_report").prop('disabled', true);
			$("#UserAdmin_user_role_id").prop('disabled', true);
		}else{ 
			$("#UserAdmin_akses_report").prop('disabled', false);
			$("#UserAdmin_user_role_id").prop('disabled', false);
		}

		$("input[name$='UserAdmin[show_report]']").click(function() {			
			var akses = $(this).val();
			if( akses == 0 ){
				$("#UserAdmin_akses_report").prop('disabled', true);
				$("#UserAdmin_user_role_id").prop('disabled', true);
			}else{ 
				$("#UserAdmin_akses_report").prop('disabled', false);
				$("#UserAdmin_user_role_id").prop('disabled', false);
			}
		});
		
		// if( $("#UserAdmin_akses_report").val() == 0 && flag == 1 ){
			// $("#UserAdmin_user_role_id").prop('disabled', false);
		// }else{ 
			// $("#UserAdmin_user_role_id").prop(true);
		// }
		
		// $("#UserAdmin_akses_report").on("change", function() {
			// if(  this.value == 0 ){
				// $("#UserAdmin_user_role_id").prop('disabled', true);
			// }else{ 
				// $("#UserAdmin_user_role_id").prop('disabled', false);
			// }
		// });

	});
</script>