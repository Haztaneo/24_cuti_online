<?php

class AtasanController extends Controller
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
				'actions'=>array('create','update','admin','delete','index','loadNama'),
                'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level == 2 || Yii::app()->user->level == 3)',
			),	
			array('allow', 
				'actions'=>array('loadNama'),
                'expression'=>'isset(Yii::app()->user->level) && Yii::app()->user->level == 1',
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
		$model=new Atasan;

		if(isset($_POST['Atasan']))
		{
			$model->attributes=$_POST['Atasan'];
			$data = UserLdap::model()->find(array('condition'=>'nama="'.$model->nama.'"'));
			if($data){
				$model->uid = $data->uid;
				$model->email = $data->email;
			}
				
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Atasan']))
		{
			$model->attributes=$_POST['Atasan'];
			$data = UserLdap::model()->find(array('condition'=>'nama="'.$model->nama.'"'));
			if($data){
				$model->uid = $data->uid;
				$model->email = $data->email;
			}
			
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		try{
		   $this->loadModel($id)->delete();
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
		$model=new Atasan('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Atasan']))
			$model->attributes=$_GET['Atasan'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Atasan::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='atasan-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionLoadNama()
	{
		if (isset($_GET['term'])) {
			$criteria = new CDbCriteria;
			$criteria->select = array('nama');
			$criteria->addSearchCondition('nama', $_GET['term']);			
			$criteria->limit = 5;
			$data = Atasan::model()->findAll($criteria);			
			$arr = array();
			foreach ($data as $item) {
				array_push($arr, $item->nama); 
			}
			echo CJSON::encode($arr);
			Yii::app()->end();
		}
	}
}
