<?php
class CutiBersamaController extends Controller
{
	public $layout='//layouts/column2';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			// array('booster.filters.BoosterFilter - delete'),
		);
	}

	public function accessRules()
	{
		return array(			
			array('allow', 
				'actions'=>array('create','update','admin','delete','index','view','updatePotongCuti'),
                'expression'=>'isset(Yii::app()->user->show_admin_page) && Yii::app()->user->show_admin_page == 1',
			),	
			array('allow', 
				'actions'=>array('index','admin','view'),
                'expression'=>'isset(Yii::app()->user->level)',
			),				
			array('deny',  
				'users'=>array('*'),
			),
		);
	}

	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	public function actionCreate()
	{
		$model = new CutiBersama;
		
        if(isset($_POST['CutiBersama']))
		{
			$model->attributes=$_POST['CutiBersama'];
			$model->periode = date_format(date_create($model->tgl),'Y');
			$model->hari = $this->convertDay($model->tgl);
			if($model->save()){
				$this->actionUpdatePotongCuti();
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);	
		
		if(isset($_POST['CutiBersama']))
		{
			$model->attributes = $_POST['CutiBersama'];
			$model->periode = date_format(date_create($model->tgl),'Y');
			$model->hari = $this->convertDay($model->tgl);
			if($model->save()){
				$this->actionUpdatePotongCuti();
				$this->redirect(array('admin'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		try{
			$this->loadModel($id)->delete();			
			$this->actionUpdatePotongCuti();
			
			echo '<div style="width:94%" class="alert alert-info"><strong>Deleted Successfully </strong><button type="button" class="close" data-dismiss="alert">×</button></div>'; 
		}catch(CDbException $e){
			echo '<div style="width:94%" class="alert alert-notice"><strong>Failed to delete data. This data is being used by other data </strong><button type="button" class="close" data-dismiss="alert">×</button></div>'; 
		}
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	public function actionIndex()
	{
		$this->actionAdmin();
	}
	
	public function actionAdmin()
	{
		$model=new CutiBersama('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CutiBersama']))
			$model->attributes=$_GET['CutiBersama'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=CutiBersama::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cuti-bersama-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	private function convertDay($day){
		$dDay = date_format(date_create($day),'D');
		switch ($dDay) {
			case "Mon":
				return "SENIN";
				break;
			case "Tue":
				return "SELASA";
				break;
			case "Wed":
				return "RABU";
				break;
			case "Thu":
				return "KAMIS";
				break;
			case "Fri":
				return "JUMAT";
				break;
			case "Sat":
				return "SABTU";
				break;
			case "Sun":
				return "MINGGU";
				break;
		}
	}
	
	public function actionUpdatePotongCuti(){
		$modelLokasi = Lokasi::model()->findAll(' status = 1 ');
		$modelCutiBersama = CutiBersama::model()->search()->getData();		
		foreach($modelLokasi as $loc){		
			$listWeekend = array();
			$listPotongCuti = array();
			
			/*List Akhir Pekan*/
			if( $loc->senin > -1 )	 	array_push($listWeekend,"SENIN");
			if( $loc->selasa > -1 ) 	array_push($listWeekend,"SELASA");
			if( $loc->rabu > -1 ) 		array_push($listWeekend,"RABU");
			if( $loc->kamis > -1 )		array_push($listWeekend,"KAMIS");
			if( $loc->jumat > -1 ) 		array_push($listWeekend,"JUMAT");
			if( $loc->sabtu > -1 ) 		array_push($listWeekend,"SABTU");
			if( $loc->minggu > -1 ) 	array_push($listWeekend,"MINGGU");
			
			/*List Cuti Bersama*/			
			foreach($modelCutiBersama as $cb){
				if( !in_array( $cb->hari, $listWeekend) )
					array_push($listPotongCuti, $cb->hari );
			}
			
			Yii::app()->db->createCommand(' update lokasi set potong_cuti_bersama = '.count($listPotongCuti).' where id = '.$loc->id )->execute();
			Yii::app()->db->createCommand(' update izin set jumlah_cuti_bersama = '.count($listPotongCuti).' where tahun = '.(int) date('Y').' and pegawai_id in (select id from pegawai where lokasi_id = '.$loc->id.') ' )->execute();
			unset($cb);			
		}			
	}
}
