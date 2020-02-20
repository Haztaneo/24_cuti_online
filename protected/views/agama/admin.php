<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btAdd',
				'caption'=>'Tambah Agama',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("create"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
			// 'onclick'=>new CJavaScriptExpression('function(){alert("Save button has been clicked"); this.blur(); return false;}'),
		));	?>
</div>
<div id='statusMsg'></div>

<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"- Daftar Agama -",
	  )); ?>
	  
<div class="tab-content" style="margin-top:-15px">	 
<?php $this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'agama-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover table-condensed',
	'columns'=>array(
		array(
			'header' => 'No',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:25px;'),
		),
		array(
			'header' => 'Nama',
			'name'=>'nama',
			'value' => '$data["nama"]',
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
		),
	),
)); ?>
<?php $this->endWidget();?>
</div>