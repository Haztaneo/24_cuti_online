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
			'caption'=>'Daftar Permission',
			'buttonType'=>'link',
			'url'=>Yii::app()->controller->createUrl("admin"),
			'htmlOptions'=>array('class'=>'btn btn-info'),
	));	?>	
</div>

<?php $this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"- Update Permission : ".$model->id." -",
	  )); ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php $this->endWidget();?>