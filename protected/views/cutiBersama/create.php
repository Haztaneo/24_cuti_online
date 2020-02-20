<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btList',
				'caption'=>'Daftar Cuti Bersama',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("admin"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
</div>

<?php $this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"- Tambah Cuti Bersama -",
	  )); ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php $this->endWidget();?>