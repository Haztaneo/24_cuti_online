<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btAdd',
				'caption'=>'Tambah Admin HRD',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("create"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
</div>
<div id='statusMsg'></div>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>"- Daftar Admin HRD -",
	  )); ?>
	 
<div class="tab-content" style="margin-top:-15px">	 	 	 
<?php $this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'user-admin-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover table-condensed',
	'columns'=>array(
		array(
			'header' => '<div style="font-size:x-small;color:#3a87ad">No</div>',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:25px;'),
		),		
		array(
			'header' => '<div style="font-size:x-small;color:#3a87ad">Email</div>',
			'name'=>'email',
			'value' => '$data["email"]',
			'htmlOptions' => array('style'=>'color:blue; white-space: nowrap'),
			'type'=>'html',	
		),
		array(
			'header' => '<div style="font-size:x-small;color:#3a87ad">Nama</div>',
			'name'=>'nama',
			'value' => '$data["nama"]',
			'htmlOptions' => array('style'=>'white-space: nowrap;'),
			'type'=>'html',	
		),
		array(
			'header' => '<div style="font-size:x-small;color:#3a87ad">Terima Notif Email</div>',
			'value' => '$data["is_receive_email"]==1?"Ya":"<font color=\"red\">Tidak</font>"',
			'htmlOptions' => array('style'=>'text-align: center; '),
			'type'=>'html',	
		),
		array(
			'header' => '<div style="font-size:x-small;color:#3a87ad">Akses Halaman Admin</div>',
			'value' => '$data["show_admin_page"]==1?"Ya":"<font color=\"red\">Tidak</font>"',
			'htmlOptions' => array('style'=>'text-align: center;  '),
			'type'=>'html',	
		),
		array(
			'header' => '<div style="font-size:x-small;color:#3a87ad">Akses Halaman Laporan</div>',
			'value' => function($data) {		
								if( $data->akses_report==2 )
									$akses = "<div style='color:blue'>[ All Division ]</div>"; 
								elseif( $data->akses_report==1 )
									$akses = "<div style='color:green'>[ Private Division ]</div>";
								else
									$akses = "<div style='color:#919191'>[ Private ]</div>";
									
								// $akses = $data->akses_report==1?"Public":"Private";
								if( $data->show_report == 1 ){
									return $akses;
								}else{
									return "<div style='color:red'>Tidak</div>";
								}
							},
			'htmlOptions' => array('style'=>'text-align: center;  white-space: nowrap;'),
			'type' => 'html'
		),		
		array(
			'header' => '<div style="font-size:x-small;color:#3a87ad">Role</div>',
			'value' => function($data) {						
						$tRole = UserRole::model()->find( 'id = '.$data['user_role_id'] ); 
						if( count($tRole) > 0 && $data->show_report == 1 ){
							return $tRole->name;
						}else{
							return " - ";
						}
					},
			'htmlOptions' => array('style'=>'white-space: nowrap;'),
			'type'=>'html',	
		),		
		array(
			'header' => '<div style="font-size:x-small;color:#3a87ad">Status</div>',
			'value' => '$data["status"]==1?"Aktif":"Inactive"',
			'htmlOptions' => array('style'=>'text-align: center; width:50px;'),
			'type'=>'html',	
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