<?php

class AgamaController extends Controller
{
	public $layout='//layouts/column2';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			// array('ext.YiiBooster.filters.BoosterFilter - delete'),
		);
	}

	public function accessRules()
	{
		return array(			
			array('allow', 
				'actions'=>array('create','update','admin','delete','index'),
                'expression'=>'isset(Yii::app()->user->show_admin_page) && Yii::app()->user->show_admin_page == 1',
			),						
			array('deny', 
				'actions'=>array('create','update','admin','delete','index'),
				'users'=>array('*'),
			),
		);
	}

	public function actionView()
	{
		$this->layout='//layouts/mainDark';
		$this->render('index');
	}

	public function actionCreate()
	{
		$model=new Agama;

		if(isset($_POST['Agama']))
		{
			$model->attributes=$_POST['Agama'];
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

		if(isset($_POST['Agama']))
		{
			$model->attributes=$_POST['Agama'];
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
		$model=new Agama('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Agama']))
			$model->attributes=$_GET['Agama'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Agama::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='agama-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
