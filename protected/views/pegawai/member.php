<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"- Daftar Anggota -",
	  )); ?>
<!--
<div class="alert alert-info" style="margin:5px 5px 10px 5px">
	<b><font color="#FF6B6B">Max Ambil Cuti</font> = Jumlah maksimal hari secara Default, yang dapat diajukan oleh pegawai dalam pengajuan sekali cuti</b>
</div>-->		 
<?php 
$maxCuti = Konfig::model()->findByPk(2)->max_ambil_cuti; 				
$this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'pegawai-grid',
	'dataProvider'=>$model->searchMember(),
	// 'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover',
	'columns'=>array(
		array(
			'header' => 'No',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:25px;'),
		),	
		array(
			'header' => 'NIK',
			'value' => '$data->nik',
			'htmlOptions' => array('style'=>'color:blue;'),
		),		
		array(
			'header' => 'Email',
			'name'=>'email',
			'value' => function($data) {
				if( $data["email"]!='' ){
					return "<span style='color:blue';>".$data["email"]."</span>";
				}else{
					return "<div style='background-color:red; color:white; padding:5px'>Email belum terisi ** </div>";
				}
			},
			'type'=>'html',	
			'htmlOptions' => array('style'=>'color:blue;'),
		),
		'nama_lengkap',
		array(
			'header' => 'Lokasi',
			'value' => '$data->lokasi->nama',
		),
		array(
			'header' => 'DOJ',
			'value' => 'date("d F Y",strtotime($data->doj))',
		),
		array(
			'header' => 'Sisa Cuti',
			'value' => function($data) {
				if( $data->is_doj_full==1 ){
					return $data["sisa_cuti"]-($data->lokasi->potong_cuti_bersama)." Hari";
				}elseif( $data->is_doj_full==0 && ($data["sisa_cuti"]>($data->lokasi->potong_cuti_bersama)) ){
					return $data["sisa_cuti"]-($data->lokasi->potong_cuti_bersama)." Hari";
				}else{
					return $data["sisa_cuti"]." Hari";
				}
			},
			'type'=>'html',	
			'htmlOptions' => array('style'=>'text-align:right'),
		),
		// array(
			// 'header' => 'Max Ambil Cuti',
			// 'value' => '$data->max_cuti > 0?$data->max_cuti:'.$maxCuti.' ." Hari"',
			// 'htmlOptions' => array('style'=>'width:110px; text-align:right;'),
		// ),
		// array(
			// 'class'=>'CButtonColumn',
			// 'template'=>'{view}',
		// ),
	
	),
)); ?>
<?php $this->endWidget();?>