<?php if( isset(Yii::app()->user->level) && Yii::app()->user->level >1 && Yii::app()->user->show_admin_page >1 ){ ?>
	<div class="well" style="padding:10px;text-align:right;">  
		<?php	$this->widget('zii.widgets.jui.CJuiButton', array(
							'name'=>'btAdd',
							'caption'=>'Tambah Cuti Bersama',
							'buttonType'=>'link',
							'url'=>Yii::app()->controller->createUrl("create"),
							'htmlOptions'=>array('class'=>'btn btn-info'),
					));	
		?>
	</div>
<?php }?>

<div id='statusMsg'></div>
<?php $this->beginWidget('zii.widgets.CPortlet', array( )); ?>
<div class="tabbable" style="margin:15px"> <!-- Only required for left/right tabs -->
	<ul class="nav nav-pills nav-justified" style="margin-bottom:-2px">
		<li class="active">
			<a href="#tab1" data-toggle="tab">
				<span class="tab_label_open"><b>CUTI BERSAMA - TAHUN <?php echo date('Y');?> <span id="open"></span></span></b></a>
		</li>
		<li>
			<a href="#tab2" data-toggle="tab">
				<span class="tab_label_close" style="color:#FFB566">
				<b>CUTI BERSAMA - SEBELUM TAHUN <?php echo date('Y');?></b>
				<span id="close"></span></span>
			</a>
		</li>
	</ul>
	<hr/>
	<div class="tab-content" style="margin-top:-15px">
		<div class="tab-pane active" id="tab1">  
		<?php if( isset(Yii::app()->user->level) && Yii::app()->user->level >1 ){
					$this->widget('zii.widgets.grid.CGridView', array(		
						'id'=>'cutiBersama-grid',
						'dataProvider'=>$model->search(),
						'itemsCssClass'=>'table table-striped',
						'template'=>"{items}{summary}{pager}",
						'columns'=>array(
							array(
								'header' => '<font color="#3A87AD">No</font>',
								'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
								'htmlOptions' => array('style'=>'text-align: center; width:25px;color:#6363FF;'),
							),	
							array(
								'header' => '<font color="#3A87AD">Tanggal</font>',
								'value' => function($data) {
									return date("d M Y",strtotime($data["tgl"]));
								},
								'type'=>'html',
								'htmlOptions' => array('style'=>'width:120px;color:#6363FF;'),
							),
							array(
								'header' => '<font color="#3A87AD">Hari</font>',
								'value' => function($data) {
									return $data["hari"];
								},
								'type'=>'html',
								'htmlOptions' => array('style'=>'width:80px;color:#6363FF;'),
							),
							array(
								'header' => '<font color="#3A87AD">Keterangan</font>',
								'value' => function($data) {
									return "<span style='color:#6363FF;'>".$data["keterangan"]."</span>";
								},
								'type'=>'html',
							),
							array(
								'class'=>'CButtonColumn',
								'template'=>'{update} {delete}',
								'afterDelete'=>'function(link,success,data){
													if(success){
														window.location.reload();
														// $("#statusMsg").html(data); show message
													}
												}',
								'buttons'=>array(
								  'update'=>array(
										'visible'=>'$data->periode == '. date('Y'),
								  ),
								  'delete'=>array(
										'visible'=>'$data->periode == '. date('Y'),
								  ),
								),
							),
						),
					));
				}else{
					$this->widget('zii.widgets.grid.CGridView', array(		
					'id'=>'cutiBersama-grid2',
					'dataProvider'=>$model->search(),
					'template'=>'{items}{summary}{pager}',
					'itemsCssClass'=>'table table-striped',
					'columns'=>array(
						array(
							'header' => '<font color="#3A87AD">No</font>',
							'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
							'htmlOptions' => array('style'=>'text-align: center; width:25px;color:#6363FF;'),
						),	
						array(
							'header' => '<font color="#3A87AD">Tanggal</font>',
							'name'=>'tgl',
							'value' => function($data) {
								return date("d M Y",strtotime($data["tgl"]));
							},
							'type'=>'html',
							'htmlOptions' => array('style'=>'width:120px;color:#6363FF;'),
						),
						array(
							'header' => '<font color="#3A87AD">Hari</font>',
							'name'=>'hari',
							'value' => function($data) {
								return $data["hari"];
							},
							'type'=>'html',
							'htmlOptions' => array('style'=>'width:80px;color:#6363FF;'),
						),
						array(
							'header' => '<font color="#3A87AD">Keterangan</font>',
							'name'=>'keterangan',
							'value' => function($data) {
								return "<span style='color:#6363FF;'>".$data["keterangan"]."</span>";
							},
							'type'=>'html',
						),
					),
				   ));
				}
			?>
		</div>
		<div class="tab-pane" id="tab2">			
	    <?php
			 $this->widget('zii.widgets.grid.CGridView', array(		
					'id'=>'cutiBersama-grid3',
					'dataProvider'=>$model->search(1),
					'template'=>'{items}{summary}{pager}',
					'itemsCssClass'=>'table table-striped',
					'columns'=>array(
						array(
							'header' => '<font color="#3A87AD">No</font>',
							'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
							'htmlOptions' => array('style'=>'text-align: center; width:25px;'),
						),	
						array(
							'header' => '<font color="#3A87AD">Periode</font>',
							'name'=>'periode',
							'value' => function($data) {
								return "<span style='color:#7A7A7A;'>".$data["periode"]."</span>";
							},
							'type'=>'html',
							'htmlOptions' => array('style'=>'width:100px;'),
						),
						array(
							'header' => '<font color="#3A87AD">Tanggal</font>',
							'name'=>'tgl',
							'value' => function($data) {
								return "<span style='color:#7A7A7A;'>".date("d M Y",strtotime($data["tgl"]))."</span>";
							},
							'type'=>'html',
							'htmlOptions' => array('style'=>'width:120px;'),
						),
						array(
							'header' => '<font color="#3A87AD">Hari</font>',
							'name'=>'hari',
							'value' => function($data) {
								return "<span style='color:#7A7A7A;'>".$data["hari"]."</span>";
							},
							'type'=>'html',
							'htmlOptions' => array('style'=>'width:80px;'),
						),
						array(
							'header' => '<font color="#3A87AD">Keterangan</font>',
							'name'=>'keterangan',
							'value' => function($data) {
								return "<span style='color:#7A7A7A;'>".$data["keterangan"]."</span>";
							},
							'type'=>'html',
						),		
					),
				));
			 ?>
		</div>
	</div>
</div>
<?php $this->endWidget();?>

<?php
/* OLD	
ob_start();
if( isset(Yii::app()->user->level) && Yii::app()->user->level >1 ){
$this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'cutiBersama-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover',
	'columns'=>array(
		array(
			'header' => 'No',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:25px;color:#6363FF;'),
		),	
		array(
			'header' => 'Tanggal',
			'name'=>'tgl',
			'value' => function($data) {
				return date("d M Y",strtotime($data["tgl"]));
			},
			'type'=>'html',
			'htmlOptions' => array('style'=>'font-size:11px;text-align: center; width:90px;color:#6363FF;'),
		),
		array(
			'header' => 'Hari',
			'name'=>'hari',
			'value' => function($data) {
				return $data["hari"];
			},
			'type'=>'html',
			'htmlOptions' => array('style'=>'font-size:11px;text-align: center; width:60px;color:#6363FF;'),
		),
		array(
			'header' => 'Keterangan',
			'name'=>'keterangan',
			'value' => function($data) {
				return "<span style='font-size:11px;color:#6363FF;'>".$data["keterangan"]."</span>";
			},
			'type'=>'html',
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update} {delete}',
			'afterDelete'=>'function(link,success,data){
								if(success){
									window.location.reload();
									// $("#statusMsg").html(data); show message
								}
							}',
			'buttons'=>array(
			  'update'=>array(
					'visible'=>'$data->periode == '. date('Y'),
			  ),
			  'delete'=>array(
					'visible'=>'$data->periode == '. date('Y'),
			  ),
			),
		),
	),
));
}else{
	$this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'cutiBersama-grid2',
	'dataProvider'=>$model->search(),
	'itemsCssClass'=>'table table-striped table-bordered table-hover',
	'columns'=>array(
		array(
			'header' => 'No',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:25px;color:#6363FF;'),
		),	
		array(
			'header' => 'Tanggal',
			'name'=>'tgl',
			'value' => function($data) {
				return date("d M Y",strtotime($data["tgl"]));
			},
			'type'=>'html',
			'htmlOptions' => array('style'=>'font-size:11px;text-align: center; width:90px;color:#6363FF;'),
		),
		array(
			'header' => 'Hari',
			'name'=>'hari',
			'value' => function($data) {
				return $data["hari"];
			},
			'type'=>'html',
			'htmlOptions' => array('style'=>'font-size:11px;text-align: center; width:60px;color:#6363FF;'),
		),
		array(
			'header' => 'Keterangan',
			'name'=>'keterangan',
			'value' => function($data) {
				return "<span style='font-size:11px;color:#6363FF;'>".$data["keterangan"]."</span>";
			},
			'type'=>'html',
		),
	),
   ));
}
$tab1Content=ob_get_contents();
ob_end_clean();

ob_start();
$this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'cutiBersama-grid3',
	'dataProvider'=>$model->search(1),
	// 'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover',
	'columns'=>array(
		array(
			'header' => 'No',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:25px;'),
		),	
		array(
			'header' => 'Periode',
			'name'=>'periode',
			'value' => function($data) {
				return "<span style='color:#FF7700;'>".$data["periode"]."</span>";
			},
			'type'=>'html',
			'htmlOptions' => array('style'=>'font-size:11px;text-align: center; width:50px;'),
		),
		array(
			'header' => 'Tanggal',
			'name'=>'tgl',
			'value' => function($data) {
				return "<span style='color:#FF7700;'>".date("d M Y",strtotime($data["tgl"]))."</span>";
			},
			'type'=>'html',
			'htmlOptions' => array('style'=>'font-size:11px;text-align: center; width:90px;'),
		),
		array(
			'header' => 'Hari',
			'name'=>'hari',
			'value' => function($data) {
				return "<span style='color:#FF7700;'>".$data["hari"]."</span>";
			},
			'type'=>'html',
			'htmlOptions' => array('style'=>'font-size:11px;text-align: center; width:60px;'),
		),
		array(
			'header' => 'Keterangan',
			'name'=>'keterangan',
			'value' => function($data) {
				return "<span style='font-size:11px;color:#FF7700;'>".$data["keterangan"]."</span>";
			},
			'type'=>'html',
		),		
	),
));
$tab2Content=ob_get_contents();
ob_end_clean();

$thisYear = '<font color="#3490FF">CUTI BERSAMA - TAHUN '.date('Y').'</font>';
$lastYear = '<font color="#FF8300">CUTI BERSAMA - SEBELUM TAHUN '.date('Y').'</font>';
$this->widget('zii.widgets.jui.CJuiTabs',array(
    'tabs'=>array(
        $thisYear => array('content' => $tab1Content,'id' => 'ThisYear'),
        $lastYear =>array('content'=> $tab2Content, 'id'=>'LastYear'),
    ),
    'options'=>array(
        'collapsible'=>false,
    ),
));	
*/
?>	