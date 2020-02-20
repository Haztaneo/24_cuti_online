<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btAdd',
				'caption'=>'Tambah Role Pengguna',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("create"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
			'name'=>'btList',
			'caption'=>'Daftar Role Pengguna',
			'buttonType'=>'link',
			'url'=>Yii::app()->controller->createUrl("admin"),
			'htmlOptions'=>array('class'=>'btn btn-info'),
	));	?>	
</div>


<?php 	
	$this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"- Update Role Pengguna : ".$model->id." -",
	)); 
	
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'role-form',
		'enableAjaxValidation'=>false,
	)); 
?>

<?php $this->renderPartial('_form', array('model'=>$model,'form'=>$form)); ?>
<?php $this->renderPartial('_permissionform', array('permission'=>$permission, 'model'=>$model, 'checked'=>$checked)); ?>
<?php $this->endWidget(); ?><!--CActiveForm-->
<?php $this->endWidget(); ?><!--zii.widgets.CPortlet-->