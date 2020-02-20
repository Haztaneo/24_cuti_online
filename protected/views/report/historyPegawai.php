<div class="row-fluid">
	<div class="span12">
<?php $this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'<span class="icon-th-list"></span> Laporan Riwayat Penggunaan Cuti Associate', 'titleCssClass'=>'' ));	

	$form = $this->beginWidget('booster.widgets.TbActiveForm',
					array(
						'id' => 'verticalForm',
						'type' => 'horizontal',
					)
			);
?>
	
<table cellpadding="5" border="0px" style="width:100%;margin-bottom:-15px">
	<tr>
		<td><b>Nama Associate</b></td>
		<td>
		<?php						
					if( Yii::app()->user->level == 1 ){
						$this->widget('ext.comboboxReport.EJuiComboBox', array(
							'name'=>'history[uid]',
							'value'=>$uid,
							'data' => UserLdap::model()->getOptionsReportLeader(),
							'assoc'=>true,
							'options' => array(
								'allowText' => false,
							),
							'htmlOptions' => array('placeholder' => 'Nama Associate', 'class'=>'span10' ),
						)); 	
					}elseif( Yii::app()->user->level == 2 || Yii::app()->user->level == 3 ){	
						if( Yii::app()->user->akses_report == 0 ){	//private
							$modelPegawai = Pegawai::model()->find('uid="'.Yii::app()->user->id.'"');
							echo CHtml::textField('nama', $modelPegawai->nama_lengkap, array('class'=>'span11','disabled'=>'disabled'));
							echo CHtml::hiddenField('history[uid]', Yii::app()->user->id, array('readonly'=>true));
						}else{		//2=All Division,1=Private Division
							$this->widget('ext.comboboxReport.EJuiComboBox', array(
								'name'=>'history[uid]',
								'value'=>$uid,
								'data' => UserLdap::model()->getOptionsReport(Yii::app()->user->akses_report),
								'assoc'=>true,
								'options' => array(
									'allowText' => false,
								),
								'htmlOptions' => array('placeholder' => 'Nama Associate', 'class'=>'span10' ),
							)); 
						}						
					}elseif( Yii::app()->user->level == 0 ){	
						$modelPegawai = Pegawai::model()->find('uid="'.Yii::app()->user->id.'"');
						echo CHtml::textField('nama', $modelPegawai->nama_lengkap, array('class'=>'span11','disabled'=>'disabled'));
						echo CHtml::hiddenField('history[uid]', Yii::app()->user->id, array('readonly'=>true));
					}
			?>
		</td>
		<td><b>Status</b></td>
		<td><?php echo CHtml::dropDownList('history[status]', $status, 
              array('0' => 'BARU', '1' => 'DISETUJUI', '2' => 'DIBATALKAN'),array('empty' => '-- Semua --')); ?>
		</td>
	</tr>
	<tr>
		<td><b>Tahun</b></td>
		<td><?php echo CHtml::dropDownList('history[tahun]', $tahun, 
              CHtml::listData(Izin::model()->findAllBySql("select distinct year(tgl_pengajuan)as tahun from izin where year(tgl_pengajuan)< year(now())"),'tahun','tahun'),array('empty' => date('Y'), 'class'=>'span4' )); ?>
		</td>
		<td><b>Tipe Izin</b></td>
		<td><?php echo CHtml::dropDownList('history[tipe]', $tipe, CHtml::listData(TipeIzin::model()->findAllBySql("select * from tipe_izin where status=1"),'id','nama'), array('empty' => '-- Semua --')); ?>
		</td>
	</tr>
	<tr>
		<td colspan="4"><?php $this->widget('booster.widgets.TbButton',array(
		'buttonType' => 'submit', 'label' => 'Tampilkan', 'context' => 'primary', 
		'htmlOptions'=>array('name'=>'tbSubmit', 'style'=>'height:30px;vertical-align: top;',),
));?></td>
	</tr>
</table> 
<?php 
	$this->endWidget();
	unset($form);
$this->endWidget(); 
?>
    </div>
</div>

<?php if( isset($_POST['tbSubmit']) && empty($_POST['history']['uid']) ){?>
<div class="alert alert-danger span12" style="margin:2px 0px 25px 0px;text-align:left; font-weight: bold;">Nama associate belum dipilih !!</div>
<?php }elseif( isset($_POST['tbSubmit']) && !empty($_POST['history']['uid']) && count($data)==0){ ?>
<div class="alert alert-warning span12" style="margin:2px 0px 25px 0px;text-align:left; font-weight: bold;">Data tidak ditemukan !!</div>
<?php }elseif( isset($_POST['tbSubmit']) && !empty($_POST['history']['uid']) && count($data)>0){ ?>
<table class="table table-striped table-hover table-bordered table-condensed">
<thead>
<tr>
	<th style="text-align:center">No</th>
	<th style="text-align:center">No. Form</th>
	<th style="text-align:center;white-space: nowrap">Nama</th>
	<th style="text-align:center;white-space: nowrap">Tanggal Cuti </th>
	<th style="text-align:center;white-space: nowrap">Jumlah Cuti</th>
	<th style="text-align:center">Alasan</th>
	<th style="text-align:center">Tipe Izin</th>
	<th style="text-align:center">Status</th>
</tr>
</thead>	  
<tbody>
<?php $z = 0;
	  foreach($data as $data){
		$z += 1;
		if($data["status"]== 0){
			$statusC = "<font color='#CCCCCC'><b>BARU</b></font>";
		}elseif($data["status"]== 1){
			$statusC = "<font color='#6B86FF'><b>DISETUJUI</b></font>";
		}elseif($data["status"]== 2){
			$statusC = "<font color='#FF7F7F'><b>DIBATALKAN</b></font>";
		}		
		
		echo '<tr>
				<td style="text-align:center">'.$z.'</td>
				<td style="white-space: nowrap">'.$data["kode"].'</td>				
				<td style="white-space: nowrap">'.$data["nama_panggilan"].'<br/>[ '.$data["dept_divisi_nama"].' ]</td>				
				<td style="text-align:center">'.date("d M",strtotime($data["tgl_mulai"])).' - '.date("d M",strtotime($data["tgl_akhir"])).'<br/></td>
				<td style="text-align:right">'.$data["jumlah_hari"].' Hari&nbsp;&nbsp;&nbsp;</td>
				<td>'.$data["alasan"].'</td>
				<td style="white-space: nowrap">'.$data["nama"].'</td>
				<td style="text-align:left;white-space: nowrap">'.$statusC.'</td>
			  </tr>
			  ';
	  }
?>
</tbody>
</table>
<?php } ?>