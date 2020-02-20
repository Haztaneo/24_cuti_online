<h4 style="color:#319ED7">Nama associate : <font color="#777777"><?php echo $model->nama_lengkap; ?></font></h4>
<table class="table table-striped table-hover table-bordered table-condensed">
<thead>
<tr>
	<th style="text-align:center">No</th>
	<th style="text-align:center">No. Form</th>
	<th style="text-align:center;white-space: nowrap">TGL Pengajuan </th>
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
				<td style="text-align:center">'.date("d M Y",strtotime($data["tgl_pengajuan"])).'</td>
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