<?php	$modelPegawai = Pegawai::model()->find(array("condition"=>"uid='".Yii::app()->user->id."'"));	
	if( count($modelPegawai)==0 ){
		echo '<div class="alert alert-warning">
				<h5><font color="#FF6B6B">Anda belum terdaftar dalam menu Associate. Tambahkan data anda pada menu <a href="'.Yii::app()->controller->createUrl("pegawai/create").'">Associate</a> untuk dapat mengakses halaman ini</font></h5>
			  </div>';		
	}elseif( $modelPegawai->nama_atasan==null ){
		echo '<div class="alert alert-warning">
				<h5><font color="#FF6B6B">Untuk dapat mengakses halaman ini nama atasan Anda harus terdaftar terlebih dahulu. <br/>Silahkan hubungi Admin untuk mendaftarkan nama atasan Anda.</h5>
			  </div>';		
	}else{
?>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
		// 'title'=>"- TAMBAH Izin Cuti -",
	  )); ?>
<div class="well" style="padding:0px 0px;text-align:center;">  
	<h1><small>FORM PERMOHONAN IZIN & CUTI</small></h1> 
</div>
<?php $this->renderPartial('_form', array('model'=>$model,'listWeekend'=>$listWeekend, 'listCB'=>$listCB)); ?>
<?php $this->endWidget();?>

<?php } ?>