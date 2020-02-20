<?php

class UserLdapController extends Controller
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
				'actions'=>array('admin','update','index','ask','sync','loadData','loadNama','hide','show'),
                'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level == 2 || Yii::app()->user->level == 3)',
			),	
			array('allow', 
				'actions'=>array('ask','sync','loadData','loadNama','hide','show'),
                'expression'=>'isset(Yii::app()->user->level) && Yii::app()->user->level == 1',
			),
			array('deny',  
				'users'=>array('*'),
			),
		);
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		if(isset($_POST['UserLdap']))
		{
			$model->attributes=$_POST['UserLdap'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function loadModel($id)
	{
		$model = UserLdap::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function actionIndex()
	{
		$this->actionAdmin();
	}

	public function actionAdmin()
	{
		$model=new UserLdap('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserLdap']))
			$model->attributes=$_GET['UserLdap'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
		
	public function actionAsk()
	{
		echo "<script>
			var r = confirm(\"Apakah anda ingin melakukan synchronize user LDAP associates ?\");
			if(r){
				window.location='".Yii::app()->request->baseUrl."/userLdap/sync';					
			}else{
				window.location='".Yii::app()->request->baseUrl."/userLdap';
			}		
			</script>";
	}
	
	public function actionSync(){
		$user 			= Yii::app()->user->uid;
		$pass 			= Yii::app()->user->pwd;		
		$ldap_host		= Yii::app()->params['ldap_host']; 
		$ldap_port      = Yii::app()->params['ldap_port']; 	
		$dn			    = Yii::app()->params['ldap_dn']; 				
		$ldap_dn        = 'uid='.$user.','.$dn;

		$adconn = ldap_connect( $ldap_host , $ldap_port );
		ldap_set_option ($adconn, LDAP_OPT_REFERRALS, 0); 
		ldap_set_option($adconn, LDAP_OPT_PROTOCOL_VERSION, 3) ;
		
		if($ldap_bind = @ldap_bind($adconn)){
			if($bind = @ldap_bind($adconn, $ldap_dn, $pass)) { 
				$attribute = array("uid","mail","displayname","co");
				if( Yii::app()->params['ldap_exclude'] != ''){
					$filter  = '(&(objectclass=inetOrgPerson)(uid=*)'.Yii::app()->params['ldap_exclude'].')'; 
				}else{
					$filter  = '(&(objectclass=inetOrgPerson)(uid=*))'; }
				$result = ldap_search($adconn, $dn, $filter, $attribute);
				$res = ldap_get_entries($adconn,$result);		
				$total_data = isset($res['count'])?$res['count']:0;
				
				foreach ($res as $f) {		
					if(isset($f['uid'])){
						$data[] = array('uid' => isset($f["uid"][0])?$f["uid"][0]:'',
								'mail' => isset($f["mail"][0])?$f["mail"][0]:'',
								'namaLengkap' => isset($f["displayname"][0])?$f["displayname"][0]:'',
								'status' => isset($f["co"][0])?$f["co"][0]:'active' );
					}
				}
				
				for( $i=0; $i<$total_data; $i++ ){
					$model = UserLdap::model()->findByPk($data[$i]['uid']);
					if($model === null){ 
						$model = new UserLdap; 
					}					
					$model->uid = $data[$i]['uid'];
					$model->email = $data[$i]['mail'];
					$model->nama = $data[$i]['namaLengkap'];
					$model->status = $data[$i]['status'];
					$model->save();
				}
				ldap_close($adconn);				
				Yii::app()->db->createCommand(" UPDATE atasan SET status=0 WHERE uid IN (SELECT uid FROM user_ldap WHERE status<>'active') ")->execute();
				Yii::app()->db->createCommand(" UPDATE user_admin SET status=0 WHERE uid IN (SELECT uid FROM user_ldap WHERE status<>'active') ")->execute();
				Yii::app()->db->createCommand(" UPDATE pegawai SET uid_atasan=null AND nama_atasan=null WHERE uid IN (SELECT uid FROM user_ldap WHERE status<>'active') ")->execute();
			}else{
				echo "Synchronize failed!";
			}		
		}else{
			echo "LDAP connection failed!";
		}
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}
	
	public function actionLoadData()
	{
		if (isset($_GET['term'])) {
			$criteria = new CDbCriteria;
			$criteria->select = array('uid');
			$criteria->addSearchCondition('uid', $_GET['term']);
			$criteria->limit = 10;
			$data = UserLdap::model()->findAll($criteria);			
			$arr = array();
			foreach ($data as $item) {
				array_push($arr, $item->uid); 
			}
			echo CJSON::encode($arr);
			Yii::app()->end();
		}
	}
	
	public function actionLoadNama()
	{
		if (isset($_GET['term'])) {
			$criteria = new CDbCriteria;
			$criteria->condition = 'status="active" and is_visible=1';
			$criteria->select = array('nama');
			$criteria->addSearchCondition('nama', $_GET['term']);			
			$criteria->limit = 10;
			$data = UserLdap::model()->findAll($criteria);			
			$arr = array();
			foreach ($data as $item) {
				array_push($arr, $item->nama); 
			}
			echo CJSON::encode($arr);
			Yii::app()->end();
		}
	}
	
	public function actionShow($uid)
	{
		$model=$this->loadModel($uid);
		if(isset($_GET['uid']))
		{
			$model->is_visible = 1;				
			if($model->save())	
				$this->redirect(array('userLdap/admin'));				
		}		
	}
	
	public function actionHide($uid)
	{
		$model=$this->loadModel($uid);
		if(isset($_GET['uid']))
		{
			$model->is_visible = 0;				
			if($model->save())	
				$this->redirect(array('userLdap/admin'));				
		}		
	}
}
