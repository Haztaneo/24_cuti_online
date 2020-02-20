<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btAdd',
				'caption'=>'Tambah Associate',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("create"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
</div>

<div id='statusMsg'></div>
	 
<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"- Daftar Associate -",
	  )); ?>

<div class="tab-content" style="margin-top:-15px">	  
<?php $this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'pegawai-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover table-condensed ',
	'columns'=>array(
		array(
			'header' => 'No',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:25px;'),
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
			'htmlOptions' => array('style'=>'color:blue;white-space: nowrap;'),			
		),		
		array(
			'header' => 'Nama Lengkap',
			'value' => '$data->nama_lengkap',
			'htmlOptions' => array('style'=>'white-space: nowrap;'),
		),	
		array(
			'header' => 'Divisi',
			'value' => '$data->deptDivisi->nama',
			'htmlOptions' => array('style'=>'white-space: nowrap;'),
		),
		array(
			'header' => 'Lokasi',
			'value' => '$data->lokasi->nama',
			'htmlOptions' => array('style'=>'white-space: nowrap;'),
		),
		array(
			'header' => 'DOJ',
			'value' => 'date("d-m-Y",strtotime($data->doj))',
			'htmlOptions' => array('style'=>'white-space: nowrap'),
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
			'htmlOptions' => array('style'=>'width:62px;text-align:right;white-space: nowrap'),
		),
		array(
			'header' => 'Nama Atasan',
			'name'=>'nama_atasan',
			'value' => function($data) {
				if( $data["nama_atasan"]!='' ){
					return "<span style='color:blue';>".$data["nama_atasan"]."</span>";
				}else{
					return "<div style='background-color:red; color:white; padding:5px'>Belum terisi ** </div>";
				}
			},
			'type'=>'html',	
			'htmlOptions' => array('style'=>'color:blue;white-space: nowrap'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'afterDelete'=>'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
			'htmlOptions' => array('style'=>'white-space: nowrap;'),
		),
	),
)); ?>
<?php $this->endWidget();?>
</div>