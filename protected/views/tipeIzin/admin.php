<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btAdd',
				'caption'=>'Tambah Tipe Izin',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("create"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
</div>
<div id='statusMsg'></div>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"- Daftar Tipe Izin -",
	  )); ?>
	  
<?php $this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'tipe-izin-grid',
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
			'header' => 'Nama',
			'name'=>'nama',
			'value' => '$data["nama"]',
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		array(
			'header' => 'Potong Jatah Cuti',
			'name'=>'is_potong_cuti',
			'value' => '$data["is_potong_cuti"]==1?"YA":"TIDAK"',
			'htmlOptions' => array('style'=>'text-align: center; width:130px;'),
		),
		// array(
			// 'header' => 'Jatah Max Izin / Cuti',
			// 'name'=>'jatah_cuti',
			// 'value' => '$data["jatah_cuti"]." hari"',
			// 'htmlOptions' => array('style'=>'text-align: right; width:150px;'),
		// ),
		array(
			'header' => 'Wajib Attach Dokumen',
			'name'=>'is_must_attach',
			'value' => '$data["is_must_attach"]==1?"YA":"TIDAK"',
			'htmlOptions' => array('style'=>'text-align: center; width:155px;'),
		),
		array(
			'header' => 'Status',
			'name'=>'status',
			'value' => '$data["status"]==1?"Aktif":"TIDAK Aktif"',
			'htmlOptions' => array('style'=>'text-align: center; width:50px;'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
		),
	),
)); ?>
<?php $this->endWidget();?>