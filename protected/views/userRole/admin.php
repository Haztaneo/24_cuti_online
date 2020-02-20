<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btAdd',
				'caption'=>'Tambah Role Pengguna',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("create"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
</div>
<div id='statusMsg'></div>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"- Daftar Role Pengguna -",
	  )); ?>
	  
<?php $this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'user-role-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover',
	'columns'=>array(
		array(
			'header' => 'No',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:25px;'),
		),		
		array(
			'header' => 'Nama Role',
			'name'=>'name',
			'value' => '$data["name"]',
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		array(
			'header' => 'Deskripsi',
			'name'=>'description',
			'value' => '$data["description"]',
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