<?php $modelPegawai = Pegawai::model()->find(array("condition"=>"uid='".Yii::app()->user->id."'"));	
	if( count($modelPegawai)==0 ){
		echo '<div class="alert alert-warning">
				<h5><font color="#FF6B6B">Anda belum terdaftar dalam menu Pegawai. Tambahkan data anda pada menu <a href="'.Yii::app()->controller->createUrl("pegawai/create").'">Pegawai</a> untuk dapat mengakses halaman ini</font></h5>
			  </div>';		
	/*}elseif( $modelPegawai->nama_atasan==null ){
		echo '<div class="alert alert-warning">
				<h5><font color="#FF6B6B">Untuk dapat mengakses halaman ini nama atasan Anda harus terdaftar terlebih dahulu. <br/>Silahkan hubungi Admin untuk mendaftarkan nama atasan Anda.</h5>
			  </div>';		*/
	}else{
		echo '<div class="alert alert-info" style="margin:0px 5px 10px 0px">
				<b><font color="#FF6B6B">** </font> &nbsp;=> Memotong jatah cuti tahunan</b>
			  </div>';	
		foreach(Yii::app()->user->getFlashes() as $key => $message) {
			echo '<div style="width:94%" class="alert alert-' . $key . '">'
				 .'<button type="button" class="close" data-dismiss="alert">×</button>'
				 . $message . "</div>\n"; 
		}
?>

<?php $this->beginWidget('zii.widgets.CPortlet', array( )); ?>
<div class="tabbable" style="margin:15px"> <!-- Only required for left/right tabs -->
	<ul class="nav nav-pills nav-justified" style="margin-bottom:-2px">
		<li class="active">
			<a href="#tab1" data-toggle="tab">
				<span class="tab_label_open"><b>RIWAYAT PENGAJUAN - TAHUN <?php echo date('Y');?> <span id="open"></span></span></b></a>
		</li>
		<li>
			<a href="#tab2" data-toggle="tab">
				<span class="tab_label_close" style="color:#FFB566">
				<b>RIWAYAT PENGAJUAN - SEBELUM TAHUN <?php echo date('Y');?></b>
				<span id="close"></span></span>
			</a>
		</li>
	</ul>
	<hr/>
	<div class="tab-content" style="margin-top:-15px">
		<div class="tab-pane active" id="tab1">  
		<?php	$this->widget('zii.widgets.grid.CGridView', array(		
					'id'=>'izin-cuti-grid1',
					'dataProvider'=>$model->searchCustom(1),
					'itemsCssClass'=>'table table-striped',
					'template'=>"{items}{summary}{pager}",
					'columns'=>array(
						array(
							'header' => '<font color="#3A87AD">No</font>',
							'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
							'htmlOptions' => array('style'=>'text-align: center; width:2%;white-space: nowrap'),
						),
						array(
							'header' => '<font color="#3A87AD">NO. FORM</font>',
							'name'=>'kode',
							'value' => function($data) {
										if( $data->tipeIzin->is_potong_cuti == 1 ){
											return "<span style='color:#6363FF;white-space:nowrap;';>
											".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
											</span>";
										}else{
											return "<span style='color:#6363FF;white-space:nowrap;';>
											".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
										}
									},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'width:18%;white-space: nowrap'),
						),
						array(
							'header' => '<div style="text-align:center;color:#3A87AD">Tanggal</div>',
							'name'=>'tgl_mulai',
							'value'=>'date("d M",strtotime($data->tgl_mulai))." - ".date("d M",strtotime($data->tgl_akhir))',	
							'htmlOptions' => array('style'=>'color:#6363FF; white-space:nowrap;text-align:center;width:10%;'),
						),				
						array(
							'header' => '<div style="text-align:center;">Jumlah</div>',
							'name'=>'jumlah_hari',
							'value' => function($data) {
								return '<span class="badge badge-success">'.$data["jumlah_hari"].' Hari</span>';
							},
							'htmlOptions' => array('style'=>'text-align:center;width:10%;white-space: nowrap'),
							'type'=>'html',	
						),
						array(
							'header' => '<font color="#3A87AD">Alasan</font>',
							'value' => '$data["alasan"]',
							'htmlOptions' => array('style'=>'word-break: break-all;color:#6363FF;white-space: nowrap'),
						),
						array(
							'header' => '<font color="#3A87AD">STATUS</font>',
							'value' => function($data) {
								if( $data["status"] == 1 ){
									return "<span class='badge badge-info' style='font-size:11px;font-weight: bold;padding:5px';>DISETUJUI</span>";
								}else if( $data["status"]==2 ){
									return "<span class='badge badge-important' style='font-size:11px;font-weight: bold;padding:5px';>DIBATALKAN</span>";
								}else{
									return "<span class='badge' style='font-size:11px;font-weight: bold;padding:5px';>PENDING</span>";
								}
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'width:10%;white-space: nowrap'),
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{view}',
							'buttons'=>array
							(
								'view' => array
								(
									'label'=> 'DETAIL',
									'options'=>array('class'=>'btn btn-null btn-mini'),	
								),
							),	
							'htmlOptions' => array('style'=>'white-space:nowrap;width:5%;'),
						),		
					),
				));
			?>
		</div>
		<div class="tab-pane" id="tab2">			
	    <?php
			 $this->widget('zii.widgets.grid.CGridView', array(		
					'id'=>'izin-cuti-grid0',
					'dataProvider'=>$model->searchCustom(0),
					'template'=>'{items}{summary}{pager}',
					'itemsCssClass'=>'table table-striped',
					'columns'=>array(
						array(
							'header' => '<font color="#3A87AD">No</font>',
							'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
							'htmlOptions' => array('style'=>'text-align: center; width:2%;white-space: nowrap'),
						),		
						array(
							'header' => '<font color="#3A87AD">Tahun</font>',
							'name'=>'tahun',
							'value' => '$data["tahun"]',
							'htmlOptions' => array('style'=>'color:#7A7A7A;width:7%;white-space: nowrap'),
						),
						array(
							'header' => '<font color="#3A87AD">NO. FORM</font>',
							'name'=>'kode',
							'value' => function($data) {
										if( $data->tipeIzin->is_potong_cuti == 1 ){
											return "<span style='color:#7A7A7A;white-space:nowrap;';>
											".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
											</span>";
										}else{
											return "<span style='color:#7A7A7A;white-space:nowrap;';>
											".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
										}
									},
							'type'=>'html',
							'htmlOptions' => array('style'=>'width:18%;white-space: nowrap'),
						),
						array(
							'header' => '<div style="text-align:center">Tanggal</div>',
							'name'=>'tgl_mulai',
							'value'=>'date("d M",strtotime($data->tgl_mulai))." - ".date("d M",strtotime($data->tgl_akhir))',
							'htmlOptions' => array('style'=>'color:#7A7A7A;width:90px; text-align:center;white-space: nowrap'),
						),
						array(
							'header' => '<div style="text-align:center;">Jumlah</div>',
							'name'=>'jumlah_hari',
							'value' => function($data) {
								return '<span class="badge badge-success">'.$data["jumlah_hari"].' Hari</span>';
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'text-align:center;width:10%;white-space: nowrap'),
						),
						array(
							'header' => '<font color="#3A87AD">Alasan</font>',
							'value' => '$data["alasan"]',
							'htmlOptions' => array('style'=>'word-break: break-all;color:#7A7A7A;'),
						),
						array(
							'header' => '<font color="#3A87AD">STATUS</font>',
							'value' => function($data) {
								if( $data["status"] == 1 ){
									return "<span class='badge badge-info' style='font-size:11px;font-weight: bold;padding:5px';>DISETUJUI</span>";
								}else if( $data["status"]==2 ){
									return "<span class='badge badge-important' style='font-size:11px;font-weight: bold;padding:5px';>DIBATALKAN</span>";
								}else{
									return "<span class='badge' style='font-size:11px;font-weight: bold;padding:5px';>PENDING</span>";
								}
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'width:10%;white-space: nowrap'),
						),
						array(
							'class'=>'CButtonColumn',
							'template'=>'{view}',
							'buttons'=>array
							(
								'view' => array
								(
									'label'=> 'DETAIL',
									'options'=>array('class'=>'btn btn-null btn-mini'),	
								),
							),	
							'htmlOptions' => array('style'=>'white-space:nowrap;width:5%;'),
						),		
					),
				));
			 ?>
		</div>
	</div>
</div>
<?php $this->endWidget();?>

<?php
		/* OLD
		ob_start();
		$this->widget('zii.widgets.grid.CGridView', array(		
			'id'=>'izin-cuti-grid1',
			'dataProvider'=>$model->searchCustom(1),
			// 'filter'=>$model,
			'itemsCssClass'=>'table table-striped table-bordered table-hover',
			'columns'=>array(
				array(
					'header' => 'No',
					'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
					'htmlOptions' => array('style'=>'text-align: center; width:25px;'),
				),
				array(
					'header' => 'No. FORM',
					'name'=>'kode',
					'value' => function($data) {
								if( $data->tipeIzin->is_potong_cuti == 1 ){
									return "<span style='color:#6363FF;font-size:11px;white-space:nowrap;';>
									".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
									</span>";
								}else{
									return "<span style='color:#6363FF;font-size:11px;white-space:nowrap;';>
									".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
								}
							},
					'type'=>'html',	
				),
				array(
					'header' => '<div style="text-align:center">Tanggal</div>',
					'name'=>'tgl_mulai',
					'value'=>'date("d M",strtotime($data->tgl_mulai))." - ".date("d M",strtotime($data->tgl_akhir))',	
					'htmlOptions' => array('style'=>'font-size:11px; color:#6363FF; white-space:nowrap; text-align:center;width:90px;'),
				),				
				array(
					'header' => 'Jumlah',
					'name'=>'jumlah_hari',
					'value' => '$data["jumlah_hari"]." Hari"',
					'htmlOptions' => array('style'=>'font-size:11px;color:#6363FF;text-align:right;width:50px;'),
				),
				array(
					'header' => 'Alasan',
					'value' => '$data["alasan"]',
					'htmlOptions' => array('style'=>'font-size:11px;word-break: break-all;color:#6363FF;'),
				),
				array(
					'header' => 'STATUS',
					'value' => function($data) {
						if( $data["status"] == 1 ){
							return "<span style='font-size:11px;font-weight: bold;color:#6CFC6A';>DISETUJUI</span>";
						}else if( $data["status"]==2 ){
							return "<span style='font-size:11px;font-weight: bold;color:#FF4F4F';>DIBATALKAN</span>";
						}else{
							return "<span style='font-size:11px;font-weight: bold;color:silver';>PENDING</span>";
						}
					},
					'type'=>'html',	
					'htmlOptions' => array('style'=>'width:60px;'),
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{view}',
					'buttons'=>array
					(
						'view' => array
						(
							'label'=> 'DETAIL',
							'options'=>array('class'=>'btn btn-null btn-mini'),	
						),
					),			
				),		
			),
		));
		$tab1Content=ob_get_contents();
		ob_end_clean();

		ob_start();
		$this->widget('zii.widgets.grid.CGridView', array(		
			'id'=>'izin-cuti-grid0',
			'dataProvider'=>$model->searchCustom(0),
			// 'filter'=>$model,
			'itemsCssClass'=>'table table-striped table-bordered table-hover',
			'columns'=>array(
				array(
					'header' => 'No',
					'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
					'htmlOptions' => array('style'=>'text-align: center; width:25px;'),
				),		
				array(
					'header' => 'TAHUN',
					'name'=>'tahun',
					'value' => '$data["tahun"]',
					'htmlOptions' => array('style'=>'font-size:11px;color:#7A7A7A;width:40px'),
				),
				array(
					'header' => 'No. FORM',
					'name'=>'kode',
					'value' => function($data) {
								if( $data->tipeIzin->is_potong_cuti == 1 ){
									return "<span style='color:#7A7A7A;font-size:11px;white-space:nowrap;';>
									".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
									</span>";
								}else{
									return "<span style='color:#7A7A7A;font-size:11px;white-space:nowrap;';>
									".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
								}
							},
					'type'=>'html',						
				),
				array(
					'header' => '<div style="text-align:center">Tanggal</div>',
					'name'=>'tgl_mulai',
					'value'=>'date("d M",strtotime($data->tgl_mulai))." - ".date("d M",strtotime($data->tgl_akhir))',
					'htmlOptions' => array('style'=>'font-size:11px; color:#7A7A7A;width:90px; text-align:center'),
				),
				array(
					'header' => 'Jumlah',
					'name'=>'jumlah_hari',
					'value' => '$data["jumlah_hari"]." Hari"',
					'htmlOptions' => array('style'=>'font-size:11px;color:#7A7A7A;text-align:right;width:50px;'),
				),
				array(
					'header' => 'Alasan',
					'value' => '$data["alasan"]',
					'htmlOptions' => array('style'=>'font-size:11px;word-break: break-all;color:#7A7A7A;'),
				),
				array(
					'header' => 'STATUS',
					'value' => function($data) {
						if( $data["status"] == 1 ){
							return "<span style='font-size:11px;font-weight: bold;color:#6CFC6A';>DISETUJUI</span>";
						}else if( $data["status"]==2 ){
							return "<span style='font-size:11px;font-weight: bold;color:#FF4F4F';>DIBATALKAN</span>";
						}else{
							return "<span style='font-size:11px;font-weight: bold;color:silver';>PENDING</span>";
						}
					},
					'type'=>'html',	
					'htmlOptions' => array('style'=>'width:60px;'),
				),
				array(
					'class'=>'CButtonColumn',
					'template'=>'{view}',
					'buttons'=>array
					(
						'view' => array
						(
							'label'=> 'DETAIL',
							'options'=>array('class'=>'btn btn-null btn-mini'),	
						),
					),			
				),		
			),
		));
		$tab2Content=ob_get_contents();
		ob_end_clean();

		$thisYear = '<font color="#006EDD">RIWAYAT - TAHUN '.date('Y').'</font>';
		$lastYear = '<font color="#FF8300">RIWAYAT - SEBELUM TAHUN '.date('Y').'</font>';
		$this->widget('zii.widgets.jui.CJuiTabs',array(
			'tabs'=>array(
				$thisYear => array('content' => $tab1Content,'id' => 'ThisYear'),
				$lastYear =>array('content'=> $tab2Content, 'id'=>'LastYear'),
			),
			'options'=>array(
				'collapsible'=>false,
			),
		));	
		END OLD */
	}
?>	