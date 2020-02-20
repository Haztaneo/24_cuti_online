<?php

class UserRoleController extends Controller
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
		$model=new UserRole;
		$permission = new UserPermission;
		$arrCek = array('');

		if(isset($_POST['UserRole']))
		{
			$model->attributes = $_POST['UserRole'];
			if($model->save()){
				for ($f=1; $f <= $_POST['counter']; $f++){		
					if(isset($_POST['cek_'.$f])){
						$rolepermission = new UserRolePermission;		
						$rolepermission->user_permission_id = $_POST['cek_'.$f];
						$rolepermission->user_role_id = $model->id;							
						$rolepermission->save();
					}						
				} 					
				$this->redirect(array('admin'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'permission'=>$permission,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$permissionmodel = new UserPermission('search');
		$arrCek = array();

		if(isset($_POST['UserRole']))
		{
			$model->attributes = $_POST['UserRole'];
			if($model->save()){
				Yii::app()->db->createCommand(' delete from user_role_permission where user_role_id = '.$model->id)->execute();
				for ($f=1; $f <= $_POST['counter']; $f++){		
					if(isset($_POST['cek_'.$f])){
						$rolepermission = new UserRolePermission;		
						$rolepermission->user_permission_id = $_POST['cek_'.$f];
						$rolepermission->user_role_id = $model->id;							
						$rolepermission->save();
					}								
				}
			} 					
			$this->redirect(array('admin'));
						
		}else{ 
			// $arrCek = array('odd','even');		
			$dataPermission = UserRolePermission::model()->findAll(" user_role_id = ".$id);				
			foreach($dataPermission as $dp){
				array_push($arrCek, CHtml::encode($dp->user_permission_id));
			}
		}	
		
		$this->render('update',array(
			'model'=>$model,
			'permission'=>$permissionmodel,
			'checked'=>$arrCek,
		));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionIndex()
	{
		/*$dataProvider=new CActiveDataProvider('UserRole');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
		$this->actionAdmin();
	}

	public function actionAdmin()
	{
		$model=new UserRole('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserRole']))
			$model->attributes=$_GET['UserRole'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=UserRole::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-role-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
