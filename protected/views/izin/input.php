<?php $this->beginWidget('zii.widgets.CPortlet'); ?>
<div class="alert alert-info span12" style="margin:2px 0px 25px 0px;text-align:center;"><h3>- FORM INPUT IZIN & CUTI -</h3></div>
<div class="form" style="padding:10px">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'izin-cuti-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=> array('enctype' => 'multipart/form-data',),
)); ?>	
	<div class="row">
	<h5>Nama Associate:</h5>
		<?php $this->widget('ext.combobox.EJuiComboBox', array(
				'model' => $model,
				'attribute' => 'pegawai_nama_lengkap',
				'value'=>$model->pegawai_nama_lengkap, 
				'data' => UserLdap::model()->getOptionsInput(),
				'options' => array(
					'allowText' => false,
				),
				'htmlOptions' => array('placeholder' => 'Nama Associate', 'class'=>'span4'),
			));
		?>		
		<?php echo $form->error($model,'pegawai_nama_lengkap'); ?>
	</div>
	<div class="row">
	<h5>Tanggal Pengajuan:</h5>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', 
				  array('model' => $model,
						'attribute' => 'tgl_pengajuan',
						'name' => 'offerDate',
						'htmlOptions' => array('class'=>'span2 form-control',
												'size' => '10',
												'maxlength' => '10',
												'placeholder'=>'yyyy-mm-dd',
												'readonly'=>true,),
						'options' => array ('dateFormat' => 'yy-mm-dd',
											'autoclose' => true,
											'changeMonth'=>true,
											'beforeShowDay'=>'js:editDays',
										   ),
				)); ?>
		<?php echo $form->error($model,'tgl_pengajuan'); ?>		
	</div>	
	<h5>Permohonan Izin / Cuti :</h5>
	<input id="is_must_attach" type="hidden" name="is_must_attach" >
		<p style="margin-left:20px">		
		<?php $tipeIzin = TipeIzin::model()->findAll(array('select' => 'id,nama','condition' => 'status = 1', )); ?>
        <?php $opts = CHtml::listData($tipeIzin,'id','nama'); ?>
			<?php echo $form->radioButtonList($model,'tipe_izin_id', $opts, 
						array( 'separator'=>'<br/>'
							   ,'labelOptions'=>array('style'=>'display:inline')
							   ,'onChange'=>CHtml::ajax(array(
									'type'=>'POST', 									
									'url' =>array('izin/getAjaxTipeOptions'),
									'data'=>array('tipe_id'=>'js:this.value'),
									'success'=>"function(response){
										 //alert(response);
										$('#is_must_attach').val(response);
										// if( $('#is_must_attach').val() == 1 )	
											// $( '#isAttach' ).show();
										// else 
											// $( '#isAttach' ).hide();
									 }",
								)),
						)
				   ); ?>
		</p>
	<div class="row">
	<h5>Tanggal :</h5>
		<div style="float:left; width:20%; margin-left:0px">
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', 
					  array('model' => $model,
							'attribute' => 'tgl_mulai',
							'name' => 'Sdate',
							'htmlOptions' => array('class'=>'span8 form-control',
													'size' => '10',
													'maxlength' => '10',
													'placeholder'=>'yyyy-mm-dd',
													'readonly'=>true,),
							'options' => array ('dateFormat' => 'yy-mm-dd',
												'autoclose' => true,
												'changeMonth'=>true,
												'beforeShowDay'=>'js:editDays',
											   ),
					)); echo "<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;s/d </b>";	?>
			<?php echo $form->error($model,'tgl_mulai'); ?>				
		</div>
		<div style="float:left; width:20%">
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', 
				array('model' => $model,'attribute' => 'tgl_akhir',
						'name' => 'Edate',
						'htmlOptions' => array('class'=>'span8 form-control',
												'size' => '10',
												'maxlength' => '10',
												'placeholder'=>'yyyy-mm-dd',
												'readonly'=>true,),
						'options' => array ('dateFormat' => 'yy-mm-dd','autoclose' => true,
											'changeMonth'=>true,
											'beforeShowDay'=>'js:editDays',		
										   ),
				));	?>
		<?php echo $form->error($model,'tgl_akhir'); ?>		
		</div>
	</div>	
	<div class="row">
		<h5>Jumlah : <span id='labelHari' class="badge badge-warning" style="margin:0px 5px">0</span> Hari</h5>
		<?php echo $form->hiddenField($model,'jumlah_hari',array('class'=>'span1')); ?>
	</div>
  	<?php	foreach(Yii::app()->user->getFlashes() as $key => $message) {
				echo '<div style="width:78%" class="alert alert-' . $key . '">'
				      . $message . "</div>\n"; }
	?>	
	<div id='isAttach' class="row">		
		<h5>Lampiran ( <i>jpg / jpeg</i> )</h5>		
		<?php echo $form->fileField($model,'attach_dokumen',array('class'=>'btn', 'style'=>'text-align: left; width:40%')); ?>
		<?php echo $form->error($model,'attach_dokumen'); ?>
		 <div style="margin-top:7px;"><span style="display:none;" class="required" id="TxtFoto">Lampiran tidak boleh kosong.<br/><br/></span></div>
	</div>
	<div class="row">
		<h5>Alasan Izin / Cuti :<span class="required">*</span> </h5>	
		<?php echo $form->textArea($model,'alasan',array('rows'=>4, 'cols'=>50, 'class'=>'span10')); ?>
		<?php echo $form->error($model,'alasan'); ?>
	</div>
		
	<div class="row buttons" style="text-align:left">
		<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'submit',
				'caption'=>'Simpan',
				'htmlOptions'=>array('class'=>'btn btn-primary'),
				));	?>
		<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'submitNew',
				'caption'=>'Simpan & Tambah Baru',
				'htmlOptions'=>array('class'=>'btn btn-success'),
				));	?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->


<div id="dialog-message" title="Peringatan !" style="display: none;">
	<p>
		<b><span class="ui-icon ui-icon-alert" style="float:left; margin:10px 7px 0 0;"></span>
		<span id="msgErrorFile" style="float:left; margin:10px 0 0 0;">File lampiran harus berupa jpg/jpeg.</span></b>
	</p>
</div> 
<?php	
Yii::app()->clientScript->registerScript('editDays', "
	function showError(msg) {
		if(msg != '')
			$('#msgErrorFile').html(msg);
		
		$( '#dialog-message' ).dialog({
			modal: true,
			buttons: {
				Ok: function() {
					$(this).dialog('close');
					$('#Izin_attach_dokumen').focus();
				}
			}
		});  
	}
		
	function editDays(date) {	
		var listDay = ".json_encode($listWeekend).",
			cbList = ".json_encode($listCB).",
			rangeCB = [];
		
		for (var i = 0; i < cbList.length; i++) {
			rangeCB.push( new Date(cbList[i]).toString() );
		}
		
		if( jQuery.inArray(date.getDay().toString() , listDay) != -1 || jQuery.inArray( date.toString() , rangeCB ) != -1 ) 
			return [false,''];
		else
			return [true,''];
	}
");


Yii::app()->clientScript->registerScript('autoChange','  
	$("#Izin_jumlah_hari").val(0);
	$("#labelHari").html(0);
	
	function tipeIzin() {
		var tipe = $("#izin-cuti-form input[type=radio]:checked").val();
		var data = "tipe_id=" + tipe;
		$.ajax({
			type: "POST",
			url: "aksi-form.php",
			url: "'.Yii::app()->controller->createUrl("izin/getAjaxTipeOptions").'",
			data: data,
			success: function(response) {
				$("#is_must_attach").val(response);
				// if( $("#is_must_attach").val() == 1 )	
					// $( "#isAttach" ).show();
				// else 
				    // $( "#isAttach" ).hide();
			}
		});
	}		
	
	function diffDates() {	
        if ( $("#Sdate").val() != "" && $("#Edate").val() != "" ){
			var cbList = '.json_encode($listCB).',
				listDay = '.json_encode($listWeekend).';
			var startDate = $("#Sdate").val(), 
			    endDate  = $("#Edate").val(),  
			    dateRange = [],
			    dateRangeCB = [],
				dateRangeCL = [];   
			var tgl0 = new Date();
			var tgl1 = new Date(Date.parse(startDate));
			var tgl2 = new Date(Date.parse(endDate));
			
			if ( (tgl1.getFullYear() != tgl0.getFullYear()) || ( tgl2.getFullYear() != tgl0.getFullYear() ) ){
				$("#Sdate").val(""); 
				$("#Edate").val(""); 
				var msg = "<i>Periode Tahun Tanggal Mulai & Tanggal Akhir harus sama ....!!</i>";
				showError(msg);    
			}
			
			//list range date
			for (var d = new Date(startDate); d <= new Date(endDate); d.setDate(d.getDate() + 1)) {
				dateRange.push( $.datepicker.formatDate("mm/dd/yy", d) );	                					
			}
			
			//list range date tanpa cuti bersama
			for (var i = 0; i < dateRange.length; i++) {			
				if( jQuery.inArray( dateRange[i] , cbList) == -1 )
					dateRangeCB.push( dateRange[i] );	
			}
			
			//list range date tanpa weekend
			for (var i = 0; i < dateRangeCB.length; i++) {				
				var tgl = new Date( dateRangeCB[i] );
				if( jQuery.inArray( tgl.getDay().toString() , listDay) == -1 ) // -1 is in array
					dateRangeCL.push( dateRangeCB[i] );			
			}
			
			$("#Izin_jumlah_hari").val(dateRangeCL.length);
			$("#labelHari").html(dateRangeCL.length);
		}	
	}			 
		
	// $("#is_must_attach").change(function() {   
		// // var mustAttach = this.value.split(",")[1];
		// if( this.val() == 1 )	
			// $( "#is_must_attach" ).show();
		// else $( "#is_must_attach" ).hide();
	// });	
	
	$("#Sdate").change( diffDates );
	$("#Edate").change( diffDates );	
	$(document).ready(function(){		
		diffDates();
		tipeIzin();
	});
');	

   Yii::app()->clientScript->registerScript('attachment',"  
		function detectSize(fsize) {
			var max = 1048576; var maxKb = Math.round(max/1024).toFixed(2);
			if (fsize > 0)
			{
				var kb = Math.round(fsize/1024).toFixed(2); //convert to KB w/ two comma
				//detect file size no more than 512KB (524288 bytes)
				if(fsize<=max)
					return true;                    
			}
			var msg = 'Ukuran file lampiran : ' + kb + ' KB. Image tidak boleh lebih dari ' + maxKb + ' KB. Silahkan kecilkan ukuran image Anda atau pilih image lain.';
			showError(msg);
			return false;
		}
    
		$('#izin-cuti-form').submit(function(){  
			if($('#Izin_attach_dokumen').val() == ''){					
			}else{
				if (window.File && window.FileReader && window.FileList && window.Blob)
				{
					//get the file size and file type from file input field
					var fsize = $('#Izin_attach_dokumen')[0].files[0].size;
					var ftype = $('#Izin_attach_dokumen')[0].files[0].type;
					var fname = $('#Izin_attach_dokumen')[0].files[0].name;
					switch(ftype)
					{
						case 'image/jpeg':
						case 'image/jpg':
							if(detectSize(fsize))
								return true;
							return false;
						default:
							showError();
							return false;                        
					}               

				}else{
					var msg = 'Silakan upgrade browser Anda, browser Anda saat ini tidak memiliki beberapa fitur baru yang dibutuhkan sistem!';
					showError(msg);                
					return false;
				}            
			}
		
			return true;
		});
    ");
	
?>

<?php $this->endWidget();?>