<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btList',
				'caption'=>'Daftar Tipe Izin',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("admin"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
</div>

<?php $this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"- Tambah Tipe Izin -",
	  )); ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php $this->endWidget();?>