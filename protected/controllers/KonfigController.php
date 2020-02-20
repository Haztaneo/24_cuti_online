<?php

class KonfigController extends Controller
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
				'actions'=>array('update','admin','index'),
                'expression'=>'isset(Yii::app()->user->show_admin_page) && Yii::app()->user->show_admin_page == 1',
			),						
			array('deny', 
				  'actions'=>array('update','admin','index'),
				  'users'=>array('*'),
			),
		);
	}
	
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		if(isset($_POST['Konfig']))
		{
			$model->attributes=$_POST['Konfig'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionIndex()
	{
		$this->actionAdmin();
	}

	public function actionAdmin()
	{
		$model=new Konfig('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Konfig']))
			$model->attributes=$_GET['Konfig'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model = Konfig::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='konfig-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
