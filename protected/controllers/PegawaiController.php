<?php
class PegawaiController extends Controller
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
				'actions'=>array('create','update','admin','delete','index','loadData','member'),
                'expression'=>'isset(Yii::app()->user->level) && Yii::app()->user->level != 0',
			),		
			array('allow', 
				'actions'=>array('updateDOJ'),
				'users'=>array('*'),
			),		
			array('deny',  
				'users'=>array('*'),
			),
		);
	}

	public function actionCreate()
	{
		$model = new Pegawai;
		$model->sex = 'L';
		$model->agama_id = 1;

		if(isset($_POST['Pegawai']))
		{
			$model->attributes = $_POST['Pegawai'];
			$potong_cuti_bersama = 0;
			
			if( !empty($model->uid) ){
				$dataLDAP = UserLdap::model()->find(' uid = "'.$model->uid.'"');
				$model->email = $dataLDAP->email; 
				// $model->uid = str_replace(Yii::app()->params['ldap_domain'],"",$model->uid);
			}
			
			if( !empty($model->nama_atasan) ){
				$data = UserLdap::model()->find( array('condition'=>'nama="'.$model->nama_atasan.'"') );
				if($data){
					$model->uid_atasan = $data->uid;
					$model->nama_atasan = $data->nama;
				}
			}else{
				$model->uid_atasan = null;
				$model->nama_atasan = null;
			}
			
			if( !empty($model->lokasi_id) ){
				$dataLokasi = Lokasi::model()->findByPk($model->lokasi_id);
				$potong_cuti_bersama = $dataLokasi->potong_cuti_bersama; 
			}
			
			if( !empty($model->doj) ){ 
				$dataDOJ = $this->actionCheckDOJ( $model->doj, $potong_cuti_bersama ) ;
				$model->is_doj_full = $dataDOJ['is_doj_full'];	
				$model->sisa_cuti = $dataDOJ['sisa_cuti'];
			}
			
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
			'flag'=> 0, 
		));
	}

	public function actionUpdate($id)
	{		
		$model = $this->loadModel($id);
		$kfg = Konfig::model()->findByPk(2);
		$modelIzin = Izin::model()->findAll(array("condition"=>"uid='".$model->uid."'"));
		if( count($modelIzin)==0 ){ //0=New,1=exist
			$flag = 0;
		}else{
			$flag = 1;
		}
		
		if(isset($_POST['Pegawai']))
		{
			$model->attributes = $_POST['Pegawai'];
			
			if( !empty($model->uid) ){
				$dataLDAP = UserLdap::model()->find(' uid = "'.$model->uid.'"');
				$model->email = $dataLDAP->email; 
			}else{
				$model->uid = null;
				$model->email = null; 
			}
			
			if( !empty($model->nama_atasan) ){
				$data = UserLdap::model()->find(array('condition'=>'nama="'.$model->nama_atasan.'"'));
				if($data){
					$model->uid_atasan = $data->uid;
					$model->nama_atasan = $data->nama;
				}
			}else{
				$model->uid_atasan = null;
				$model->nama_atasan = null;
			}
						
			if( $flag == 0 ){
				if( !empty($model->doj) ){ 
					$dataDOJ = $this->actionCheckDOJ( $model->doj, $model->lokasi->potong_cuti_bersama ) ;
					$model->is_doj_full = $dataDOJ['is_doj_full'];	
					$model->sisa_cuti = $dataDOJ['sisa_cuti'];
				}
			}
			
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
			'flag'=>$flag, 
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

	private function actionCheckDOJ( $doj, $potongCuti )  
	{	
		$kfg = Konfig::model()->findByPk(2);
		$tgl = date('Y').'-'.date_format( date_create($doj), 'm-d' ); //date("Y-m-d",strtotime("+1 years"))	
		$dojTgl = date_format( date_create($tgl), 'Y-m-d' );
		$dojTahun = date_format( date_create($doj), 'Y' );		
			
		if( (date('Y') - $dojTahun) > 1 ){ 	
			$is_doj_full = 1 ; 	
			$sisa_cuti = $kfg->jumlah_cuti_setahun; 
		}elseif( ( date('Y-m-d') > $dojTgl ) && ( date('Y') - $dojTahun )==1 ){ 	 
			$is_doj_full = 1 ; 	
			$dojDay = date_format( date_create($doj), 'd' );
			$dojMonth = date_format( date_create($doj), 'm' );					
			if( $dojDay < $kfg->tgl_min_doj ){ 
				$dojMonth -= 1; 
			}
				
			$totCuti = $kfg->jumlah_cuti_setahun - $dojMonth;	
			if( $totCuti > $potongCuti ){ 
				$sisa_cuti = $totCuti;
			}else{
				$sisa_cuti = 0;
			}	
		}else{  
			$is_doj_full = 0 ;
			$sisa_cuti = 0; 	
		}
		return compact("is_doj_full", "sisa_cuti");	
	}
		
	public function actionUpdateDOJ()  //otomatis update
	{	
		$kfg = Konfig::model()->findByPk(2); 		
		if ( date('Y') >  $kfg->periode ){ //reset awal tahun
			Yii::app()->db->createCommand(' update pegawai set sisa_cuti = ('.$kfg->jumlah_cuti_setahun.' - pending_cuti) where is_doj_full = 1 ' )->execute();	
			Yii::app()->db->createCommand(' update pegawai set sisa_cuti = (sisa_cuti - pending_cuti) where is_doj_full = 0 ' )->execute();	
			
			/*Start Memenuhi doj*/
			$fullDOJ = Pegawai::model()->findAll(' is_doj_full = 0 and date(now()) > date_add(doj, interval 1 year) ');	
			if( count($fullDOJ ) > 0 ){		
				foreach($fullDOJ as $fDOJ){
					$dojDay = date_format( date_create($fDOJ->doj), 'd' );
					$dojMonth = date_format( date_create($fDOJ->doj), 'm' );					
					if( $dojDay < $kfg->tgl_min_doj ){ 
						$dojMonth -= 1; 
					}
						
					$totCuti = $kfg->jumlah_cuti_setahun - $dojMonth;	
					if( $totCuti > ($fDOJ->lokasi->potong_cuti_bersama) ){ 
						$sisa_cuti = $totCuti;
					}else{
						$sisa_cuti = 0;
					}	
					
					Yii::app()->db->createCommand(' update pegawai set is_doj_full = 1, sisa_cuti = '.$sisa_cuti.' where uid = "'.$fDOJ->uid.'"' )->execute();		
				}
			} /*End Memenuhi doj*/
			
			Yii::app()->db->createCommand(' update pegawai set pending_cuti = 0 ' )->execute();			
			Yii::app()->db->createCommand(' update lokasi set potong_cuti_bersama = 0  ' )->execute();
			Yii::app()->db->createCommand(' update konfig set periode = '.date('Y') )->execute();
		}else{ //in this year
			/*Start Memenuhi doj*/
			$fullDOJ = Pegawai::model()->findAll(' is_doj_full = 0 and date(now()) > date_add(doj, interval 1 year) ');	
			if( count($fullDOJ ) > 0 ){		
				foreach($fullDOJ as $fDOJ){
					$dojDay = date_format( date_create($fDOJ->doj), 'd' );
					$dojMonth = date_format( date_create($fDOJ->doj), 'm' );					
					if( $dojDay < $kfg->tgl_min_doj ){ 
						$dojMonth -= 1; 
					}
						
					$totCuti = ($kfg->jumlah_cuti_setahun - $dojMonth) - ($kfg->pending_cuti);	
					if( $totCuti > ($fDOJ->lokasi->potong_cuti_bersama) ){ 
						$sisa_cuti = $totCuti;
					}else{
						$sisa_cuti = 0;
					}	
					
					Yii::app()->db->createCommand(' update pegawai set is_doj_full = 1, pending_cuti = 0, sisa_cuti = '.$sisa_cuti.' where uid = "'.$fDOJ->uid.'"' )->execute();		
				}
			} /*End Memenuhi doj*/
		}		
	}
	
	public function actionIndex()
	{
		$this->actionAdmin();
	}

	public function actionAdmin()
	{
		$model=new Pegawai('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pegawai']))
			$model->attributes=$_GET['Pegawai'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function actionMember()
	{
		$model = new Pegawai('searchMember');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pegawai']))
			$model->attributes=$_GET['Pegawai'];

		$this->render('member',array(
			'model'=>$model,
		));
	}
	
	public function loadModel($id)
	{
		$model=Pegawai::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pegawai-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
		
	public function actionLoadData()
	{
		if (isset($_GET['term'])) {
			$criteria = new CDbCriteria;
			$criteria->select = array('nama_lengkap');
			$criteria->addSearchCondition('nama_lengkap', $_GET['term']);
			$criteria->limit = 10;
			$data = Pegawai::model()->findAll($criteria);			
			$arr = array();
			foreach ($data as $item) {
				array_push($arr, $item->nama_lengkap); 
			}
			echo CJSON::encode($arr);
			Yii::app()->end();
		}
	}
}
