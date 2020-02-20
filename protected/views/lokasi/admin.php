<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btAdd',
				'caption'=>'Tambah Lokasi',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("create"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
</div>
<div id='statusMsg'></div>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"- Daftar Lokasi -",
	  )); ?>
	
<?php $this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'lokasi-grid',
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
			'header' => 'Akhir Pekan',
			'value' => function($data) {
				if( $data["senin"] > -1 ) echo "Senin , ";
				if( $data["selasa"] > -1 ) echo "Selasa , ";
				if( $data["rabu"] > -1 ) echo "Rabu , ";
				if( $data["kamis"] > -1 ) echo "Kamis , ";
				if( $data["jumat"] > -1 ) echo "Jumat , ";
				if( $data["sabtu"] > -1 ) echo "Sabtu , ";
				if( $data["minggu"] > -1 ) echo "Minggu";
			},
			'type'=>'html',	
		),
		array(
			'header' => 'Status',
			'name'=>'status',
			'value' => '$data["status"]==1?"AKTIF":"TIDAK AKTIF"',
			'htmlOptions' => array('style'=>'width:100px;'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
		),
	),
)); ?>
<?php $this->endWidget();?>