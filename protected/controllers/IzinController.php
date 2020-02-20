<?php

class IzinController extends Controller
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
				'actions'=>array( 'input', 'create', 'update', 'admin', 'delete', 'index', 'cnc', 'view', 'request', 'requestMember', 'getAjaxTipeOptions' ),
                'expression'=>'isset(Yii::app()->user->level) ',
			),	
			array('deny',  
				'users'=>array('*'),
			),
		);
	}

	public function actionInput()
	{		
		$model = new Izin;	
		date_default_timezone_set("Asia/Jakarta");
		$model->tipe_izin_id = 1 ;
		$model->jumlah_hari = 0;
		$listWeekend = array();
		$listCB = array();
		
		//0=New Tidak potong cuti,1=New Potong Cuti,2=cancel
		if(isset($_POST['Izin']))
		{	
			$model->attributes = $_POST['Izin'];
			
			/*START : Validate exist in DB (tgl mulai & tgl akhir cuti )*/
			$mIzin = Izin::model()->find( array('select'=>'kode',
						'condition'=>' uid="'.Yii::app()->user->id.'" and tgl_mulai="'.$model->tgl_mulai.'" and tgl_akhir="'.$model->tgl_akhir.'" and status in(0,1) ' ) );
						
			if( count($mIzin)> 0 ){ 
				Yii::app()->user->setFlash('notice', "<strong>Pengajuan izin & cuti anda sudah tersimpan atau masih dalam proses !!</strong>");
			}else{
			
				/*Start Inisialisasi Formulir*/
				$modelPegawai = Pegawai::model()->find('nama_lengkap="'.$model->pegawai_nama_lengkap.'"');
				$mTipe = TipeIzin::model()->findByPk($model->tipe_izin_id);
				$mKonfig = Konfig::model()->findByPk(2);
				$totCuti = $mKonfig->jumlah_cuti_setahun;
				
				$model->tahun = date("Y", strtotime($model->tgl_pengajuan));
				$model->bulan = date("m", strtotime($model->tgl_pengajuan));
				$model->kode = "CK-".$modelPegawai->deptDivisi->kode."/".date("ym/", strtotime($model->tgl_pengajuan)).sprintf("%04s%03s", $modelPegawai->id, ($modelPegawai->ctl)+1); 
				$model->pegawai_id = $modelPegawai->id;
				$model->uid = $modelPegawai->uid;
				$model->pegawai_nama_lengkap = $modelPegawai->nama_lengkap;
				$model->dept_divisi_nama = $modelPegawai->deptDivisi->nama;
				$model->sisa_cuti = $modelPegawai->sisa_cuti;
				$model->jumlah_cuti_bersama = $modelPegawai->lokasi->potong_cuti_bersama;
				
				/*Data login*/
				$modelLogin = UserLdap::model()->find('uid="'.Yii::app()->user->id.'"');
				$modelPegawaiLogin = Pegawai::model()->find('uid="'.Yii::app()->user->id.'"');
				$model->approval_note = "DISETUJUI [ Manual Input By ".$modelLogin->nama." ]";
				$atasan = Pegawai::model()->find('uid="'.$modelPegawai->uid_atasan.'"');
				$model->disetujui_id = $atasan->id;					
				$model->disetujui_nama = $atasan->nama_lengkap;	
				$model->disetujui_tgl = date('Y-m-d H:i:s');	
				
				$model->diketahui_id = $modelPegawaiLogin->id<>null?$modelPegawaiLogin->id:null;
				$model->diketahui_nama = !empty($modelPegawaiLogin->nama_lengkap)?$modelPegawaiLogin->nama_lengkap:$modelLogin->nama;
				$model->diketahui_tgl = date('Y-m-d H:i:s');
				
				$model->status_proses = 2;		
				$model->status = 1;		
				$model->created_by = $modelLogin->nama;					
				$model->created_date = date('Y-m-d H:i:s');	
				
				/*List Akhir Pekan*/
				$mLokasi = Lokasi::model()->findByPk( $modelPegawai->lokasi_id );	
				if( $mLokasi->senin > -1 ) array_push($listWeekend,$mLokasi->senin);
				if( $mLokasi->selasa > -1 ) array_push($listWeekend,$mLokasi->selasa);
				if( $mLokasi->rabu > -1 ) array_push($listWeekend,$mLokasi->rabu);
				if( $mLokasi->kamis > -1 ) array_push($listWeekend,$mLokasi->kamis);
				if( $mLokasi->jumat > -1 ) array_push($listWeekend,$mLokasi->jumat);
				if( $mLokasi->sabtu > -1 ) array_push($listWeekend,$mLokasi->sabtu);
				if( $mLokasi->minggu > -1 ) array_push($listWeekend,$mLokasi->minggu);
				
				/*List Cuti Bersama*/
				$modelCuti = CutiBersama::model()->search()->getData();
				foreach($modelCuti as $cb){
					array_push($listCB, date("m/d/Y", strtotime($cb->tgl)) );
				}
					
				// if( $_POST['is_must_attach']==1 ) $model->scenario = 'attach';
				/*End Inisialisasi Formulir*/

				if($mTipe->is_potong_cuti==1){ //Potong cuti				
					/* Cek Cuti This year/next year */
					$flag = 1;					
					if( (date("Y",strtotime($model->tgl_mulai)) == date("Y")) && (date("Y",strtotime($model->tgl_akhir)) == date("Y")) ){	// Cuti Tahun Berjalan
						/* if( $modelPegawai->pending_cuti > 0 ){	//handle by auto update DOJ
							$modelPegawai->sisa_cuti = ($totCuti - $modelPegawai->pending_cuti);							
							$modelPegawai->pending_cuti = 0 ;							
						} */	
						$tgl = date('Y').'-'.date_format( date_create($modelPegawai->doj), 'm-d' ); 
						$dojTgl = date_format( date_create($tgl), 'Y-m-d' );
						// $dojTahun = date_format( date_create($doj), 'Y' ); // ( date('Y') - $dojTahun )==1  	
						if( $modelPegawai->is_doj_full==0 && ( $model->tgl_mulai > $dojTgl ) ){	
							$modelPegawai->pending_cuti += $model->jumlah_hari; 
							$flag = 2;
						}		
					}else{	// Cuti Tahun Depan
						if( $modelPegawai->pending_cuti == 0 ){
							$modelPegawai->pending_cuti = $model->jumlah_hari ;
						}else{
							$modelIzin = Yii::app()->db->createCommand(" SELECT uid,sum(jumlah_hari)as total FROM izin WHERE tahun > ".(int)date("Y")." and uid='".Yii::app()->user->id."' and status in (0,1) group by uid ")->queryAll();
							$totalCuti = isset($modelIzin[0]['total']) ? $modelIzin[0]['total'] : 0 ;
							$modelPegawai->pending_cuti = ($totalCuti + $model->jumlah_hari);
						}	
					}						
					/*END Cek Cuti This year/next year*/
					
					/*Maksimal Ambil Cuti*/
					if( $modelPegawai->max_cuti > 0){
						$maxCuti = $modelPegawai->max_cuti;				
					}else{ 
						$maxCuti = Konfig::model()->findByPk(2)->max_ambil_cuti; 					
					}
					 
					if( $model->jumlah_hari > $maxCuti ){ 
						Yii::app()->user->setFlash('notice', "<strong>Maksimal pengambilan cuti : ".$maxCuti." hari !!</strong>");
					}else{								
						if(@!empty($_FILES['Izin']['name']['attach_dokumen'])) {					
							$rnd = rand(2,9999);
							$path = Yii::app()->params['upload_path'] .'/dokumen/';
							$uploadFile = CUploadedFile::getInstance($model,'attach_dokumen');
							$fileName = "{$rnd}-{$uploadFile}";
							$model->attach_dokumen = $fileName;
						}
						
						if($model->save()){	
							$modelPegawai->save(); 
							if( $flag == 1 ){
								$this->updateSisaCuti($model->pegawai_id , $model->jumlah_hari, 1);
							}elseif( $flag == 2 ){
								$this->updateSisaCuti($model->pegawai_id , 0, 1);
							}
							
							if( $_POST['is_must_attach']==1 ){
								$uploadFile->saveAs($path .$model->attach_dokumen);
								Yii::import('application.extensions.image.Image');
								$image = new Image($path .$model->attach_dokumen);
								$image->save();
							}
													
							if(isset($_POST['submit'])){
								$this->redirect(array('request'));
							}elseif(isset($_POST['submitNew'])){
								$this->redirect(array('input'));
							}
						}
					 }
				 }else{ //tidak memotong cuti
					if(@!empty($_FILES['Izin']['name']['attach_dokumen'])) {					
						$rnd = rand(2,9999);
						$path = Yii::app()->params['upload_path'] .'/dokumen/';
						$uploadFile = CUploadedFile::getInstance($model,'attach_dokumen');
						$fileName = "{$rnd}-{$uploadFile}";
						$model->attach_dokumen = $fileName;
					}
									
					if($model->save()){
						$this->updateSisaCuti($model->pegawai_id ,$model->jumlah_hari, 0);
						if( $_POST['is_must_attach']==1 ){
							$uploadFile->saveAs($path .$model->attach_dokumen);
							Yii::import('application.extensions.image.Image');
							$image = new Image($path .$model->attach_dokumen);
							$image->save();
						}
						
						if(isset($_POST['submit'])){
							$this->redirect(array('request'));
						}elseif(isset($_POST['submitNew'])){
							$this->redirect(array('input'));
						}
					}
				 }
			}/*END : Validate exist in DB*/
		}

		$this->render('input',array(
			'model'=>$model,
			'listWeekend'=>$listWeekend,
			'listCB'=>$listCB,
		));
	}
	
	public function actionCreate()
	{
		$model = new Izin;	
		date_default_timezone_set("Asia/Jakarta");
		$model->tahun = date("Y");
		$model->bulan = date("m");		
		$model->tgl_pengajuan = date("Y-m-d");		
		$model->tipe_izin_id = 1 ;
		$model->jumlah_hari = 0;
		$listWeekend = array();
		$listCB = array();
		
		/*Start Inisialisasi Formulir*/
		$modelPegawai = Pegawai::model()->find('uid="'.Yii::app()->user->id.'"');
		if( count($modelPegawai) > 0 ){
			$mDept = DeptDivisi::model()->findByPk($modelPegawai->dept_divisi_id);
			$model->kode = "CK-".$modelPegawai->deptDivisi->kode."/".date("ym/").sprintf("%04s%03s", $modelPegawai->id, ($modelPegawai->ctl)+1); 
			$model->pegawai_id = $modelPegawai->id;
			$model->uid = $modelPegawai->uid;
			$model->pegawai_nama_lengkap = $modelPegawai->nama_lengkap;
			$model->dept_divisi_nama = $modelPegawai->deptDivisi->nama;
			$model->sisa_cuti = $modelPegawai->sisa_cuti;
			
			/*List Akhir Pekan*/
			$mLokasi = Lokasi::model()->findByPk( $modelPegawai->lokasi_id );	
			if( $mLokasi->senin > -1 ) array_push($listWeekend,$mLokasi->senin);
			if( $mLokasi->selasa > -1 ) array_push($listWeekend,$mLokasi->selasa);
			if( $mLokasi->rabu > -1 ) array_push($listWeekend,$mLokasi->rabu);
			if( $mLokasi->kamis > -1 ) array_push($listWeekend,$mLokasi->kamis);
			if( $mLokasi->jumat > -1 ) array_push($listWeekend,$mLokasi->jumat);
			if( $mLokasi->sabtu > -1 ) array_push($listWeekend,$mLokasi->sabtu);
			if( $mLokasi->minggu > -1 ) array_push($listWeekend,$mLokasi->minggu);
			
			/*List Cuti Bersama*/
			$modelCuti = CutiBersama::model()->search()->getData();
			foreach($modelCuti as $cb){
				array_push($listCB, date("m/d/Y", strtotime($cb->tgl)) );
			}
		}			
		/*End Inisialisasi Formulir*/
		
		//0=New Tidak potong cuti,1=New Potong Cuti,2=cancel
		if(isset($_POST['Izin']))
		{	
			$model->attributes = $_POST['Izin'];
			
			/*cek validate input tgl_mulai & tgl_akhir exist in DB*/
			$mIzin = Izin::model()->find( array('select'=>'kode',
						'condition'=>' uid="'.Yii::app()->user->id.'" and tgl_mulai="'.$model->tgl_mulai.'" and tgl_akhir="'.$model->tgl_akhir.'" and status in(0,1) ' ) );
						
			if( count($mIzin)> 0 ){ 
				Yii::app()->user->setFlash('notice', "<strong>Pengajuan izin & cuti anda sudah tersimpan atau masih dalam proses !!</strong>");
			}else{
				if( $_POST['is_must_attach']==1 ) $model->scenario = 'attach';
				$mTipe = TipeIzin::model()->findByPk($model->tipe_izin_id);
				$mKonfig = Konfig::model()->findByPk(2);
				$totCuti = $mKonfig->jumlah_cuti_setahun;
				$model->jumlah_cuti_bersama = $modelPegawai->lokasi->potong_cuti_bersama;
				$model->created_by = Yii::app()->user->id;					
				$model->created_date = date('Y-m-d H:i:s');	
				
				if($mTipe->is_potong_cuti==1){ /*START : Memotong cuti*/			
					/* Cek Cuti This year/next year */
					$flag = 1;						
					if( (date("Y",strtotime($model->tgl_mulai)) == date("Y")) && (date("Y",strtotime($model->tgl_akhir)) == date("Y")) ){
						$tgl = date('Y').'-'.date_format( date_create($modelPegawai->doj), 'm-d' ); 
						$dojTgl = date_format( date_create($tgl), 'Y-m-d' );
						if( $modelPegawai->is_doj_full==0 && ( $model->tgl_mulai > $dojTgl ) ){	
							$modelPegawai->pending_cuti += $model->jumlah_hari; 
							$flag = 2;
						}	
					}else{
						if( $modelPegawai->pending_cuti == 0 ){
							$modelPegawai->pending_cuti = $model->jumlah_hari;
						}else{
							$modelIzin = Yii::app()->db->createCommand(" SELECT uid,sum(jumlah_hari)as total FROM izin WHERE tahun > ".(int)date("Y")." and uid='".Yii::app()->user->id."' and status in (0,1) group by uid ")->queryAll();
							$totalCuti = isset($modelIzin[0]['total']) ? $modelIzin[0]['total'] : 0 ;
							$modelPegawai->pending_cuti = $totalCuti + $model->jumlah_hari;
						}	
					}						
					/*END Cek Cuti This year/next year*/
							
					 /*Maksimal Ambil Cuti*/
					 if( $modelPegawai->max_cuti > 0){
						$maxCuti = $modelPegawai->max_cuti;				
					 }else{ 
						$maxCuti = Konfig::model()->findByPk(2)->max_ambil_cuti; 					
					 }
					 
					 if( $model->jumlah_hari > $maxCuti ){ 
						Yii::app()->user->setFlash('notice', "<strong>Maksimal pengambilan cuti : ".$maxCuti." hari !!</strong>");
					/* }elseif( ($modelPegawai->sisa_cuti - $model->jumlah_hari) < 0 ){
						Yii::app()->user->setFlash('notice', "<strong>Sisa cuti yang dimiliki : ".$modelPegawai->sisa_cuti." hari  !!</strong>"); */
					 }else{								
						if(@!empty($_FILES['Izin']['name']['attach_dokumen'])) {					
							$rnd = rand(2,9999);
							$path = Yii::app()->params['upload_path'] .'/dokumen/';
							$uploadFile = CUploadedFile::getInstance($model,'attach_dokumen');
							$fileName = "{$rnd}-{$uploadFile}";
							$model->attach_dokumen = $fileName;
						}
						
						if($model->save()){	
							$modelPegawai->save(); 
							if( $flag == 1 ){
								$this->updateSisaCuti($model->pegawai_id , $model->jumlah_hari, 1);
							}elseif( $flag == 2 ){
								$this->updateSisaCuti($model->pegawai_id , 0, 1);
							}
							
							if( $_POST['is_must_attach']==1 )							
							{
								// $image = Yii::app()->image->load($path .$model->attach_dokumen);
								$uploadFile->saveAs($path .$model->attach_dokumen);
								Yii::import('application.extensions.image.Image');
								$image = new Image($path .$model->attach_dokumen);
								$image->save();
							}
													
							if($this->sendEmail( $model->id, 0 ) == 1 ){
								Yii::app()->user->setFlash('info', "<strong>Email pengajuan izin / cuti anda berhasil dikirimkan </strong>");
							}
							$this->redirect(array('admin'));
						}
					 }
				 /*END : Memotong cuti*/
				 }else{ /*START : tidak memotong cuti*/
					if(@!empty($_FILES['Izin']['name']['attach_dokumen'])) {					
						$rnd = rand(2,9999);
						$path = Yii::app()->params['upload_path'] .'/dokumen/';
						$uploadFile = CUploadedFile::getInstance($model,'attach_dokumen');
						$fileName = "{$rnd}-{$uploadFile}";
						$model->attach_dokumen = $fileName;
					}
						
					if($model->save()){
						$this->updateSisaCuti($model->pegawai_id ,$model->jumlah_hari, 0);
						if( $_POST['is_must_attach']==1 )	
						{
							$uploadFile->saveAs($path .$model->attach_dokumen);
							Yii::import('application.extensions.image.Image');
							$image = new Image($path .$model->attach_dokumen);
							$image->save();
						}
						
						if( $this->sendEmail( $model->id, 0 ) == 1 ){
								Yii::app()->user->setFlash('info', "<strong>Email pengajuan izin / cuti anda berhasil dikirimkan </strong>");
						}
						$this->redirect(array('admin'));
					}
				 } /*END : tidak memotong cuti*/	
			}			
		}

		$this->render('create',array(
			'model'=>$model,
			'listWeekend'=>$listWeekend,
			'listCB'=>$listCB,
		));
	}
	
	private function sendEmail( $id, $flag )
    {		
		//0=pegawai,1=leader,2=HR,3=Batal
		$model = Izin::model()->findByPk($id);
		$modelPegawai = Pegawai::model()->findByPk($model->pegawai_id);
		$modelHR = UserAdmin::model()->findAll(array(
						  'select'=>'email',
						  'condition'=>'is_receive_email=1 and status=1'
						 ));     
		
		$headers = 'From: K24 CUTI ONLINE - DO NOT REPLY <noreply@k24.co.id>' . "\r\n";
		if( $flag == 0 ){
			$kepada = $modelPegawai->nama_atasan;
			$mailTo = $modelPegawai->uid_atasan.Yii::app()->params['ldap_domain'];
			$subject = '[K24 - Cuti Online] Permohonan Izin / Cuti Baru' ;	
			foreach($modelHR as $hr){
				$headers .= 'Cc: '.$hr->email.'' . "\r\n"; 
			}
			$headers .= 'Bcc: '.$model->uid.Yii::app()->params['ldap_domain'].'' . "\r\n"; 
			$pesan = "Berikut Adalah Data Associate Yang Mengajukan Permohonan Izin / Cuti.";
		}elseif( $flag == 1 ){
		    $kepada = $modelPegawai->nama_lengkap;
			$mailTo = $model->uid.Yii::app()->params['ldap_domain'];
			$subject = '[K24 - Cuti Online] Konfirmasi Persetujuan Atasan' ;	
			foreach($modelHR as $hr){
				$headers .= 'Cc: '.$hr->email.'' . "\r\n"; 
			}
			$pesan = "Atasan anda menyetujui Permohonan Izin / Cuti anda.";
		}elseif( $flag == 2 ){
			$kepada = $modelPegawai->nama_lengkap;
			$mailTo = $model->uid.Yii::app()->params['ldap_domain'];
			$subject = '[K24 - Cuti Online] Konfirmasi Persetujuan HR' ;	
			$headers .= 'Cc: '.$modelPegawai->uid_atasan.Yii::app()->params['ldap_domain'].'' . "\r\n"; 
			$pesan = "HR menyetujui Permohonan Izin / Cuti anda.";
		}elseif( $flag == 3 ){
		    $kepada = $modelPegawai->nama_lengkap;
			$mailTo = $model->uid.Yii::app()->params['ldap_domain'];
			$subject = '[K24 - Cuti Online] Pembatalan Permohonan Izin-Cuti' ;	
			$headers .= 'Cc: '.$modelPegawai->uid_atasan.Yii::app()->params['ldap_domain'].'' . "\r\n";
			foreach($modelHR as $hr){
				$headers .= 'Cc: '.$hr->email.'' . "\r\n"; 
			}
			$pesan = "Permohonan Izin / Cuti anda telah dibatalkan.";
		}
		
		$message = $this->renderPartial('sendEmail', 
					 array( 'kepada' => $kepada,
							'nama_atasan' => $modelPegawai->uid_atasan,
							'dataIzin' => $model,
							'pesan' => $pesan, ), true);	
		$headers .= 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		if (mail($mailTo, $subject, $message, $headers)){
			return 1;
		}else{
			return 2;                    
		}
	} 
	
	private function updateSisaCuti( $id, $sumDay ,$flag )
    {
		$model = Pegawai::model()->findByPk($id);
		//0=New Tidak potong cuti,1=New Potong Cuti,2=cancel Potong Cuti,3=cancel Tidak potong cuti
		if( $flag==0 ){
			$model->ctl += 1;
		}elseif( $flag==1 ){
			$model->sisa_cuti -= $sumDay;
			$model->ctl += 1;
		}elseif( $flag==2 ){
			$model->sisa_cuti += $sumDay;
		}elseif( $flag==3 ){
			$model->sisa_cuti = $model->sisa_cuti;
		}			
		$model->save();
	} 
	
	private function convertDay($day){
		switch ($day) {
			case "Mon":
				return '1';
				break;
			case "Tue":
				return '2';
				break;
			case "Wed":
				return '3';
				break;
			case "Thu":
				return '4';
				break;
			case "Fri":
				return '5';
				break;
			case "Sat":
				return '6';
				break;
			case "Sun":
				return '0';
				break;
		}
	}
	
	public function actionView($id)
	{
		//Proses cancel dan terima 
		$model = $this->loadModel($id);			
		$atasan = Atasan::model()->find('uid = "'.Yii::app()->user->id.'"' );
		$level = 0;
		if( Yii::app()->user->level > 1 && count($atasan)>0 ){ 
			$level = 3; //leader + HR
		}elseif( Yii::app()->user->level == 1 ){ 
			$level = 2; //leader
		}elseif( Yii::app()->user->level > 1 && count($atasan)==0 ){ 
			$level = 1; //HR
		}						
			
		$mPegawai = Pegawai::model()->find('uid="'.Yii::app()->user->id.'"'); 		
		//Proses as HR	
		if(isset($_POST['btnKnow'])){			
			$model->diketahui_id = $mPegawai->id;
			$model->diketahui_nama = $mPegawai->nama_lengkap;
			$model->diketahui_tgl = date('Y-m-d H:i:s');
			$model->status_proses = 2; //done on hrd	
			$model->status = 1; //is_approve
									
			if($model->save()){
				if( Yii::app()->user->level<>0 ){
					if($this->sendEmail( $id, 2 ) == 1){
						Yii::app()->user->setFlash('info', "<strong>Email konfirmasi persetujuan pengajuan izin / cuti berhasil dikirimkan </strong>");
					}
					$this->redirect(array('request'));
				}else{
					$this->redirect(array('admin'));
				}
			}		
		}		
		
		//Proses as Leader + HR
		if(isset($_POST['Izin']['approval_note']) || isset($_POST['Izin']['cancel_note']))
		{
			date_default_timezone_set("Asia/Jakarta");
			if( $_POST['flag'] == 1 ){
				$model->scenario = 'approval';
				if( trim($_POST['Izin']['approval_note']) != "" ){
					$model->approval_note = trim($_POST['Izin']['approval_note']);
					$model->disetujui_id = $mPegawai->id;
					$model->disetujui_nama = $mPegawai->nama_lengkap;
					$model->disetujui_tgl = date('Y-m-d H:i:s');
					if( $level == 3 ){
						    $model->diketahui_id = $mPegawai->id;
							$model->diketahui_nama = $mPegawai->nama_lengkap;
							$model->diketahui_tgl = date('Y-m-d H:i:s');
							$model->status_proses = 2; //done on HR	
							$model->status = 1; //is_approve
					}elseif( $level == 2 ){ //leader
						$model->status_proses = 1; //on leader	
					}
				}else{
					$model->approval_note= null;
				}
			}elseif ( $_POST['flag'] == 2 ){
				$model->scenario = 'cnc';
				if( trim($_POST['Izin']['cancel_note'])!="" ){					
					$model->cancel_note = trim($_POST['Izin']['cancel_note']);
					$model->cancel_by = Yii::app()->user->id;					
					$model->cancel_date = date('Y-m-d H:i:s');	
					$model->status = 2; //is_cancel
					$modelTipe = TipeIzin::model()->findByPk($model->tipe_izin_id);	 
					if($modelTipe->is_potong_cuti==1){ //Cancel Potong cuti			
						$this->updateSisaCuti($model->pegawai_id ,$model->jumlah_hari, 2);
					}else{	//Cancel Tidak Potong cuti	
						$this->updateSisaCuti($model->pegawai_id ,$model->jumlah_hari, 3);
					}
				}else{
					$model->cancel_note= null;
				}
			}

			if($model->save()){
				if( Yii::app()->user->level<>0 ){
					if( $_POST['flag'] == 1 ){
						if( $this->sendEmail( $id, 1 ) == 1 ){
							Yii::app()->user->setFlash('info', "<strong>Email konfirmasi persetujuan pengajuan izin / cuti berhasil dikirimkan </strong>");
						}
					}elseif( $_POST['flag'] == 2 ){
						if( $this->sendEmail( $id, 3 ) == 1 ){
							Yii::app()->user->setFlash('info', "<strong>Email konfirmasi pembatalan pengajuan izin / cuti berhasil dikirimkan </strong>");
						}
					}
					
					$this->redirect(array('request'));
				}else{
					$this->redirect(array('admin'));
				}
			}
		}

		$this->render('view',array(
			'model'=>$model,
			'level'=>$level,
		));
	}
	
	public function actionRequestMember()
	{
		$model = new Izin;	 
		$this->render('requestMember',array(
			'model'=>$model,
		));
	}
	
	public function actionRequest()
	{
		$model = new Izin;	 
		$this->render('request',array(
			'model'=>$model,
		));
	}

	public function actionIndex()
	{
		$this->actionAdmin();
	}
	
	public function actionAdmin()
	{
		$model=new Izin('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Izin']))
			$model->attributes=$_GET['Izin'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Izin::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='izin-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}	
	
	public function actionGetAjaxTipeOptions() {
		$data = null;
		$data = TipeIzin::model()->find('id=:id',array(':id'=>(int) $_POST['tipe_id']));
        echo CHtml::encode($data->is_must_attach);
	}
}
