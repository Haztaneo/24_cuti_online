<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btAdd',
				'caption'=>'Tambah Atasan',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("create"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
</div>
<div id='statusMsg'></div>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"- Daftar Atasan -",
	  )); ?>
	 
<div class="tab-content" style="margin-top:-15px">	 	 
<?php $this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'atasan-grid',
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
			'header' => 'Nama Dept / Divisi',
			'name'=>'dept_divisi_id',
			'value' => '$data->deptDivisi->nama',
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		array(
			'header' => 'Nama',
			'name'=>'nama',
			'value' => '$data["nama"]',
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		'keterangan',
		/*array(
			'header' => 'Is Manager',
			'name'=>'is_manager',
			'value' => '$data["is_manager"]==1?"YA":"TIDAK"',
			'htmlOptions' => array('style'=>'width:80px;text-align:center'),
		),*/
		array(
			'header' => 'Status',
			'name'=>'status',
			'value' => '$data["status"]==1?"AKTIF":"TIDAK AKTIF"',
			'htmlOptions' => array('style'=>'width:80px;text-align:center'),
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