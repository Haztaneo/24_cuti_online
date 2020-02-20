<div class="well" style="padding:10px;text-align:right;">  
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'btAdd',
				'caption'=>'<b>Synchronize LDAP</b>',
				'buttonType'=>'link',
				'url'=>Yii::app()->controller->createUrl("ask"),
				'htmlOptions'=>array('class'=>'btn btn-info'),
		));	?>
</div>
<div id='statusMsg'></div>
<?php $this->beginWidget('zii.widgets.CPortlet', array(
			// 'title'=>"- Daftar User LDAP -",
	  ));
?>
<div class="alert alert-info" style="margin:5px 5px 10px 5px">
	<b>Email dengan <font color="#FF6B6B">Status Email</font> = "active" dan <font color="#FF6B6B">is Visible</font> =  "YES" yang akan ditampilkan dan digunakan dalam sistem</b>
</div>	

<?php	
ob_start();
$this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'user-ldap-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover',
	'columns'=>array(
		array(
			'header' => 'No',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:3%;'),
		),	
		array(
			'header' => 'Email',
			'name'=>'email',
			'value' => '$data["email"]',
			'htmlOptions' => array('style'=>'color:#5B81FF'),	
		),
		array(
			'header' => 'Nama',
			'name'=>'nama',
			'value' => '$data["nama"]',
			'htmlOptions' => array('style'=>'color:#5B81FF'),	
		),
		array(
			'header' => 'Status Email',			
			'value' => function($data) {
				if( $data["status"]=='active' ){
						return "<span style='color:#5B81FF';>".$data["status"]."</span>";
					}else{
						return "<span style='background-color:red; color:white; padding:5px'>".$data["status"]." **</span>";
					}
			},
			'type'=>'html',	
	        'htmlOptions' => array('style'=>'width:90px;text-align:center'),			
		),
		array(
			'header' => 'is Visible',
			'value' => '$data["is_visible"]==1?"<font color=#5B81FF><b>YES</b></font>":"<font color=red><b>NO</b></font>"',
			'htmlOptions' => array('style'=>'width:65px;text-align:center'),
			'type'=>'html',
		),		
		array(
			'header'=>'Action', 
			'type' =>'raw',
			'value' => function($data) {
			return CHtml::link("<span style='color:green'><b>Hide</b></span>",
				Yii::app()->request->baseUrl."/userLdap/hide/uid/".$data->uid,
				array('class'=>'btn btn-mini','onClick'=>"javascript:return confirm('Are you sure you want to hide : ".$data->nama." ?')"));				
			},
			'htmlOptions' => array('style'=>'width:45px;text-align:center'),
		),
	),
)); 
$tab1Content=ob_get_contents();
ob_end_clean();

ob_start();
$this->widget('zii.widgets.grid.CGridView', array(		
	'id'=>'user-ldap-grid2',
	'dataProvider'=>$model->search(1),
	'filter'=>$model,
	'itemsCssClass'=>'table table-striped table-bordered table-hover',
	'columns'=>array(
		array(
			'header' => 'No',
			'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
			'htmlOptions' => array('style'=>'text-align: center; width:3%;'),
		),	
		array(
			'header' => 'Email',
			'name'=>'email',
			'value' => '$data["email"]',
			'htmlOptions' => array('style'=>'color:#777777'),
		),
		array(
			'header' => 'Nama',
			'name'=>'nama',
			'value' => '$data["nama"]',
			'htmlOptions' => array('style'=>'color:#777777'),
		),
		array(
			'header' => 'Status Email',			
			'value' => function($data) {
				if( $data["status"]=='active' ){
						return "<span style='color:#777777';>".$data["status"]."</span>";
					}else{
						return "<span style='background-color:red; color:white; padding:5px'>".$data["status"]." **</span>";
					}
			},
			'type'=>'html',	
	        'htmlOptions' => array('style'=>'width:90px;text-align:center'),			
		),
		array(
			'header' => 'is Visible',
			'value' => '$data["is_visible"]==1?"<font color=#777777><b>YES</b></font>":"<font color=red><b>NO</b></font>"',
			'htmlOptions' => array('style'=>'width:65px;text-align:center;'),
			'type'=>'html',
		),
		array(
			'header'=>'Action', 
			'type' =>'raw',
			'value' => function($data) {
				return CHtml::link("<span style='color:#777777'><b>Show</b></span>",
					Yii::app()->request->baseUrl."/userLdap/show/uid/".$data->uid,
					array('class'=>'btn btn-mini','onClick'=>"javascript:return confirm('Are you sure you want to show : ".$data->nama." ?')"));
			},
			'htmlOptions' => array('style'=>'width:45px;text-align:center'),
		),
	),
)); 
$tab2Content=ob_get_contents();
ob_end_clean();

$thisYear = '<font color="#0090FF">LDAP - AKTIF</font>';
$lastYear = '<font color="#FF8300">LDAP - TIDAK AKTIF</font>';
$this->widget('zii.widgets.jui.CJuiTabs',array(
    'tabs'=>array(
        $thisYear => array('content' => $tab1Content,'id' => 'Active'),
        $lastYear =>array('content'=> $tab2Content, 'id'=>'nonActive'),
    ),
    'options'=>array(
        'collapsible'=>false,
    ),
));	

// array(
	// 'class'=>'CButtonColumn',
	// 'template'=>'{update}',
// ),
// array(
	 // 'class'=>'CButtonColumn',
	 // 'template'=>'{show}{hide}',
	 // 'buttons'=>array(
		  // 'show'=>array(
			   // 'label'=>'Show',
			   // 'url'=>'Yii::app()->controller->createUrl("show",array("uid"=>$data->uid))',
				// // 'click'=>'CHtml::ajax(array("update"=>"ajaxPanel")',
				// 'options'=>array('class'=>'btn btn-null btn-mini', 'style'=>'font-weight: bold;color: blue;','onclick'=>'return confirm("Anda yakin akan menampilkan data ?")'),					
				// 'visible' => '$data->is_visible == 0',
		   // ),
			// 'hide'=>array(
			   // 'label'=>'Hide',
			   // 'url'=>'Yii::app()->controller->createUrl("hide",array("uid"=>$data->uid))',
				// 'options'=>array('class'=>'btn btn-null btn-mini', 'style'=>'font-weight: bold;color: green;','onclick'=>'return confirm("Anda yakin akan tidak menampilkan data ?")'),					
				// 'visible' => '$data->is_visible == 1',
		   // ),
	 // ),
// ),
?>	
<?php $this->endWidget();?>