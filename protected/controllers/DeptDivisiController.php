<?php

class DeptDivisiController extends Controller
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
				'actions'=>array('create','update','admin','delete','index'),
                'expression'=>'isset(Yii::app()->user->show_admin_page) && Yii::app()->user->show_admin_page == 1',
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
		$model=new DeptDivisi;

		if(isset($_POST['DeptDivisi']))
		{
			$model->attributes=$_POST['DeptDivisi'];
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
		
		if(isset($_POST['DeptDivisi']))
		{
			$model->attributes=$_POST['DeptDivisi'];
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
		$model=new DeptDivisi('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['DeptDivisi']))
			$model->attributes=$_GET['DeptDivisi'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=DeptDivisi::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='dept-divisi-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
