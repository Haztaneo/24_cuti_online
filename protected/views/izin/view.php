<?php $this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"~ DETAIL - IZIN / CUTI ~",
	  )); ?>
<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'izin-cuti-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">
	  <div style="float:left; width:60%; margin-top:-20px;">	
		<h5>No. Form</h5>
		<div class="label label-info span12" style="padding:5px;text-align:center;"><h4><?php echo $model->kode; ?></h4></div>
	  </div>
	</div>
	<hr>
	<table class="table table-striped table-hover table-bordered">
      <caption></caption>
      <thead>
        <tr>
          <th colspan="2"><font color="#3D5DFF">PERMOHONAN IZIN / CUTI</font></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="25%">Nama</td>
          <td><b><?php echo strtoupper($model->pegawai_nama_lengkap); ?></b></td>
        </tr>
		<tr>
          <td>Tanggal Pengajuan</td>
          <td><?php echo date_format(date_create($model->tgl_pengajuan),"d F Y"); ?></td>
        </tr>
        <tr>
          <td>Jenis permohonan</td>
          <td><?php echo $model->tipeIzin->nama; ?></td>
        </tr> 
		<tr>
          <td>Tanggal</td>
          <td><?php echo date("d F Y",strtotime($model->tgl_mulai))."<b> s/d </b>".date("d F Y",strtotime($model->tgl_akhir)); ?></td>
        </tr>
		<tr>
          <td>Jumlah</td>
          <td><?php echo $model->jumlah_hari." Hari"; ?></td>
        </tr>
		<tr>
          <td>Alasan</td>
          <td><?php echo $model->alasan; ?></td>
        </tr>
		<tr>
          <td>Status</td>
          <td>
			<?php	switch ($model->status) {
						case 0:
							echo "<span style='font-weight: bold;color:silver';>PENDING</span>";
							break;
						case 1:
							echo "<span style='font-weight: bold;color:green';>DISETUJUI</span>";
							break;
						case 2:
							echo "<span style='font-weight: bold;color:RED';>DIBATALKAN</span>";
							break; 
					} ?>
		  </td>
        </tr>
		<tr>
          <td>PROSES</td>
          <td>
			<?php	
					if( $model->status == 2 ){ //cancel
						echo "<span style='font-weight: bold;color:#F89406';>Dibatalkan Oleh ".$model->cancel_by."</span>";
					}else{					
						switch ($model->status_proses) {
							case 0:
								// echo "<span style='font-weight: bold;color:green';>Waiting for Approval from Leader</span>";
								echo "<span style='font-weight: bold;color:green';>Menunggu persetujuan dari Atasan</span>";
								break;
							case 1:
								echo "<span style='font-weight: bold;color:green';>Menunggu persetujuan dari HRD</span>";
								break;
							case 2:
								echo "<span style='font-weight: bold;color:blue';>DONE</span>";
								break;
						} 
					}
			?>
		  </td>
        </tr>
      </tbody>
    </table>
	
	 <div class="accordion" id="accordion2">
      <div class="accordion-group">
        <div class="accordion-heading">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
             # <b>LAMPIRAN</b> #
          </a>
        </div>
        <div id="collapseOne" class="accordion-body collapse">
          <div class="accordion-inner">
				<?php 
					$path = Yii::app()->request->baseUrl.'/uploads/dokumen/';	
					if($model->attach_dokumen) :
						$img = $path.$model->attach_dokumen;						
						if ( file_exists(YiiBase::getPathOfAlias('webroot').'/uploads/dokumen/'.$model->attach_dokumen) ): 
						else:
							$img = $path.'noImage.jpg';
						endif;
					else :            
						$img = $path."noImage.jpg";
					endif;
				?>
				<img src="<?php echo $img; ?>" class="img-responsive" style="max-width:100%"/>
          </div>
        </div>
      </div>
    </div>
    
	<?php if($model->disetujui_nama<>null){?>
  	<table class="table table-striped table-hover table-bordered">
      <caption></caption>
      <thead>
        <tr>
          <th colspan="2"><font color="#008032">DISETUJUI</font></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="25%">Keterangan</td>
          <td><?php echo $model->approval_note; ?></td>
        </tr>
        <tr>
          <td>Oleh</td>
          <td><?php echo $model->disetujui_nama; ?></td>
        </tr>
        <tr>
          <td>Tanggal</td>
          <td><?php echo isset($model->disetujui_tgl)?date_format(date_create($model->disetujui_tgl),"d F Y"):''; ?></td>
        </tr>
      </tbody>
    </table>
	<?php } ?>
	
	<?php if($model->diketahui_nama<>null){?>	
  	<table class="table table-striped table-hover table-bordered">
      <caption></caption>
      <thead>
        <tr>
          <th colspan="2"><font color="#FF8800">DIKETAHUI</font></th>
        </tr>
      </thead>	
      <tbody>
        <tr>
          <td width="25%">Oleh</td>
          <td><?php echo $model->diketahui_nama; ?></td>
        </tr>
        <tr>
          <td>Tanggal</td>
          <td><?php echo isset($model->diketahui_tgl)?date_format(date_create($model->diketahui_tgl),"d F Y"):''; ?></td>
        </tr>
      </tbody>
    </table>
	<?php } ?>
	
	<?php if( $model->status == 2 ){?>
  	<table class="table table-striped table-hover table-bordered">
      <caption></caption>
      <thead>
        <tr>
          <th colspan="2"><font color="#B94A48">DIBATALKAN</font></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="25%">Alasan</td>
          <td><?php echo $model->cancel_note; ?></td>
        </tr>
        <tr>
          <td>Oleh</td>
          <td><?php echo $model->cancel_by; ?></td>
        </tr>
        <tr>
          <td>Tanggal</td>
          <td><?php echo date_format(date_create($model->cancel_date),"d F Y"); ?></td>
        </tr>
      </tbody>
    </table>
	<?php } ?>
	
<input type="hidden" name="flag" id="flag" value="<?php echo isset($_POST['flag'])?$_POST['flag']:0;?>">
<table style="width:100%">
<tr>
	<td>
<?php if( ($level == 2 || $level == 3) && $model->status<>2 && $model->status_proses==0){ 
			$this->widget('zii.widgets.jui.CJuiButton',array(
				'buttonType'=>'button',
				'name'=>'btnApprove',
				'caption'=>'SETUJU',
				'htmlOptions'=>array('class'=>'btn btn-info'),
				'onclick'=>new CJavaScriptExpression('function(){
						$( "#formAppprove" ).show(500);
						$( "#formCancel" ).hide();
						$( "#Izin_cancel_note" ).val("");
						$( "#Izin_approval_note" ).focus();
						$("html, body").animate({scrollTop: $("#Izin_approval_note").offset().top}, 1000);
						$( "#flag" ).val(1);
				}'),
			));	
	  } 

	  if( $level == 1 && $model->status<>2 && $model->status_proses==1 ){ 
		  $this->widget('zii.widgets.jui.CJuiButton',array(
				'buttonType'=>'submit',
				'name'=>'btnKnow',
				'caption'=>'PROSES',
				'htmlOptions'=>array('class'=>'btn btn-success','style'=>'margin-top:8px;padding:9px 25px'),
				'onclick'=>new CJavaScriptExpression('function(){ return confirm("Anda menyetujui permohonan izin /cuti ini ?"); }'),
		  ));	
	  }
	
	  //leader & HRD akses tombol batal unlimited
	  if($level==1 || $level==2 || $level==3){
			$this->widget('zii.widgets.jui.CJuiButton',array(
					'buttonType'=>'button',
					'name'=>'btnCancel',
					'caption'=>'BATAL',
					'htmlOptions'=>array('class'=>'btn btn-warning'),
					'onclick'=>new CJavaScriptExpression('function(){
							$( "#formAppprove" ).hide();
							$( "#Izin_approval_note" ).val("");
							$( "#formCancel" ).show(500);
							$( "#Izin_cancel_note" ).focus();
							$("html, body").animate({
									scrollTop: $("#Izin_cancel_note").offset().top}, 1000);
							$( "#flag" ).val(2);
					}'),
			));
	  }else{
			if ( (($model->status==0 || $model->status==1) && $model->status_proses==0) ||
				 (($model->status==0 || $model->status==1) && $model->status_proses<>0 && (date('Y-m-d') < $model->tgl_mulai)) ){ 
				$this->widget('zii.widgets.jui.CJuiButton',array(
					'buttonType'=>'button',
					'name'=>'btnCancel',
					'caption'=>'BATAL',
					'htmlOptions'=>array('class'=>'btn btn-warning'),
					'onclick'=>new CJavaScriptExpression('function(){
							$( "#formAppprove" ).hide();
							$( "#Izin_approval_note" ).val("");
							$( "#formCancel" ).show(500);
							$( "#Izin_cancel_note" ).focus();
							$("html, body").animate({
									scrollTop: $("#Izin_cancel_note").offset().top}, 1000);
							$( "#flag" ).val(2);
					}'),
				));
			}
	  } 
?>
	</td>
	<td style="text-align:right">
		<?php  $this->widget('zii.widgets.jui.CJuiButton', array(
						'name'=>'btBack',
						'caption'=>'KEMBALI',
						'buttonType'=>'link',
						'url'=>Yii::app()->user->level==0?Yii::app()->controller->createUrl("admin"):Yii::app()->controller->createUrl("request"),
						'htmlOptions'=>array('class'=>'btn btn-inverse'),
				));	
		?>
	</td>
</tr>
</table>

<hr/>

<div class="alert alert-info" id="formAppprove" style="display:none">
	<h5>Keterangan <span class="required">*</span> </h5>
	<?php echo $form->textArea($model,'approval_note',array('rows'=>4,'cols'=>50,'class'=>'span12')); ?>
	<?php echo $form->error($model,'approval_note'); ?>	
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
			 'buttonType' => 'submit',
			 'name'=>'submitApprove',
			 'caption'=>'PROSES',
			 // 'onclick'=>new CJavaScriptExpression('function(){ return confirm("Lanjutkan proses ?"); }'),
			 'htmlOptions'=>array('class'=>'btn btn-info','style'=>'padding:9px 25px'),
			));	?>	
</div>

<div class="alert" id="formCancel" style="display:none">
	<h5>Alasan Pembatalan <span class="required">*</span> </h5>
	<?php echo $form->textArea($model,'cancel_note',array('rows'=>4,'cols'=>50,'class'=>'span12')); ?>
	<?php echo $form->error($model,'cancel_note'); ?>	
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
			 'buttonType' => 'submit',
			 'name'=>'submitBatal',
			 'caption'=>'PROSES',
			 // 'onclick'=>new CJavaScriptExpression('function(){ return confirm("Anda yakin akan membatalkan?"); }'),
			 'htmlOptions'=>array('class'=>'btn btn-warning','style'=>'padding:9px 25px'),
			));	?>	
</div>	

<?php $this->endWidget(); ?>
</div>
<?php $this->endWidget();?>
<?php		
	Yii::app()->clientScript->registerScript('notif','
		$(document).ready(function(){
			var flag = $( "#flag" ).val();			
			if( flag == 1 ){
				$( "#formAppprove" ).show(500);
				$( "#Izin_cancel_note" ).val("");
				$( "#Izin_approval_note" ).focus();
				$("html, body").animate({scrollTop: $("#Izin_approval_note").offset().top}, 1000);
			}else if( flag == 2){
				$( "#Izin_approval_note" ).val("");
				$( "#formCancel" ).show(500);
				$( "#Izin_cancel_note" ).focus();
				$("html, body").animate({
						scrollTop: $("#Izin_cancel_note").offset().top}, 1000);
			}
		});
	');
?>