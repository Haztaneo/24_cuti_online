<?php

class UserPermissionController extends Controller
{
	public $layout='//layouts/column2';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function accessRules()
	{
		return array(			
			array('allow', 
				'actions'=>array('create','update','admin','delete','index'),
                'expression'=>' isset(Yii::app()->user->level) && (Yii::app()->user->show_admin_page == 1) ',
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
		$model=new UserPermission;

		if(isset($_POST['UserPermission']))
		{
			$model->attributes=$_POST['UserPermission'];
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

		if(isset($_POST['UserPermission']))
		{
			$model->attributes=$_POST['UserPermission'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionIndex()
	{
		$this->actionAdmin();
	}

	public function actionAdmin()
	{
		$model=new UserPermission('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserPermission']))
			$model->attributes=$_GET['UserPermission'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=UserPermission::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-permission-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
