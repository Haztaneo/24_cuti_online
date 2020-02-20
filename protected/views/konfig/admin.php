<div id='statusMsg'></div>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"- Konfigurasi -",
	  )); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'konfig-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover',
	'columns'=>array(
		array(
			'header' => 'Jumlah cuti setahun',
			'name'=>'jumlah_cuti_setahun',
			'value' => '$data["jumlah_cuti_setahun"]." Hari"',
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		array(
			'header' => 'Max ambil cuti',
			'name'=>'max_ambil_cuti',
			'value' => '$data["max_ambil_cuti"]." Hari"',
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		/*array(
			'header' => 'Jumlah cuti bersama - Tahun '.date("Y").'',
			'name'=>'jumlah_cuti_bersama',
			'value' => '$data["jumlah_cuti_bersama"]." Hari"',
			'htmlOptions' => array('style'=>'color:blue;'),
		),*/
		array(
			'header' => 'Minimal Tanggal DOJ',
			'name'=>'tgl_min_doj',
			'value' => '$data["tgl_min_doj"]',
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		array(
			'header' => 'Minimal Hari Pengajuan',
			'name'=>'min_tgl_pengajuan',
			'value' => '$data["min_tgl_pengajuan"]." Hari Sebelumnya"',
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
		),
	),
)); ?>
<?php $this->endWidget();?>