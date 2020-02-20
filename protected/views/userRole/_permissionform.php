<div class="form" style="padding:10px">
	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'role-grid',
		'dataProvider'=>$permission->search(),
		//'filter'=>$model,
		'rowCssClass'=> $checked,
		'columns'=>array(
			array('name'=>'no',
						'type'=>'raw',
						'header' => 'No ',
						'value' => '$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
						'filter' => '',
						'htmlOptions'=>array('valign'=>'top','style'=>'width:40px;'),
			),
			array( 'name'=>'Nama Permission',
						'type'=>'raw',
						'header' =>'Nama Permission',
						'value' =>'$data->name',
			),
			array( 'name'=>'tickmark',
						'type'=>'raw',
						'header' => 'Allowed',
						'filter' => '',
						'htmlOptions'=>array('width'=>'80', 'align'=>'center'),
						'value' => 'CHtml::checkBox( "cek_".($row+1),
											in_array($data->id, $this->grid->rowCssClass)? true:false, 
											array("value"=>$data->id, "id"=>"cek_".($row+1)) )',
			),
		),
	)); ?>

	<input type="hidden" name="counter" value="<?php echo count($permission->search()->getData()); ?>">
   <div class="row buttons">
		<?php $this->widget('zii.widgets.jui.CJuiButton', array(
				'name'=>'submit',
				'caption'=>'Simpan',
				'htmlOptions'=>array('class'=>'btn btn-primary'),
				));	?>
	</div>
</div><!-- form -->