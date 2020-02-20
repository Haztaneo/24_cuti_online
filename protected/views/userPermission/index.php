<?php
/* @var $this UserPermissionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Permissions',
);

$this->menu=array(
	array('label'=>'Create UserPermission', 'url'=>array('create')),
	array('label'=>'Manage UserPermission', 'url'=>array('admin')),
);
?>

<h1>User Permissions</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
