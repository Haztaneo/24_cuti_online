<?php
/* @var $this UserPermissionController */
/* @var $model UserPermission */

$this->breadcrumbs=array(
	'User Permissions'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List UserPermission', 'url'=>array('index')),
	array('label'=>'Create UserPermission', 'url'=>array('create')),
	array('label'=>'Update UserPermission', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserPermission', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserPermission', 'url'=>array('admin')),
);
?>

<h1>View UserPermission #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
