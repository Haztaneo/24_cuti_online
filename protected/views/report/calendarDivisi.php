<div class="row-fluid">
    <div class="span12">
<?php	$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'<span class="icon-th-list"></span> Kalender Cuti Per Divisi',
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
		<td style="width:12%"><b>Divisi</b></td>
		<td style="width:40%">
		<?php	
				if( Yii::app()->user->level == 2 || Yii::app()->user->level == 3 ){	
					if( Yii::app()->user->akses_report == 2 ){	//All Division
						echo CHtml::dropDownList('history[divisi]', $divisi, CHtml::listData(DeptDivisi::model()->findAllBySql("select id,nama from dept_divisi where status=1 "),'nama','nama'),array('empty' => '-- Semua Divisi --', 'class'=>'span12' )); 	
					}else{	//1=Private Division, 0=Private
						$modelPegawai = Pegawai::model()->find('uid="'.Yii::app()->user->id.'"');
						echo CHtml::textField('nama', $modelPegawai->deptDivisi->nama, array('class'=>'span12','disabled'=>'disabled'));
						echo CHtml::hiddenField('history[divisi]', $modelPegawai->deptDivisi->nama, array('readonly'=>true));
					}						
				}else{	
					$modelPegawai = Pegawai::model()->find('uid="'.Yii::app()->user->id.'"');
					echo CHtml::textField('nama', $modelPegawai->deptDivisi->nama, array('class'=>'span12','disabled'=>'disabled'));
					echo CHtml::hiddenField('history[divisi]', $modelPegawai->deptDivisi->nama, array('readonly'=>true));
				}
		?>
		</td>
		<td></td>
	</tr>
	<tr>
		<td><b>Tahun</b></td>
		<td><?php echo CHtml::dropDownList('history[tahun]', $tahun, 
              CHtml::listData(Izin::model()->findAllBySql("select distinct year(tgl_pengajuan)as tahun from izin where year(tgl_pengajuan)< year(now())"),'tahun','tahun'),array('empty' => date('Y'), 'class'=>'span6' )); ?>
		</td>	
		<td></td>
	</tr>
	<tr>
		<td><b>Bulan</b></td>
		<td>
			<?php echo CHtml::dropDownList('history[bulan]', $bulan, array( '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember',), array('class'=>'span6')); ?> 
			&nbsp;&nbsp;&nbsp;
			<?php $this->widget('booster.widgets.TbButton',array(
					'buttonType' => 'submit', 'label' => 'Tampilkan', 'context' => 'primary', 
					'htmlOptions'=>array('name'=>'tbSubmit', 'style'=>'height:30px;vertical-align: top;',),
			));?>
		</td>
		<td style="text-align:right; padding-right:10px">	
		<?php	if( Yii::app()->user->level == 2 || Yii::app()->user->level == 3){  ?>
			<a href="<?php echo Yii::app()->request->baseUrl;?>/report/excelDivisi">
				<img title="Export to Excel" src="<?php echo Yii::app()->request->baseUrl.'/images/excel-icon.png' ?>" width="30" height="30" />Export Excel</a>
			<?php if( Yii::app()->user->akses_report == 2 ){	?>
			&nbsp;&nbsp;&nbsp;
			<a href="<?php echo Yii::app()->request->baseUrl;?>/report/excelAllAssociate">
				<img title="Export to Excel" src="<?php echo Yii::app()->request->baseUrl.'/images/excel-icon.png' ?>" width="30" height="30" />Semua Divisi</a>
			<?php }	 
		      }elseif( Yii::app()->user->level == 1 ){ 
				//if( count($data)>0 && !empty($_POST['history']['divisi']) ){	?>
			<!--<a href="<?php //echo Yii::app()->request->baseUrl;?>/report/xallasLD2">
				<img title="Export to Excel" src="<?php //echo Yii::app()->request->baseUrl.'/images/excel-icon.png' ?>" width="30" height="30" />Export Excel</a>
				&nbsp;&nbsp;&nbsp; -->
			   <?php //} ?>
			<a href="<?php echo Yii::app()->request->baseUrl;?>/report/xallasLD">
				<img title="Export to Excel" src="<?php echo Yii::app()->request->baseUrl.'/images/excel-icon.png' ?>" width="30" height="30" />Export Excel</a>
		<?php } ?>
		</td>
	</tr>
</table> 
<?php 
	$this->endWidget();
	unset($form);
$this->endWidget(); 
?>
    </div>
</div>

<?php if( isset($_POST['tbSubmit']) && count($data)==0){
?>
<div class="alert alert-warning span12" style="margin:2px 0px 25px 0px;text-align:left; font-weight: bold;">Data tidak ditemukan !!</div>
<?php 
		$tahun = empty($_POST['history']['tahun'])?date('Y'):$_POST['history']['tahun'];
		$bulan = isset($_POST['history']['bulan'])?$_POST['history']['bulan']:''.date('m').'';
		$this->widget('ext.fullcalendar.EFullCalendarHeart', array(
		'themeCssFile'=>'cupertino/jquery-ui.min.css',
		'options'=>array(
			'header'=>array(
				'left'=>'', 
				'center'=>'title',
				'right'=>'',
			),
			'lang'=> 'id',
			'fixedWeekCount'=> false, 
			'defaultDate'=> $tahun.'-'.$bulan.'-02',
			'eventLimit'=>true,  
			'contentHeight'=> 550,
		)
		));
?>
<?php }elseif( isset($_POST['tbSubmit'])&& count($data)>0){ 
		$tahun = empty($_POST['history']['tahun'])?date('Y'):$_POST['history']['tahun'];
		$bulan = isset($_POST['history']['bulan'])?$_POST['history']['bulan']:''.date('m').'';
		$this->widget('ext.fullcalendar.EFullCalendarHeart', array(
			'themeCssFile'=>'cupertino/jquery-ui.min.css',
			'options'=>array(
				'header'=>array(
					'left'=>'', 
					'center'=>'title',
					'right'=>'', 
				),
				'lang'=> 'id',
				'fixedWeekCount'=> false, 
				'defaultDate'=> $tahun.'-'.$bulan.'-02',		
				'events'=>$this->createUrl( 'calendarEventsDivisi', array('divisi'=>$divisi) ),
				/*Enable URL In controller (Open detail in new window)
				'eventClick'=> 'js:function(event) {	
					if (event.url) {
						window.open(event.url, "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=100, left=300, width=+screen.width, height=500, directories=no, location=no, menubar=no, titlebar=no ");					  	
						return false;
					}
				}',	*/
				'eventClick'=> 'js:function(calEvent, jsEvent, view) {
					$("#modalHeader").html(calEvent.title);
					$("#modalBody").load("'.Yii::app()->createUrl("/report/detail/id").'/"+calEvent.id);
					$("#myModal").modal();
				}',
				'eventLimit'=>true, 
				'contentHeight'=> 550,
			)));	
}
else{
	$this->widget('ext.fullcalendar.EFullCalendarHeart', array(
		'themeCssFile'=>'cupertino/jquery-ui.min.css',
		'options'=>array(
			'header'=>array(
				'left'=>'', 
				'center'=>'title',
				'right'=>'',
			),
			'lang'=> 'id',
			'fixedWeekCount'=> false, 
			'eventLimit'=>true,
			'contentHeight'=> 550,
		)
		));
}	
?>

<?php $this->beginWidget('booster.widgets.TbModal', 
				array('id' => 'myModal',
					  'htmlOptions' => ['style' => 'width: 900px; margin-left: -400px'])
		  ); ?>
	<div class="modal-body" id="modalBody">
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