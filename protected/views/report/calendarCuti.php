<div class="row-fluid">
    <div class="span12">
<?php	$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'<span class="icon-th-list"></span> Kalender Cuti Associate',
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
		<td style="width:15%"><b>Nama Associate</b></td>
		<td style="width:45%">
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
							echo CHtml::textField('nama', $modelPegawai->nama_lengkap, array('class'=>'span10','disabled'=>'disabled'));
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
						echo CHtml::textField('nama', $modelPegawai->nama_lengkap, array('class'=>'span10','disabled'=>'disabled'));
						echo CHtml::hiddenField('history[uid]', Yii::app()->user->id, array('readonly'=>true));
					}
		?>			
		</td>
		<td></td>
	</tr>
	<tr>
		<td><b>Tahun</b></td>
		<td><?php echo CHtml::dropDownList('history[tahun]', $tahun, 
              CHtml::listData(Izin::model()->findAllBySql("select distinct year(tgl_pengajuan)as tahun from izin where year(tgl_pengajuan)< year(now())"),'tahun','tahun'),array('empty' => date('Y'), 'class'=>'span5' )); ?>
		</td>	
		<td></td>
	</tr>
	<tr>
		<td><b>Bulan</b></td>
		<td>
			<?php echo CHtml::dropDownList('history[bulan]', $bulan, array( '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember',), array('class'=>'span5')); ?> 
			&nbsp;&nbsp;&nbsp;
			<?php $this->widget('booster.widgets.TbButton',array(
					'buttonType' => 'submit', 'label' => 'Tampilkan', 'context' => 'primary', 
					'htmlOptions'=>array('name'=>'tbSubmit', 'style'=>'height:30px;vertical-align: top;',),
			));?>
		</td>
		<td style="text-align:right; padding-right:10px">	
		<?php if( Yii::app()->user->level == 2 || Yii::app()->user->level == 3){  ?>
			<a href="<?php echo Yii::app()->request->baseUrl;?>/report/excelAssociate">
				<img title="Export to Excel" src="<?php echo Yii::app()->request->baseUrl.'/images/excel-icon.png' ?>" width="30" height="30" />Export Excel</a>
			<?php if( Yii::app()->user->akses_report == 2 ){	?>
				&nbsp;&nbsp;&nbsp;
				<a href="<?php echo Yii::app()->request->baseUrl;?>/report/excelAllAssociate">
					<img title="Export to Excel" src="<?php echo Yii::app()->request->baseUrl.'/images/excel-icon.png' ?>" width="30" height="30" />Semua Associate</a>
		<?php }
			}elseif( Yii::app()->user->level == 1 ){ 
				if( count($data)>0 && !empty($_POST['history']['uid']) ){	?>
			<a href="<?php echo Yii::app()->request->baseUrl;?>/report/excelAssociate">
				<img title="Export to Excel" src="<?php echo Yii::app()->request->baseUrl.'/images/excel-icon.png' ?>" width="30" height="30" />Export Excel</a>
			   <?php } ?>
				&nbsp;&nbsp;&nbsp;
			<a href="<?php echo Yii::app()->request->baseUrl;?>/report/xallasLD">
				<img title="Export to Excel" src="<?php echo Yii::app()->request->baseUrl.'/images/excel-icon.png' ?>" width="30" height="30" />Semua Associate</a>
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

<?php if( isset($_POST['tbSubmit']) && count($data)==0){ ?>
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
		
 }elseif( isset($_POST['tbSubmit']) && count($data)>0){ 
 
		$tahun = empty($_POST['history']['tahun'])?date('Y'):$_POST['history']['tahun'];
		$bulan = isset($_POST['history']['bulan'])?$_POST['history']['bulan']:''.date('m').'';

		$this->widget('ext.fullcalendar.EFullCalendarHeart', array(
			'themeCssFile'=>'cupertino/jquery-ui.min.css',
			'options'=>array(
				'header'=>array(
					'left'=>'', //'month,agendaWeek,agendaDay',
					'center'=>'title',
					'right'=>'', //'today,prev,next',            
				),
				'lang'=> 'id',
				'fixedWeekCount'=> false, //ver2.1.0
				'defaultDate'=> $tahun.'-'.$bulan.'-02',		
				'events'=>$this->createUrl( 'calendarEvents', array('uid'=>$uid) ),
				'eventLimit'=>true,  
				// 'height'=> 650,
				'contentHeight'=> 550,
				'viewRender'=> 'js:function(view, element) {			
					// alert(view.end);			
					// var tahun = new Date().getFullYear();
					var tahun = '.$tahun.';
					
					if( new Date(view.end).getFullYear() > tahun ){
						$("#yw3 .fc-next-button").addClass("fc-state-disabled");
						$("#yw3 .fc-next-button").attr("disabled","disabled");
						return false;
					}else{
						$("#yw3 .fc-next-button").removeClass("fc-state-disabled");
						$("#yw3 .fc-next-button").attr("enabled","enabled");
					}

					if( new Date(view.start).getFullYear() < tahun ){
						$("#yw3 .fc-prev-button").addClass("fc-state-disabled");
						$("#yw3 .fc-prev-button").attr("disabled","disabled");
						return false;
					}else {
						$("#yw3 .fc-prev-button").removeClass("fc-state-disabled");
						$("#yw3 .fc-prev-button").attr("enabled","enabled");
					}
				}',
				'eventClick'=> 'js:function(calEvent, jsEvent, view) {
					$("#modalHeader").html(calEvent.title);
					$("#modalBody").load("'.Yii::app()->createUrl("/report/detail/id").'/"+calEvent.id);
					$("#myModal").modal();
				}',
				/* Enable URL In controller (Open detail in new window)
				'eventClick'=> 'js:function(event) {	
					if (event.url) {
						window.open(event.url, "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=100, left=300, width=+screen.width, height=500");					  	
						return false;
					}
				}',*/				   					
				/* With Dialog
				'eventClick'=> 'js:function(calEvent, jsEvent, view) {	
					 $("#dialog").dialog({
							autoOpen: false,
						});
						$("#name").val(calEvent.id);
						$("#title").val(calEvent.title);
						$("#dialog").dialog("open");
				}',*/	
				/*'height'=> 600,
				'contentHeight'=> 600,
				'hiddenDays'=> [ 1, 3, 5 ],
				'fixedWeekCount'=> false, //ver2.1.0
				'weekNumbers'=> true,
				'weekends'=> false,
				'dayClick'=> "js:function(date, jsEvent, view) {
					alert('a day has been clicked!');
					if(view.name == 'month' || view.name == 'basicWeek') {
						$('#calendar').fullCalendar('changeView', 'basicDay');
						$('#calendar').fullCalendar('gotoDate', date);      
					}
				}",*/
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

<?php 	$this->beginWidget('booster.widgets.TbModal', 
				array('id' => 'myModal',
					  'htmlOptions' => ['style' => 'width: 900px; margin-left: -400px'])
			); 
?>
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

<!-- with dialog
<div id="fullcalendar"></div>
<div id="dialog" title="My dialog" style="display:none">
    <form>
        <fieldset>
            <label for="Id">Id</label>
            <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all">
            <label for="Id">Title</label>
            <input type="text" name="title" id="title" class="text ui-widget-content ui-corner-all">
        </fieldset>
    </form>
</div>-->