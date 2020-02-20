<html>
<head>
	<title>[K24 CUTI ONLINE] - INFORMASI PENGAJUAN IZIN & CUTI</title>
	<style>
	body{ font-family:  Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; margin: 0; padding: 0; background-color:#f6f6f6;}
	p{ margin: 0 0 10px; padding: 0; }
	.td1{ background-color:#F5F5F5;padding:8px; }
	.td2{ padding:8px; }
	#top-left{
		-moz-border-radius: 10px 0 0 0;
		-webkit-border-radius: 10px 0 0 0;
		border-radius: 10px 0 0 0;  		
	}                        
	#bottom-right{
		-moz-border-radius: 0 0 10px 0;
		-webkit-border-radius: 0 0 10px 0;
		border-radius: 0 0 10px 0;                
	}     
	#bottom-left{
		-moz-border-radius: 0 0 0 10px;
		-webkit-border-radius: 0 0 0 10px;
		border-radius: 0 0 0 10px;
	}
	#top-right{
		-moz-border-radius: 0 10px 0 0;
		-webkit-border-radius: 0 10px 0 0;
		border-radius: 0 10px 0 0;
	}
	</style>
</head>
		
<body>
<table style="font-size: 100%; width: 100%; margin: 0; padding: 20px;">
<tr style="font-size: 100%; margin: 0; padding: 0;">
	<td style="margin: 0; padding: 0;"></td>
	<td bgcolor="#FFFFFF" style="font-size: 100%; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 20px; border: 1px solid #f0f0f0;">
		<div style=" max-width: 600px; display: block; margin: 0 auto; padding: 0;">
			<table style="font-size: 100%; width: 100%; margin: 0; padding: 0;">
			<tr style="ffont-size: 100%; margin: 0; padding: 0;">
				<td style="font-size: 100%; margin: 0; padding: 0;">
<!--Start Content-->
	<p>Yth, <b><?php echo $kepada;?></b></p>
	<p>
		<div style="border:1px solid #FFA238; background-color:#FFF3E5; padding:10px; color:blue">
		<?php echo $pesan;?>
		</div>
	</p>
	<br/>	
	<h3><font color="#5B5B5B">Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;</font><font color="#AAAAAA"><?php echo $dataIzin->pegawai_nama_lengkap;?></font>
	<hr/><font color="#5B5B5B">No. Form :&nbsp;&nbsp;</font><font color="#AAAAAA"><?php echo $dataIzin->kode;?></font>
	</h3>

	<table style="border-spacing: 0;font-size: 100%;width: 100%; table-layout: fixed;">        
	<tr>
		<td id="top-left" class="td1" style="width:30%"><b>Tanggal Pengajuan</b></td>
		<td id="top-right" class="td1"><?php echo (string) date("d F Y",strtotime($dataIzin->tgl_pengajuan));?></td>
	</tr>
	<tr>
		<td class="td2" style="border-left:1px solid #F5F5F5;"><b>Jenis permohonan</b></td>
		<td class="td2" style="border-right:1px solid #F5F5F5;"><?php echo $dataIzin->tipeIzin->nama;?></td>
	</tr>
	<tr>
		<td class="td2" style="border-left:1px solid #F5F5F5;"><b>Tanggal</b></td>
		<td class="td2" style="border-right:1px solid #F5F5F5;"><?php echo (string) date("d F Y",strtotime($dataIzin->tgl_mulai))."<b> s/d </b>".(string) date("d F Y",strtotime($dataIzin->tgl_akhir));?></td>
	</tr>
	<tr>
		<td class="td2" style="border-left:1px solid #F5F5F5;"><b>Jumlah Izin / Cuti</b></td>
		<td class="td2" style="border-right:1px solid #F5F5F5;"><?php echo $dataIzin->jumlah_hari." Hari";?></td>
	</tr>
	<tr>
		<td id="bottom-left"  class="td2" style="border-left:1px solid #F5F5F5;border-bottom:1px solid #F5F5F5;vertical-align: text-top;"><b>Alasan</b></td>
		<td id="bottom-right"  class="td2" style="border-right:1px solid #F5F5F5;border-bottom:1px solid #F5F5F5;word-wrap:break-word"><?php echo $dataIzin->alasan;?></td>
	</tr>
	</table>
	<br/>
	<?php if($dataIzin->disetujui_nama != null){ ?>
	<table style="border-spacing: 0;font-size: 100%;width: 100%; table-layout: fixed;">        
	<tr>
		<td id="top-left" class="td1" style="width:30%"><b>Disetujui Oleh</b></td>
		<td id="top-right" class="td1"><?php echo $dataIzin->disetujui_nama;?></td>
	</tr>
	<tr>
		<td class="td2" style="border-left:1px solid #F5F5F5;vertical-align: text-top;"><b>Keterangan</b></td>
		<td class="td2" style="border-right:1px solid #F5F5F5;word-wrap:break-word;"><?php echo $dataIzin->approval_note?></td>
	</tr>
	<tr>
		<td id="bottom-left" class="td2" style="border-left:1px solid #F5F5F5;border-bottom:1px solid #F5F5F5;vertical-align: text-top;"><b>Tanggal Disetujui</b></td>
		<td id="bottom-right" class="td2" style="border-right:1px solid #F5F5F5;border-bottom:1px solid #F5F5F5;word-wrap:break-word"><?php echo (string) date("d F Y",strtotime($dataIzin->disetujui_tgl));?></td>
	</tr>
	</table>
	<?php } ?>
	<br/>
	<?php if($dataIzin->diketahui_nama != null){ ?>
	<table style="border-spacing: 0;font-size: 100%;width: 100%; table-layout: fixed;">        
	<tr>
		<td id="top-left" class="td1" style="width:30%"><b>Diketahui Oleh</b></td>
		<td id="top-right" class="td1"><?php echo $dataIzin->diketahui_nama;?></td>
	</tr>
	<tr>
		<td id="bottom-left" class="td2" style="border-left:1px solid #F5F5F5;border-bottom:1px solid #F5F5F5;vertical-align: text-top;"><b>Tanggal Diketahui</b></td>
		<td id="bottom-right" class="td2" style="border-right:1px solid #F5F5F5;border-bottom:1px solid #F5F5F5;word-wrap:break-word"><?php echo (string) date("d F Y",strtotime($dataIzin->diketahui_tgl));?></td>
	</tr>
	</table>
	<?php } ?>
	<br/>
    <?php if($dataIzin->cancel_by != null){ ?>
	<table style="border-spacing: 0;font-size: 100%;width: 100%; table-layout: fixed;">        
	<tr>
		<td id="top-left" class="td1" style="width:30%"><b>Dibatalkan Oleh</b></td>
		<td id="top-right" class="td1"><?php echo $dataIzin->cancel_by;?></td>
	</tr>
	<tr>
		<td class="td2" style="border-left:1px solid #F5F5F5;vertical-align: text-top;"><b>Keterangan</b></td>
		<td class="td2" style="border-right:1px solid #F5F5F5;word-wrap:break-word""><?php echo $dataIzin->cancel_note;?></td>
	</tr>
	<tr>
		<td id="bottom-left" class="td2" style="border-left:1px solid #F5F5F5;border-bottom:1px solid #F5F5F5;vertical-align: text-top;"><b>Tanggal Dibatalkan</b></td>
		<td id="bottom-right" class="td2" style="border-right:1px solid #F5F5F5;border-bottom:1px solid #F5F5F5;word-wrap:break-word"><?php echo (string) date("d F Y",strtotime($dataIzin->cancel_date));?></td>
	</tr>
	</table>
	<?php } 
		 
		$linkApp = Yii::app()->getBaseUrl(true);
	?>
			
	<table style="font-family:  Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0;"><tr style="font-family:  Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;"><td style="font-family:  Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 10px 0;">
				<p style="font-family:  Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;"><a href="<?php echo $linkApp;?>" style="font-family:  Helvetica, Arial, sans-serif; font-size: 100%; line-height: 2; color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 25px; background-color: #348eda; margin: 0 10px 0 0; padding: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">LOGIN</a></p>
			</td>
		</tr></table>
	<p style="font-family:  Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">Silahkan klik tombol di atas atau akses alamat <a href="<?php echo $linkApp;?>"><?php echo $linkApp;?></a></p> 
	
	<p>*) Ini adalah email otomatis jangan me-reply / membalas email ini.</p>
<!--End Content-->	
				 </td>
			</tr>
			</table>
		</div>
	</td>
	<td style="font-size: 100%; margin: 0; padding: 0;"></td>		
</tr>
</table>
	
<table style="font-size: 100%; width: 100%; clear: both !important; margin: 0; padding: 0;">
<tr style="font-size: 100%; margin: 0; padding: 0;">
	<td style="margin: 0; padding: 0;"></td>
	<td style="font-size: 100%; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto; padding: 0;">	
		<div style="font-size: 100%; max-width: 600px; display: block; margin: 0 auto; padding: 0;">
			<table style="font-size: 100%; width: 100%; margin: 0; padding: 0;">
			<tr style="font-size: 100%; margin: 0; padding: 0;">
				<td align="center" style="font-size: 100%; margin: 0; padding: 0;">
					<p>Copyright @ 2016 - <a href="http://www.apotek-k24.com" alt="Waralaba Apotek Online" title="Waralaba Apotek Online" style=" font-size: 100%;  color: #999; margin: 0; padding: 0;"><unsubscribe style="font-size: 100%; margin: 0; padding: 0;">PT K-24 INDONESIA</unsubscribe></a>.
					</p>
				</td>
			</tr>
			</table>
		</div>
	</td>
	<td style="margin:0; padding: 0;"></td>
</tr></table>

</body>
</html>