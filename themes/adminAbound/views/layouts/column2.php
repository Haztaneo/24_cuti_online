<?php $this->beginContent('//layouts/main'); ?>
  <div class="row-fluid">
	<div class="span3">
		<div class="sidebar-nav">        
			<?php $level = 0;
					$userLevel = isset(Yii::app()->user->level)?Yii::app()->user->level:0;
					$userID = isset(Yii::app()->user->id)?Yii::app()->user->id:'';
					$atasan = Atasan::model()->find('uid = "'.$userID.'"' );
					if( $userLevel > 1 && count($atasan)>0 ){ 
						$level = 3; //leader + HR
					}elseif( $userLevel == 1 ){ 
						$level = 2; //leader
					}elseif( $userLevel > 1 && count($atasan)==0 ){ 
						$level = 1; //HR
					}				
			  
					$riwayat = Izin::model()->findAll(array('select'=>'*',
										'condition'=>'tahun='.date('Y').' and uid="'.$userID.'" and status=0 '));
					
					if( $level == 2 ){ 
						$pengajuan = Izin::model()->findAllBySql("SELECT i.kode FROM izin i
											INNER JOIN pegawai p ON p.id=i.pegawai_id
											WHERE p.uid_atasan='".$userID."' 
												  and status_proses=0 and status=0 ");
					}elseif( $level == 1 || $level == 3 ){ 
						$pengajuan = Izin::model()->findAllBySql("SELECT i.kode FROM izin i
											INNER JOIN pegawai p ON p.id=i.pegawai_id
											WHERE status_proses in(0,1) and status=0 ");
					}				
					
					/*if ( count($riwayat)>0 )
						$totalIzin = '<span class="badge badge-success pull-right">'.count($riwayat).'</span>';
					else */
					
					$totalIzin = '';				
					if ( isset($pengajuan) && count($pengajuan)>0 ){
						$totalPengajuan = '<span class="badge badge-important pull-right">'.count($pengajuan).'</span>';
					}else{ 
						$totalPengajuan = '';
					}
					
					if(	(Yii::app()->user->show_report == 2) || (Yii::app()->user->show_report == 1 && Yii::app()->user->akses_report == 2 ) ) {
						$aksesPengajuan = 1;  
					}else{
						$aksesPengajuan = 0;  
					}
					
					if( $level == 0 ){	//Associate
						$itemColumn = array(
									array('label'=>'<i class="icon icon-home"></i>  Pengajuan Baru', 'url'=>array('/izin/create'),'itemOptions'=>array('class'=>'')),
									array('label'=>'<i class="icon icon-search"></i> Riwayat Pengajuan Anda '.$totalIzin, 'url'=>array('/izin/admin')),
								);
					}else{	//Leader & Admin
						$itemColumn = array(
									array('label'=>'<i class="icon icon-edit"></i>  Pengajuan Baru', 'url'=>array('/izin/create'),'itemOptions'=>array('class'=>'')),
									array('label'=>'<i class="icon icon-search"></i> Riwayat Pengajuan Anda '.$totalIzin, 'url'=>array('/izin/admin')),
									array('label'=>'<i class="icon icon-list-alt"></i> Status Pengajuan Associate '.$totalPengajuan, 'url'=>array('/izin/request'), 'visible'=>$aksesPengajuan==1),		
								);
					}
					
				$this->widget('zii.widgets.CMenu', array(
						/*'type'=>'list',*/
						'encodeLabel'=>false,
						'items'=>$itemColumn,
				));	
			?>
		</div>
        <br>	
		
		<?php	
			$sCuti = Pegawai::model()->find(' uid = "'.$userID.'"'); 	
			$potong_cuti_bersama = isset($sCuti->lokasi->potong_cuti_bersama)?$sCuti->lokasi->potong_cuti_bersama:0;
			if(count($sCuti)>0){
				if( $sCuti->is_doj_full==1 ){
					$sumCuti = $sCuti->sisa_cuti - $potong_cuti_bersama;
				}elseif( $sCuti->is_doj_full==0 && ($sCuti->sisa_cuti > $potong_cuti_bersama) ){
					$sumCuti = $sCuti->sisa_cuti - $potong_cuti_bersama;
				}else{
					$sumCuti = $sCuti->sisa_cuti;
				}
			}else{ 
				$sumCuti = 0;
			}					
		?>			
		<div class="well" style="padding:10px 20px 5px 20px">
			<dl class="dl-vertical alert alert-info">
				<dt>Sisa Cuti <?php echo date('Y'); ?></dt>
				<dd><span class="badge badge-important" style="margin:0px 5px"><?php echo $sumCuti;?> Hari</span></dd>
				<dt>Cuti Bersama Tahun <?php echo date('Y'); ?></dt>
				<dd><span class="badge badge-important" style="margin:0px 5px"><?php echo $potong_cuti_bersama; ?> Hari</span></dd>
			</dl>
			<?php  $cutiList = Yii::app()->db->createCommand("
						SELECT ti.nama,COALESCE(total,0)AS total 
						FROM tipe_izin ti
						LEFT JOIN (SELECT tipe_izin_id,SUM(jumlah_hari)AS total 
								   FROM izin WHERE status=1 								   
								   and uid='".$userID."' and tahun=year(now())  GROUP BY 1)AS i ON ti.id=i.tipe_izin_id  
						WHERE STATUS=1 order by 2 desc,id ")->queryAll();
						
				if( $cutiList[0]['total'] >0 )
					echo '<dt><h5>Riwayat pengambilan izin & cuti : </h5></dt>';
				
				foreach($cutiList as $dataCuti){
					if($dataCuti['total']>0)
					echo '<dt>- '.$dataCuti['nama'].'</dt>
						  <dd style="margin-left:20px"><span class="label label-success" style="margin:0px 5px">'.$dataCuti['total'].' Hari</span></dd>';
				}	
			?>
		</div>
    </div><!--/span-->    
	<div class="span9">    		
		<!-- Include content pages -->
		<?php echo $content; ?>
	</div><!--/span-->	
</div><!--/row-->
<?php $this->endContent(); ?>