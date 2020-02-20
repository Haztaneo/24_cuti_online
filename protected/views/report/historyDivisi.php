<div class="row-fluid">
    <div class="span12">
<?php	$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'<span class="icon-th-list"></span> Laporan Sisa Cuti Associate',
			'titleCssClass'=>'' ));	

		$form = $this->beginWidget('booster.widgets.TbActiveForm',
						array(
							'id' => 'verticalForm',
							'type' => 'horizontal',
						)
				);
?>
	
<table cellpadding="5" border="0px" style="width:100%;margin-bottom:-15px">
	<tr>
		<td style="width:15%"><b>Divisi</b></td>
		<td style="width:20%">
			<?php	
					if( Yii::app()->user->level == 2 || Yii::app()->user->level == 3 ){	
						if( Yii::app()->user->akses_report == 2 ){	//All Division
							echo CHtml::dropDownList('history[divisi]', $divisi, CHtml::listData(DeptDivisi::model()->findAllBySql("select id,nama from dept_divisi where status=1 "),'nama','nama'),array('empty' => '-- Pilih Divisi --', 'class'=>'span12' )); 
							echo CHtml::hiddenField('history[level]', 2, array('readonly'=>true));							
						}else{	//1=Private Division, 0=Private
							$modelPegawai = Pegawai::model()->find('uid="'.Yii::app()->user->id.'"');
							echo CHtml::textField('history[divisi]', $modelPegawai->deptDivisi->nama, array('class'=>'span12','readonly'=>true));
							echo CHtml::hiddenField('history[level]', 2, array('readonly'=>true));
						}						
					}elseif( Yii::app()->user->level == 1 || Yii::app()->user->level == 0 ){
						$modelPegawai = Pegawai::model()->find('uid="'.Yii::app()->user->id.'"');
						echo CHtml::textField('history[divisi]', $modelPegawai->deptDivisi->nama, array('class'=>'span12','readonly'=>true));
						echo CHtml::hiddenField('history[level]', 1, array('readonly'=>true));
					}
			?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td><b>Tahun</b></td>
		<td><?php echo CHtml::dropDownList('history[tahun]', $tahun, 
              CHtml::listData(Izin::model()->findAllBySql("select distinct year(tgl_pengajuan)as tahun from izin where year(tgl_pengajuan)< year(now())"),'tahun','tahun'),array('empty' => date('Y'), 'class'=>'span12' )); ?>
		</td>
		<td><?php $this->widget('booster.widgets.TbButton',array(
		'buttonType' => 'submit', 'label' => 'Tampilkan', 'context' => 'primary', 
		'htmlOptions'=>array('name'=>'tbSubmit', 'style'=>'height:30px;vertical-align: top;',),
));?></td>
	</tr>
	</tr>
</table> 
<?php 
$this->endWidget();
unset($form);
$this->endWidget(); 
?>
    </div>
</div>

<?php if( isset($_POST['tbSubmit']) && empty($_POST['history']['divisi']) ){?>
<div class="alert alert-danger span12" style="margin:2px 0px 25px 0px;text-align:left; font-weight: bold;">Nama Divisi belum dipilih !!</div>
<?php }elseif( isset($_POST['tbSubmit']) && !empty($_POST['history']['divisi']) && count($data)==0){ ?>
<div class="alert alert-warning span12" style="margin:2px 0px 25px 0px;text-align:left; font-weight: bold;">Data tidak ditemukan !!</div>
<?php }elseif( isset($_POST['tbSubmit']) && !empty($_POST['history']['divisi']) && count($data)>0){ ?>
<table class="table table-striped table-hover table-bordered table-condensed">
<thead>
<tr>
	<th style="text-align:center;width:5%">No</th>
	<th>Nama Associate</th>
	<th style="text-align:center;white-space: nowrap;width:15%">Cuti yang diambil</th>
	<th style="text-align:center;white-space: nowrap;width:10%">Sisa Cuti</th>
</tr>
</thead>	  
<tbody>
<?php $z = 0;
	  foreach($data as $data){
		$z += 1;
		echo '<tr>
				<td style="text-align:center">'.$z.'</td>
				<td style="white-space: nowrap">'.$data["nama_lengkap"].'</td>
				<td style="text-align:right">';
		$this->widget('booster.widgets.TbButton',
				array(
					'label' => $data["cuti_diambil"].' Hari',
					'context' => 'link',
					'buttonType' =>'link',
					'url'=>Yii::app()->controller->createUrl("dialog",array("id"=>$data["id"],"y"=>$tahun)),
					'htmlOptions'=>array(
							'title' => 'Detail cuti yang diambil',
							// 'data-toggle' => 'modal',
							// 'class'=>'btn btn-null btn-mini', 
							// 'style'=>'font-weight: bold;color: #00BCBF;',
							'ajax'=>array(								
								'type'=>'POST',
								'url'=>"js:$(this).attr('href')",
								'success'=>'function(data) { $("#myModal .modal-body p").html(data); $("#myModal").modal(); }'
							),
						),
					)
				);
		echo   '</td>
				<td style="text-align:right">'.$data["sisa_cuti"].' Hari&nbsp;&nbsp;&nbsp;</td>
			  </tr>
			  ';
	  }
?>
</tbody>
</table>
<?php } ?>

    <?php $this->beginWidget('booster.widgets.TbModal', 
				array('id' => 'myModal',
					  'htmlOptions' => ['style' => 'width: 900px; margin-left: -400px'])
		  ); ?>
	<!--<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4>History penggunaan cuti</h4>
	</div> -->
	<div class="modal-body">
		<p></p>
	</div> 
	<div class="modal-footer">
		<?php $this->widget('booster.widgets.TbButton',
					array(
						'context' => 'primary',
						'label' => 'Close',
						'url' => '#',
						'htmlOptions' => array('data-dismiss' => 'modal'),
					)
		); ?>
	</div> 
<?php $this->endWidget(); ?>