<style>
.num { mso-number-format:General; }
.text{ mso-number-format:"\@";  /*force text*/ }
</style>

<?php 
if(isset($divisi) && $flag == 2){
	$monthName = date('F', mktime(0, 0, 0, $bulan, 1));//divisi
	$nameFile = 'ListCuti-'.$divisi.'-'.$monthName.'-'.$tahun.'_'.date('d-m-Y');	 
	$header = '<h4>DAFTAR CUTI DIVISI : '.$divisi.'<br/>Periode : '.$monthName.' '.$tahun.'</h4>';
}elseif(isset($uid) && $flag == 1){
	$monthName = date('F', mktime(0, 0, 0, $bulan, 1));//pegawai
	$nameFile = 'ListCuti-'.str_replace (" ", "", $uid).'-'.$monthName.'-'.$tahun.'_'.date('d-m-Y');	 
	$header = '<h4>DAFTAR CUTI ASSOCIATE : '.$uid.'<br/>Periode : '.$monthName.' '.$tahun.'</h4>';
}elseif($flag == 3){ 
	$monthName = date('F', mktime(0, 0, 0, $bulan, 1));//pegawaiall
	$nameFile = 'ListCuti-'.$monthName.'-'.$tahun.'_'.date('d-m-Y');	 
	$header = '<h4>DAFTAR CUTI ASSOCIATE<br/>Periode : '.$monthName.' '.$tahun.'</h4>';
}else{ 
	$monthName = date('F', mktime(0, 0, 0, $bulan, 1));//divisiall
	$nameFile = 'ListCutiAll-'.$monthName.'-'.$tahun.'_'.date('d-m-Y');
	$header = '<h4>DAFTAR CUTI SEMUA ASSOCIATE PERIODE : '.$monthName.' '.$tahun.'</h4>';
}

$this->layout = 'shadow';
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$nameFile.xls");
header("Pragma: no-cache");
header("Expires: 0");

$styleX = "style='text-align:center; background-color:#2EB6EC';white-space: nowrap";
$x = 1;
echo $header.'
<table border="1" width="100%">
	<thead>	
		<tr>
			<th '.$styleX.'>NO</th>
			<th '.$styleX.'>NO. FORM</th>
			<th '.$styleX.'>Nama</th>
			<th '.$styleX.'>Divisi</th>
			<th '.$styleX.'>Tanggal Mulai Cuti</th>
			<th '.$styleX.'>Tanggal Akhir Cuti</th>
			<th '.$styleX.'>Tipe Izin</th>
			<th '.$styleX.'>Jumlah Hari Cuti</th>
			<th '.$styleX.'>Alasan</th>
			<th '.$styleX.'>Status</th>
		</tr>
	</thead>
	<tbody>';	
		foreach($model as $fx){
			$status = "";
			if( $fx["status"]==1 && $fx["status_proses"]==2 ){
				$status = "<font color='#317233'>DISETUJUI</font>";
			}elseif( $fx["status"]==0 && $fx["status_proses"]==0 ){
				$status = "<font color='#757575'>PENDING ON LEADER</font>";
			}elseif( $fx["status"]==0 && $fx["status_proses"]==1 ){
				$status = "<font color='#FF7200'>PENDING ON HRD</font>";
			}
		echo '<tr>
				<td>'.$x.'</td>	
				<td style="white-space: nowrap; color:#0000FF;">'.$fx["kode"].'</td>	
				<td class="text" style="white-space: nowrap;">'.$fx["pegawai_nama_lengkap"].'</td>
				<td class="text" style="white-space: nowrap;">'.$fx["dept_divisi_nama"].'</td>
				<td>'.$fx["tgl_mulai"].'</td>
				<td>'.$fx["tgl_akhir"].'</td>
				<td class="num">'.$fx["tipe_izin"].'</td>
				<td class="num">'.$fx["jumlah_hari"].'</td>
				<td class="text">'.$fx["alasan"].'</td>
				<td class="text">'.$status.'</td>
			  </tr>';
		$x++;
		}
	echo '</tbody>
</table>
';
?>