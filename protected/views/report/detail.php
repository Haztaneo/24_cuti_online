<div class="form" style="padding:10px">
	<div class="row">
	  <div style="float:left; width:100%; ">	
		<div class="label label-info span12" style="padding:5px 20px;text-align:left;"><h4>No. Form : <?php echo $model->kode; ?></h4></div>
	  </div>
	</div>
	<br/>
	<table class="table table-striped table-hover table-bordered">
      <caption></caption>
      <thead>
        <tr>
          <th colspan="2"><font color="#3D5DFF">PERMOHONAN IZIN / CUTI : </font><?php echo $model->pegawai_nama_lengkap; ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="25%">Tanggal Pengajuan</td>
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
							echo "<span style='font-weight: bold;color:green';>APPROVED</span>";
							break;
						case 2:
							echo "<span style='font-weight: bold;color:RED';>CANCELED</span>";
							break; 
					} ?>
		  </td>
        </tr>
		<tr>
          <td>PROSES</td>
          <td>
			<?php	
					if( $model->status == 2 ){ //cancel
						echo "<span style='font-weight: bold;color:#F89406';>Canceled By ".$model->cancel_by."</span>";
					}else{					
						switch ($model->status_proses) {
							case 0:
								echo "<span style='font-weight: bold;color:green';>Waiting for Approval from Leader</span>";
								break;
							case 1:
								echo "<span style='font-weight: bold;color:green';>Waiting for Approval from HRD</span>";
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
</div>