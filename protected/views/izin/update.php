<?php $this->beginWidget('zii.widgets.CPortlet', array(
		'title'=>"~ PEMBATALAN - IZIN / CUTI ~",
	  )); ?>
<?php $this->renderPartial('_formCancel', array('model'=>$model)); ?>
<?php $this->endWidget();?>