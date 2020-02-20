<?php 
	$modelPegawai = Pegawai::model()->find(array("condition"=>"uid='".Yii::app()->user->id."'"));	
	if( count($modelPegawai)==0 ){
		echo '<div class="alert alert-warning">
				<h5><font color="#FF6B6B">Anda belum terdaftar dalam menu Pegawai. Tambahkan data anda pada menu <a href="'.Yii::app()->controller->createUrl("pegawai/create").'">Pegawai</a> untuk dapat mengakses halaman ini</font></h5>
			  </div>';		
	/*}elseif( $modelPegawai->nama_atasan==null ){
		echo '<div class="alert alert-warning">
				<h5><font color="#FF6B6B">Untuk dapat mengakses halaman ini nama atasan Anda harus terdaftar terlebih dahulu. <br/>Silahkan hubungi Admin untuk mendaftarkan nama atasan Anda.</h5>
			  </div>';*/		
	}else{
		echo '<div class="alert alert-info" style="margin:0px 5px 10px 0px">
			<b><font color="#FF6B6B">**</font> &nbsp;=> Memotong jatah cuti tahunan</b>
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
				<span class="tab_label_open">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<font color="#D1D1D1"><b>CUTI PENDING</b></font>
					<span class="badge pull-right"><?php echo $model->searchRequest(0)->totalItemCount; ?></span>
					<span></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
			</a>
		</li>
		<li>
			<a href="#tab2" data-toggle="tab">
				<span class="tab_label_close">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<font color="#9BCDFF"><b>CUTI DISETUJUI</b></font>
					<span class="badge badge-info pull-right"><?php echo $model->searchRequest(1)->totalItemCount; ?></span>
					<span id="close"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
			</a>
		</li>
		<li>
			<a href="#tab3" data-toggle="tab">
				<span class="tab_label_close" style="color:#FFB566">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<font color="#FFD8B2"><b>CUTI DIBATALKAN</b></font>
					<span class="badge badge-warning pull-right"><?php echo $model->searchRequest(2)->totalItemCount; ?></span>
					<span id="close"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
			</a>
		</li>
	</ul>
	<hr/>
	<div class="tab-content" style="margin-top:-15px">
		<div class="tab-pane active" id="tab1">  		
		<?php
			$this->widget('zii.widgets.grid.CGridView', array(		
				'id'=>'izin-cuti-grid0',
				'dataProvider'=>$model->searchRequest(0),
				// 'itemsCssClass'=>'table table-striped table-bordered table-hover',
				'itemsCssClass'=>'table table-striped',
				'template'=>"{items}{summary}{pager}",
				'columns'=>array(
						array(
							'header' => '<font color="#3A87AD">No</font>',
							'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
							'htmlOptions' => array('style'=>'text-align: center; width:2%;'),
						),	
						array(
							'header' => 'NO. FORM',
							'name'=>'kode',
							'value' => function($data) {
								if( $data->tipeIzin->is_potong_cuti == 1 ){
									return "<span>".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
									</span>";
								}else{
									return "<span>".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
								}
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'color:#7A7A7A;white-space:nowrap;width:17%'),
						),
						array(
							'header' => '<font color="#3A87AD">Nama</font>',
							'value' => function($data) {
								return $data["pegawai_nama_lengkap"].'<br/> 
								 <span class="badge badge-success">'.$data["dept_divisi_nama"].'</span>';
							},
							'type'=>'html',		
							'htmlOptions' => array('style'=>'color:#7A7A7A; white-space:nowrap;'),
						),
						array(
							'header' => '<div style="text-align:center">Tanggal</div>',
							'name'=>'tgl_mulai',
							'value' => function($data) {
								return date('d M',strtotime($data['tgl_mulai'])).' - '.date('d M',strtotime($data['tgl_akhir'])).'<br/> 
								 <span class="badge badge-success">'.$data["jumlah_hari"].' Hari</span>';
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'color:#7A7A7A; white-space:nowrap; text-align:center;'),
						),
						array(
							'header' => '<font color="#3A87AD">Alasan</font>',
							'name'=>'alasan',
							'value' => '$data["alasan"]',
							'htmlOptions' => array('style'=>'word-break: break-all;color:#7A7A7A;'),
						),
						array(
							'header' => '<font color="#3A87AD">STATUS</font>',
							'value' => function($data) {
								if( $data["status_proses"]==0 ){
									return "<span style='font-size:11px;font-weight: bold;color:#3A87AD';>On Leader</span>";
								}else if( $data["status_proses"]==1 ){
									return "<span style='font-size:11px;font-weight: bold;color:#FF4F4F';>On HRD</span>";
								}
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'white-space:nowrap;width:9%;'),
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
				'id'=>'izin-cuti-grid1',
				'dataProvider'=>$model->searchRequest(1),
				'itemsCssClass'=>'table table-striped',
				'template'=>"{items}{summary}{pager}",
				'columns'=>array(
						array(
							'header' => '<font color="#3A87AD">No</font>',
							'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
							'htmlOptions' => array('style'=>'text-align: center; width:2%;'),
						),	
						array(
							'header' => 'NO. FORM',
							'name'=>'kode',
							'value' => function($data) {
										if( $data->tipeIzin->is_potong_cuti == 1 ){
											return "<span>".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
											</span>";
										}else{
											return "<span>".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
										}
									},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'color:#006EDD;white-space:nowrap;width:17%;'),
						),
						array(
							'header' => '<font color="#3A87AD">Nama</font>',
							'value' => function($data) {
								return $data["pegawai_nama_lengkap"].'<br/> 
								 <span class="badge badge-success">'.$data["dept_divisi_nama"].'</span>';
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'color:#006EDD; white-space:nowrap;'),
						),
						array(
							'header' => '<div style="text-align:center">Tanggal</div>',
							'name'=>'tgl_mulai',
							'value' => function($data) {
								return date('d M',strtotime($data['tgl_mulai'])).' - '.date('d M',strtotime($data['tgl_akhir'])).'<br/> 
								 <span class="badge badge-success">'.$data["jumlah_hari"].' Hari</span>';
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'color:#006EDD; white-space:nowrap; text-align:center'),
						),
						array(
							'header' => '<font color="#3A87AD">Alasan</font>',
							'name'=>'alasan',
							'value' => '$data["alasan"]',
							'htmlOptions' => array('style'=>'word-break: break-all;color:#006EDD;'),
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
		<div class="tab-pane" id="tab3">	
		<?php
			$this->widget('zii.widgets.grid.CGridView', array(		
				'id'=>'izin-cuti-grid2',
				'dataProvider'=>$model->searchRequest(2),
				'itemsCssClass'=>'table table-striped',
				'template'=>"{items}{summary}{pager}",
				'columns'=>array(
						array(
							'header' => '<font color="#3A87AD">No</font>',
							'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
							'htmlOptions' => array('style'=>'text-align: center; width:2%;'),
						),	
						array(
							'header' => 'NO. FORM',
							'name'=>'kode',
							'value' => function($data) {
										if( $data->tipeIzin->is_potong_cuti == 1 ){
											return "<span>".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
											</span>";
										}else{
											return "<span>".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
										}
									},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'color:#F89406;white-space:nowrap;width:17%;'),
						),
						array(
							'header' => '<font color="#3A87AD">Nama</font>',
							'value' => function($data) {
								return $data["pegawai_nama_lengkap"].'<br/> 
								 <span class="badge badge-success">'.$data["dept_divisi_nama"].'</span>';
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'color:#F89406; white-space:nowrap;'),
						),
						array(
							'header' => '<div style="text-align:center">Tanggal</div>',
							'name'=>'tgl_mulai',
							'value' => function($data) {
								return date('d M',strtotime($data['tgl_mulai'])).' - '.date('d M',strtotime($data['tgl_akhir'])).'<br/> 
								 <span class="badge badge-success">'.$data["jumlah_hari"].' Hari</span>';
							},
							'type'=>'html',	
							'htmlOptions' => array('style'=>'color:#F89406; white-space:nowrap; text-align:center'),
						),
						array(
							'header' => 'Alasan',
							'name'=>'alasan',
							'value' => '$data["alasan"]',
							'htmlOptions' => array('style'=>'word-break: break-all;color:#F89406;'),
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
		/*OLD
		$dataColumn = array(
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
										return "<span style='color:#7A7A7A;font-size:11px;white-space:nowrap;';>
										".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
										</span>";
									}else{
										return "<span style='color:#7A7A7A;font-size:11px;white-space:nowrap;';>
										".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
									}
								},
								'type'=>'html',	
								'htmlOptions' => array('style'=>'color:#7A7A7A;font-size:11px;white-space:nowrap;'),
							),
							array(
								'header' => 'Nama',
								'value' => '$data["pegawai_nama_lengkap"]."<br/>[ ".$data["dept_divisi_nama"]." ]"',
								'type'=>'html',	
								'htmlOptions' => array('style'=>'font-size:11px;color:#7A7A7A; white-space:nowrap;'),
							),
							// array(
								// 'header' => 'Sisa Cuti',
								// 'value' => function($data) {
									// $mp = Pegawai::model()->find('uid="'.$data['uid'].'"');
									// if( $mp->is_doj_full == 0 ){
										// return "<span style='color:#7A7A7A;font-size:11px;'>
										// ".$data["sisa_cuti"]." Hari</span>";
									// }else{
										// return "<span style='color:#7A7A7A;font-size:11px;'>
										// ".$data["sisa_cuti"]-$data["jumlah_cuti_bersama"]." Hari</span>";
									// }
								// },
								// 'type'=>'html',	
								// 'htmlOptions' => array('style'=>'width:62px;text-align:right'),
							// ),
							array(
								'header' => '<div style="text-align:center">Tanggal</div>',
								'name'=>'tgl_mulai',
								'value'=>'date("d M",strtotime($data->tgl_mulai))." - ".date("d M",strtotime($data->tgl_akhir))."<br/>( ".$data["jumlah_hari"]." Hari )"',
								'type'=>'html',	
								'htmlOptions' => array('style'=>'font-size:11px; color:#7A7A7A; white-space:nowrap; text-align:center'),
							),
							array(
								'header' => 'Alasan',
								'name'=>'alasan',
								'value' => '$data["alasan"]',
								'htmlOptions' => array('style'=>'font-size:11px;word-break: break-all;color:#7A7A7A;'),
							),
							array(
								'header' => 'STATUS',
								'value' => function($data) {
									if( $data["status_proses"]==0 ){
										return "<span style='font-size:11px;font-weight: bold;color:#3A87AD';>On Leader</span>";
									}else if( $data["status_proses"]==1 ){
										return "<span style='font-size:11px;font-weight: bold;color:#FF4F4F';>On HRD</span>";
									}
								},
								'type'=>'html',	
								'htmlOptions' => array('style'=>'white-space:nowrap;'),
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
						);
			
		ob_start();
		$this->widget('zii.widgets.grid.CGridView', array(		
			'id'=>'izin-cuti-grid0',
			'dataProvider'=>$model->searchRequest(0),
			// 'filter'=>$model,
			'itemsCssClass'=>'table table-striped table-bordered table-hover',
			'columns'=>$dataColumn,
		));
		$tab1Content=ob_get_contents();
		ob_end_clean();

		ob_start();
		$this->widget('zii.widgets.grid.CGridView', array(		
			'id'=>'izin-cuti-grid1',
			'dataProvider'=>$model->searchRequest(1),
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
										return "<span style='color:#006EDD;font-size:11px;white-space:nowrap;';>
										".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
										</span>";
									}else{
										return "<span style='color:#006EDD;font-size:11px;white-space:nowrap;';>
										".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
									}
								},
						'type'=>'html',	
						'htmlOptions' => array('style'=>'color:#006EDD;font-size:11px;white-space:nowrap;'),
					),
					array(
						'header' => 'Nama',
						'value' => '$data["pegawai_nama_lengkap"]."<br/>[ ".$data["dept_divisi_nama"]." ]"',
								'type'=>'html',	
						'htmlOptions' => array('style'=>'font-size:11px;color:#006EDD; white-space:nowrap;'),
					),
					// array(
						// 'header' => 'Sisa Cuti',
						// 'value' => function($data) {
							// $mp = Pegawai::model()->find('uid="'.$data['uid'].'"');
							// if( $mp->is_doj_full == 0 ){
								// return "<span style='color:#006EDD;font-size:11px;'>
								// ".$data["sisa_cuti"]." Hari</span>";
							// }else{
								// return "<span style='color:#006EDD;font-size:11px;'>
								// ".$data["sisa_cuti"]-$data["jumlah_cuti_bersama"]." Hari</span>";
							// }
						// },
						// 'type'=>'html',	
						// 'htmlOptions' => array('style'=>'width:62px;text-align:right;'),
					// ),
					array(
						'header' => '<div style="text-align:center">Tanggal</div>',
						'name'=>'tgl_mulai',
						'value'=>'date("d M",strtotime($data->tgl_mulai))." - ".date("d M",strtotime($data->tgl_akhir))."<br/>( ".$data["jumlah_hari"]." Hari )"',
						'type'=>'html',	
						'htmlOptions' => array('style'=>'font-size:11px; color:#006EDD; white-space:nowrap; text-align:center'),
					),
					array(
						'header' => 'Alasan',
						'name'=>'alasan',
						'value' => '$data["alasan"]',
						'htmlOptions' => array('style'=>'font-size:11px;word-break: break-all;color:#006EDD;'),
					),
					// array(
						// 'header' => 'STATUS',
						// 'value' => function($data) {
							// if( $data["status"] == 1 ){
								// return "<span style='font-size:11px;font-weight: bold;color:#6CFC6A';>APPROVED</span>";
							// }else if( $data["status_proses"]==1 ){
								// return "<span style='font-size:11px;font-weight: bold;color:#FF4F4F';>ON HRD</span>";
							// }
						// },
						// 'type'=>'html',	
						// 'htmlOptions' => array('style'=>'width:50px;'),
					// ),
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

		ob_start();
		$this->widget('zii.widgets.grid.CGridView', array(		
			'id'=>'izin-cuti-grid2',
			'dataProvider'=>$model->searchRequest(2),
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
										return "<span style='color:#F89406;font-size:11px;white-space:nowrap;';>
										".$data["kode"]."<br/>- ".$data->tipeIzin->nama." <font color='red'>**</font>
										</span>";
									}else{
										return "<span style='color:#F89406;font-size:11px;white-space:nowrap;';>
										".$data["kode"]."<br/>- ".$data->tipeIzin->nama."</span>";
									}
								},
						'type'=>'html',	
						'htmlOptions' => array('style'=>'color:#F89406;font-size:11px;white-space:nowrap;'),
					),
					array(
						'header' => 'Nama',
						'value' => '$data["pegawai_nama_lengkap"]."<br/>[ ".$data["dept_divisi_nama"]." ]"',
								'type'=>'html',	
						'htmlOptions' => array('style'=>'font-size:11px;color:#F89406; white-space:nowrap;'),
					),
					// array(
						// 'header' => 'Sisa Cuti',
						// 'value' => function($data) {
							// $mp = Pegawai::model()->find('uid="'.$data['uid'].'"');
							// if( $mp->is_doj_full == 0 ){
								// return "<span style='color:#F89406;font-size:11px;'>
								// ".$data["sisa_cuti"]." Hari</span>";
							// }else{
								// return "<span style='color:#F89406;font-size:11px;'>
								// ".$data["sisa_cuti"]-$data["jumlah_cuti_bersama"]." Hari</span>";
							// }
						// },
						// 'type'=>'html',
						// 'htmlOptions' => array('style'=>'width:62px;text-align:right;'),
					// ),
					array(
						'header' => '<div style="text-align:center">Tanggal</div>',
						'name'=>'tgl_mulai',
						'value'=>'date("d M",strtotime($data->tgl_mulai))." - ".date("d M",strtotime($data->tgl_akhir))."<br/>( ".$data["jumlah_hari"]." Hari )"',
						'type'=>'html',	
						'htmlOptions' => array('style'=>'font-size:11px; color:#F89406; white-space:nowrap; text-align:center'),
					),
					// array(
						// 'header' => 'Jumlah',
						// 'name'=>'jumlah_hari',
						// 'value' => '$data["jumlah_hari"]." Hari"',
						// 'htmlOptions' => array('style'=>'font-size:11px;color:#F89406;text-align:right;white-space:nowrap;'),
					// ),
					array(
						'header' => 'Alasan',
						'name'=>'alasan',
						'value' => '$data["alasan"]',
						'htmlOptions' => array('style'=>'font-size:11px;word-break: break-all;color:#F89406;'),
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
		$tab3Content=ob_get_contents();
		ob_end_clean();

		foreach(Yii::app()->user->getFlashes() as $key => $message) {
			echo '<div style="width:90%" class="alert alert-' . $key . '">'
			.'<button type="button" class="close" data-dismiss="alert">×</button>'
			. $message . "</div>\n"; 
		}
			
		$pending = '<font color="#999999"><b>Cuti Pending</b></font>
		<span class="badge badge-inverse pull-right">'.count($model->searchRequest(0)->getData()).'</span>';
		$approve = '<font color="#006EDD"><b>Cuti Disetujui</b></font>
		<span class="badge badge-info pull-right">'.count($model->searchRequest(1)->getData()).'</span>';
		$cancel = '<font color="#FF8300"><b>Cuti Dibatalkan</b></font
		><span class="badge badge-warning pull-right">'.count($model->searchRequest(2)->getData()).'</span>';
		$this->widget('zii.widgets.jui.CJuiTabs',array(
			'tabs'=>array(
				$pending => array('content' => $tab1Content,'id' => 'Pending'),
				$approve =>array('content'=> $tab2Content, 'id'=>'Disetujui'),
				$cancel =>array('content'=> $tab3Content, 'id'=>'Dibatalkan'),
			),
			'options'=>array(
				'collapsible'=>false,
			),
		));	
		END OLD */
	}
?>	
