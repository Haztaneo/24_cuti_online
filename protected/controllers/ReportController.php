<?php

class ReportController extends Controller
{
	public $layout='//layouts/column2';
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
	
	public function accessRules()
	{		
		return array(
			array('allow', 
				  'actions'=>array( 'historyPegawai' ),                
				  'expression'=>'(Yii::app()->user->show_report == 2) || (Yii::app()->user->show_report == 3) || ( (Yii::app()->user->show_report == 1) &&  in_array("laporan_penggunaan_cuti",Yii::app()->user->permission) )',		
			),
			array('allow', 
				  'actions'=>array( 'historyDivisi' ),
				  'expression'=>'(Yii::app()->user->show_report == 2) || ( (Yii::app()->user->show_report == 1) &&  in_array("laporan_sisa_cuti",Yii::app()->user->permission) )',			  
			),
			array('allow', 
				  'actions'=>array( 'calendarCuti','calendarEvents' ),     
				  'expression'=>'(Yii::app()->user->show_report == 2) || (Yii::app()->user->show_report == 3) || ( (Yii::app()->user->show_report == 1) &&  in_array("laporan_kalender_pegawai",Yii::app()->user->permission) )',			  
			),
			array('allow', 
				  'actions'=>array( 'calendarDivisi','calendarEventsDivisi' ),   
				   'expression'=>'(Yii::app()->user->show_report == 2) || ( (Yii::app()->user->show_report == 1) &&  in_array("laporan_kalender_divisi",Yii::app()->user->permission) )',				  
			),			
			array('allow', 
				  'actions'=>array( 'dialog', 'detail', 'excelAssociate', 'excelDivisi', 'excelAllAssociate', 'xallasLD' ),
				   'expression'=>'isset(Yii::app()->user->level)',
			),
			array('deny',  
				  'users'=>array('*'),
			),
		);
	}
	
	public function actionHistoryPegawai()
	{
        $data = array(); 
		$uid = NULL; 
		$status = NULL;
		$tahun =  date('Y'); 		 
		$tipe = NULL; 
		
		if( isset($_POST['history']) )
		{
			$condition = '';
			if( $_POST['history']['uid'] != null){		
				if( $_POST['history']['tahun'] != null){
					$tahun = $_POST['history']['tahun'];
				}
				$condition = ' where year(tgl_akhir) = '.$tahun;
				
				if( $_POST['history']['status'] != null){
					$status = $_POST['history']['status'];
					$condition .= ' and i.status = '.$status;
				}				
				
				if( $_POST['history']['tipe'] != null){
					$tipe = $_POST['history']['tipe'];
					$condition .= ' and tipe_izin_id = '.$tipe;
				}				
		
				$uid = $_POST['history']['uid'];
				if( $uid == 'black22'){ //all
					if( Yii::app()->user->akses_report == 1 ){	//Private Division
						$modelPegawai = Pegawai::model()->find(' uid = "'.Yii::app()->user->id.'"');			
						$condition .= ' and p.dept_divisi_id = "'.$modelPegawai->dept_divisi_id.'"';	
					}elseif( Yii::app()->user->akses_report == 0 ){	//Private 
						$condition .= ' and p.uid = "'.$uid.'"';	
					}
				}else{
					$condition .= ' and p.uid = "'.$uid.'"';	
				}
			}
			
			$sql = ' SELECT i.kode,p.nama_panggilan,i.tgl_pengajuan,i.jumlah_hari
									,i.alasan,ti.nama,i.status,i.tgl_mulai,i.tgl_akhir, dept_divisi_nama
						FROM izin i INNER JOIN tipe_izin ti ON i.tipe_izin_id=ti.id 
						INNER JOIN pegawai p ON p.id=i.pegawai_id
						'.$condition.' ORDER BY tgl_mulai DESC	'; 
			$data = Yii::app()->db->createCommand($sql)->queryAll();
		}
		
		$this->render('historyPegawai',array('data'=>$data,'uid'=>$uid,'status'=>$status,'tahun'=>$tahun,'tipe'=>$tipe));
	}   
	
	public function actionHistoryDivisi()
	{
        $data = array(); 
		$divisi = NULL; 
		$tahun =  date('Y'); 
		
		if( isset($_POST['history']) )
		{
			$condition = '';
			$condition2 = '';
			if( $_POST['history']['tahun'] != null){
				$tahun = $_POST['history']['tahun'];
			}
			$condition .= ' AND year(tgl_akhir)='.$tahun;
												
			if( $_POST['history']['level'] == 1 ){ //Leader
				$condition2 = ' WHERE (uid_atasan = "'.Yii::app()->user->id.'" OR p.uid = "'.Yii::app()->user->id.'" ) ' ;
				if( $_POST['history']['divisi'] != null){
					$divisi = $_POST['history']['divisi'];
					$condition2 .= ' AND LOWER(dd.nama) LIKE LOWER("%'.$divisi.'%")';
				}
			}elseif( $_POST['history']['level'] == 2 ){ //Admin
				if( Yii::app()->user->akses_report == 1 ){	//Private Division
					$condition2 = ' WHERE LOWER(dd.nama) LIKE LOWER("%'.$_POST['history']['divisi'].'%")';
				}
				elseif( Yii::app()->user->akses_report == 0 ){	//Private 
					$condition2 =  ' WHERE p.uid = "'.Yii::app()->user->id.'"' ;
				}	
			}
									
			$sql = '	SELECT p.id,p.nama_lengkap,COALESCE(cuti_diambil,0)AS cuti_diambil
									,COALESCE(CASE WHEN p.is_doj_full=1 THEN p1.sisa_cuti
											WHEN (p.is_doj_full=0 AND p.sisa_cuti > l.potong_cuti_bersama) THEN p1.sisa_cuti ELSE p.sisa_cuti END
									,CASE WHEN p.is_doj_full=1 THEN (p.sisa_cuti-l.potong_cuti_bersama)
											WHEN (p.is_doj_full=0 AND p.sisa_cuti > l.potong_cuti_bersama) THEN (p.sisa_cuti-l.potong_cuti_bersama)
											ELSE p.sisa_cuti END )AS sisa_cuti,dd.nama AS nama_divisi
						FROM pegawai p
						INNER JOIN lokasi l ON p.lokasi_id=l.id
						INNER JOIN dept_divisi dd ON p.dept_divisi_id=dd.id
						LEFT JOIN (  SELECT uid,SUM(jumlah_hari)AS cuti_diambil,MIN(sisa_cuti-jumlah_hari-jumlah_cuti_bersama)AS sisa_cuti
											FROM izin i
											INNER JOIN tipe_izin ti ON i.tipe_izin_id=ti.id
											WHERE is_potong_cuti=1 AND i.status IN (0,1) '.$condition.'
											GROUP BY 1 )AS p1 ON p.uid=p1.uid
						'.$condition2.' ORDER BY 2';	
			$data = Yii::app()->db->createCommand($sql)->queryAll();
		}
		
		$this->render('historyDivisi', array('data'=>$data,'divisi'=>$divisi,'tahun'=>$tahun) );
	}  

	public function actionDialog($id, $y)
	{
		if( Yii::app()->request->isAjaxRequest ){
			$model = Pegawai::model()->findByPk($id);			
			$sql = '	SELECT i.kode,i.tgl_pengajuan,i.jumlah_hari,i.alasan,ti.nama,i.status
									,i.status_proses,i.tgl_mulai,i.tgl_akhir
						FROM izin i INNER JOIN tipe_izin ti ON i.tipe_izin_id=ti.id 
						WHERE ti.is_potong_cuti=1 and pegawai_id = '.$id.' AND year(tgl_akhir) = '.$y.' 
									 AND i.status in (0,1) ORDER BY 2 DESC'; 
			$data = Yii::app()->db->createCommand($sql)->queryAll();
			
			$this->renderPartial('dialogCuti',
					array('data'=>$data, 'model'=>$model), false, true);
		}else{
			return false;
		}
	}
		
	public function actionCalendarCuti()
	{
        $data = array(); 
		$uid = 'black22'; 
		$tahun =  date('Y'); 		 
		$bulan = date('m'); //date('n'); 
		unset(Yii::app()->request->cookies['uid']);
		unset(Yii::app()->request->cookies['tahun']);
		unset(Yii::app()->request->cookies['bulan']);
		
		if( isset($_POST['history']) )
		{
			$condition = '';
			
			if( $_POST['history']['tahun'] != null){
				$tahun = $_POST['history']['tahun'];					
			}							
						
			if( $_POST['history']['bulan'] != null){
				$bulan = $_POST['history']['bulan'];				
			} 								
			
			$condition .= ' where year(tgl_mulai) = '.$tahun;
			$condition .= ' and (month(tgl_mulai) = '.$bulan.' or month(tgl_akhir) ='.$bulan.' )' ;
			$condition .= ' and i.status in (0,1) ' ;
			
			if( $_POST['history']['uid'] != $uid ){ //one associate
				$uid = $_POST['history']['uid'];		
				$condition .= ' and i.uid = "'.$uid.'"';
			}else{  //all associates
				$userLogin = Pegawai::model()->find(' uid = "'.Yii::app()->user->id.'"');			
				if( Yii::app()->user->level == 1 ){ //leader
					$condition .= ' and (p.uid_atasan = "'.Yii::app()->user->id.'" or p.uid = "'.Yii::app()->user->id.'" )';	
				}elseif( Yii::app()->user->level == 2 || Yii::app()->user->level == 3 ){	//Admin 
					if( Yii::app()->user->akses_report == 1 ){	//Private Division					
						$condition .= ' and p.dept_divisi_id = '.$userLogin->dept_divisi_id;	
					}elseif( Yii::app()->user->akses_report == 0 ){	//Private 
						$condition .= ' and i.uid = "'.Yii::app()->user->id.'"';	
					}
				}				
			}
						
			Yii::app()->request->cookies['tahun'] = new CHttpCookie('tahun', $tahun);
			Yii::app()->request->cookies['bulan'] = new CHttpCookie('bulan', $bulan);					
			Yii::app()->request->cookies['uid'] = new CHttpCookie('uid', $uid);
			
			$sql = '	select i.id,i.kode,i.tgl_pengajuan,i.jumlah_hari,i.alasan
						from izin i 
						inner join pegawai p on i.pegawai_id=p.id
						'.$condition.' order by 2 desc'; 
		
			$data = Yii::app()->db->createCommand($sql)->queryAll();
		}
		
		$this->render('calendarCuti',array('data'=>$data,'uid'=>$uid,'tahun'=>$tahun,'bulan'=>$bulan));
	} 
	
	public function actionCalendarDivisi()
	{
        $data = array(); 
		$divisi = null; 
		$bulan = date('m'); 
		$tahun =  date('Y'); 
		
		unset(Yii::app()->request->cookies['divisi']);
		unset(Yii::app()->request->cookies['bulan']);
		unset(Yii::app()->request->cookies['tahun']);		
		
		if( isset($_POST['history']) )
		{
			$condition = '';
		
			if( $_POST['history']['tahun'] != null){
				$tahun = $_POST['history']['tahun'];
			}	
			
			if( $_POST['history']['bulan'] != null){
				$bulan = $_POST['history']['bulan'];				
			} 						
						
			$condition .= ' where year(i.tgl_akhir) = '.$tahun;
			$condition .= ' and (month(i.tgl_mulai) = '.$bulan.' or month(i.tgl_akhir) ='.$bulan.' ) ';
			$condition .= ' and i.status in (0,1) ';
			
			if( $_POST['history']['divisi'] != null){
				$divisi = $_POST['history']['divisi'];		
				$condition .= ' and lower(i.dept_divisi_nama) like lower("%'.$divisi.'%") ';
				Yii::app()->request->cookies['divisi'] = new CHttpCookie('divisi', $divisi);
			}
			
			$userLogin = Pegawai::model()->find(' uid = "'.Yii::app()->user->id.'"');			
			if( Yii::app()->user->level == 1 ){ //leader
				$condition .= ' and (p.uid_atasan = "'.Yii::app()->user->id.'" or p.uid = "'.Yii::app()->user->id.'" )';	
			}elseif( Yii::app()->user->level == 2 || Yii::app()->user->level == 3 ){	//Admin 
				if( Yii::app()->user->akses_report == 0 ){	//Private 
					$condition .= ' and i.uid = "'.Yii::app()->user->id.'"';	
				}
			}					
				
			Yii::app()->request->cookies['tahun'] = new CHttpCookie('tahun', $tahun);	
			Yii::app()->request->cookies['bulan'] = new CHttpCookie('bulan', $bulan);					
						
			$sql = '	select i.id,i.kode,i.tgl_pengajuan,i.jumlah_hari,i.alasan
						from izin i 
						inner join pegawai p on i.pegawai_id=p.id
						'.$condition.' order by 2 desc'; 
					
			$data = Yii::app()->db->createCommand($sql)->queryAll();
		}
		
		$this->render('calendarDivisi', array('data'=>$data,'divisi'=>$divisi,'tahun'=>$tahun,'bulan'=>$bulan) );
	} 

	public function actionCalendarEvents()
    {       
        $uid = !empty($_GET['uid'])?$_GET['uid']:'';
		$items = array();
		
		$sql = ' select i.id,p.nama_panggilan as nama_lengkap
							,ti.nama as tipe_izin,i.tgl_mulai,i.tgl_akhir,i.status 
					from pegawai p 								
					inner join izin i on i.uid=p.uid 
					inner join tipe_izin ti on i.tipe_izin_id=ti.id '; 
									
		if( $uid == 'black22' ){ //all associates
			if( Yii::app()->user->level == 1 ){ //leader
				$sql .= ' where  (p.uid_atasan="'.yii::app()->user->id.'" or p.uid="'.yii::app()->user->id.'") 
								    	and i.status in(0,1) '; //and lower(dept_divisi_nama) like lower("%'.$divisi.'%") 
			}elseif( Yii::app()->user->level == 2 || Yii::app()->user->level == 3 ){	//Admin 
				if( Yii::app()->user->akses_report == 0 ){	//Private 
					$sql .= ' where p.uid="'.yii::app()->user->id.'" and i.status in(0,1) ';  
				}elseif( Yii::app()->user->akses_report == 1 ){	//Private Division
					$userLogin = Pegawai::model()->find(' uid = "'.Yii::app()->user->id.'"');	
					$sql .= ' where p.dept_divisi_id = '.$userLogin->dept_divisi_id.' and i.status in(0,1) '; 
				}elseif( Yii::app()->user->akses_report == 2 ){	//All Division 
					$sql .= ' where i.status in(0,1) ';  
				}
			}	
		}else{	//one associate
			$sql .= ' where p.uid="'.$uid.'" and i.status in(0,1) ';  
		}
		$model = Yii::app()->db->createCommand($sql)->queryAll();	
			
		$z = 0;
        foreach ($model as $value) {
			if( $z == 5 )
				$z = 0; 
				
			switch($z){
				case 0 : $color = '#2eb7ed'; break;			
				case 1 : $color = '#6DA559'; break;			
				case 2 : $color = '#d3c99b'; break;			
				case 3 : $color = '#a369af'; break;			
				case 4 : $color = '#fd8e2f'; break;	
				case 5 : $color = '#a77658'; break;	
			}
			$z += 1;
			
			if( $uid == 'black22' ){ //all associates
				if( $value["status"] == 0 ){
					$judul = '[Pending] '.$value["nama_lengkap"].' - '.$value["tipe_izin"];
					$color = '#969696';
				}else{ 
					$judul = $value["nama_lengkap"].' - '.$value["tipe_izin"];
				}
			}else{	//one associate
				if( $value["status"] == 0 ){
					$judul = '[Pending] - '.$value["tipe_izin"];
					$color = '#969696';
				}else{ 
					$judul = $value["tipe_izin"];
				}
			}
								
            $items[]=array(
                'id' => $value["id"],
                'title' => $judul,
                'start' => $value["tgl_mulai"],
                'end' => date('Y-m-d', strtotime('+1 day', strtotime($value["tgl_akhir"]))),  
				'color' => $color,	
				// 'allDay'=>true,
                // 'url'=>Yii::app()->controller->createUrl("/izin/view", array("id"=>$value->id)),
            );			
        }
        echo CJSON::encode($items);
        Yii::app()->end();
    }
	
	public function actionCalendarEventsDivisi()
    {       
        $divisi = !empty($_GET['divisi'])?$_GET['divisi']:'';
		$items = array();
		
		$sql = ' select i.id,p.nama_panggilan as nama_lengkap
							,ti.nama as tipe_izin,i.tgl_mulai,i.tgl_akhir,i.status,dept_divisi_nama 
					from pegawai p 								
					inner join izin i on i.uid=p.uid 
					inner join tipe_izin ti on i.tipe_izin_id=ti.id '; 
									
		if( Yii::app()->user->level == 0 ){ //Pegawai
			$sql .= ' where p.uid="'.yii::app()->user->id.'" and i.status in(0,1) ';  
		}elseif( Yii::app()->user->level == 1 ){ //leader
			$sql .= ' where  (p.uid_atasan="'.yii::app()->user->id.'" or p.uid="'.yii::app()->user->id.'") 
									and i.status in(0,1) '; 
		}elseif( Yii::app()->user->level == 2 || Yii::app()->user->level == 3 ){	//Admin 
			if( Yii::app()->user->akses_report == 0 ){	//Private 
				$sql .= ' where p.uid="'.yii::app()->user->id.'" and i.status in(0,1) ';  
			}elseif( Yii::app()->user->akses_report == 1 ){	//Private Division
				$userLogin = Pegawai::model()->find(' uid = "'.Yii::app()->user->id.'"');	
				$sql .= ' where p.dept_divisi_id = '.$userLogin->dept_divisi_id.' and i.status in(0,1) '; 
			}elseif( Yii::app()->user->akses_report == 2 ){	//All Division 
				$sql .= ' where lower(i.dept_divisi_nama) like lower("%'.$divisi.'%") and i.status in(0,1) '; 
			}
		}			
		$model = Yii::app()->db->createCommand($sql)->queryAll();	
				
		$z = 0;		
        foreach ($model as $value) {
			if( $z == 5 )  $z = 0; 
			
			switch($z){
				case 0 : $color = '#2eb7ed'; break;			
				case 1 : $color = '#6DA559'; break;			
				case 2 : $color = '#d3c99b'; break;			
				case 3 : $color = '#a369af'; break;			
				case 4 : $color = '#fd8e2f'; break;	
				case 5 : $color = '#a77658'; break;	
			}
			$z += 1;
			
			if( $value['status'] == 0 ){
				$judul = '[Pending] - '.$value['nama_lengkap'].' - '.$value["dept_divisi_nama"];
				$color = '#969696';
				$textColor = '#FFFFFF';
			}else{ $judul = $value['nama_lengkap'].' - '.$value["dept_divisi_nama"];
				 $textColor = '#FFFFFF';
			}
						
            $items[]=array(
				'id'=>$value['id'],
                'title'=>$judul,
                'start'=>$value['tgl_mulai'],
				'end'=>date('Y-m-d', strtotime('+1 day', strtotime($value['tgl_akhir']))),
				'color' => $color,
				'textColor' => $textColor,
                // 'url'=>Yii::app()->controller->createUrl("/izin/view", array("id"=>$value->id)),
				// 'url'=>'#',
            );			
        }		
        echo CJSON::encode($items);
        Yii::app()->end();
    }
	
	public function actionDetail($id)
	{
		$this->layout ='//layouts/blank';
	    $model = Izin::model()->findByPk($id);
		
		$this->render('detail',array(
			'model'=>$model,
		));
	}
	
	public function actionExcelAssociate() 
	{				
		$model = array();
		$uid = '';
		$tahun = date('Y');
		$bulan = date('m');
				
		if( isset(Yii::app()->request->cookies['uid']) ){		
			if(isset(Yii::app()->request->cookies['tahun'])){		
				$tahunX = Yii::app()->request->cookies['tahun'];
				$tahun = $tahunX->value;
			}
			
			if(isset(Yii::app()->request->cookies['bulan'])){		
				$month = Yii::app()->request->cookies['bulan'];
				$bulan = $month->value;
			}	
		
			$condition = ' and year(tgl_akhir)='.$tahun;
			$condition .= ' and (month(tgl_mulai) = '.$bulan.' or month(tgl_akhir) ='.$bulan.' )' ;
			
			if( Yii::app()->request->cookies['uid'] != 'black22' ){		
				$uid = Yii::app()->request->cookies['uid'];
				$condition .= ' and uid="'.$uid->value.'" ';
			}
									
			$sql = '	select kode,pegawai_nama_lengkap,dept_divisi_nama
									,tgl_mulai,tgl_akhir,jumlah_hari,alasan,status_proses,i.status,ti.nama as tipe_izin 
						from izin i
						inner join tipe_izin ti on i.tipe_izin_id=ti.id
						where i.status in(0,1) '.$condition.' order by dept_divisi_nama,pegawai_nama_lengkap';
			
			$data = new CSqlDataProvider($sql, array(	
						'pagination'=>false,
					 ));
			$model = $data->getData(); 
			
			$pegawai = Pegawai::model()->find('uid="'.$uid.'"');
			if(count($pegawai)>0) 
				$uid = $pegawai->nama_lengkap;
			else 
				$uid = '';
		}

		$this->render('excel',array(
				'model'=>$model,
				'uid'=>$uid,
				'tahun'=>$tahun,
				'bulan'=>$bulan,
				'flag'=>1,
		));
	}

	public function actionExcelAllAssociate()
	{		
		$condition = '';
		if( isset(Yii::app()->request->cookies['tahun']) ){
			$tahun = Yii::app()->request->cookies['tahun'];
            $condition .= ' and year(tgl_akhir) = '.$tahun;
		}else{ 
			$tahun = date('Y');
			$condition .= ' and year(tgl_akhir) = year(now()) ';
		}
		
		/*Add New*/
		if(isset(Yii::app()->request->cookies['bulan'])){		
			$month = Yii::app()->request->cookies['bulan'];
			$bulan = $month->value;
		}else{ 
			$bulan = date('m');			
		}	
		$condition .= ' and (month(tgl_mulai) = '.$bulan.' or month(tgl_akhir) ='.$bulan.' )' ;
				
		if( Yii::app()->user->level == 0 ){ //Associate
			$condition .= ' and p.uid="'.yii::app()->user->id.'" ' ;  
		}elseif( Yii::app()->user->level == 1 ){ //leader
			$condition .= ' and  (p.uid_atasan="'.yii::app()->user->id.'" or p.uid="'.yii::app()->user->id.'") '; 
		}elseif( Yii::app()->user->level == 2 || Yii::app()->user->level == 3 ){	//Admin 
			if( Yii::app()->user->akses_report == 0 ){	//Private 
				$condition .= ' and p.uid="'.yii::app()->user->id.'" ';  
			}elseif( Yii::app()->user->akses_report == 1 ){	//Private Division
				$userLogin = Pegawai::model()->find(' uid = "'.Yii::app()->user->id.'"');	
				$condition .= ' and p.dept_divisi_id = '.$userLogin->dept_divisi_id; 
			}
		}					
		
		$sql = '	select i.kode, i.pegawai_nama_lengkap, i.dept_divisi_nama
					  	  	, i.tgl_mulai, i.tgl_akhir, i.jumlah_hari, i.alasan, i.status_proses, i.status,ti.nama as tipe_izin 
					from pegawai p 								
					inner join izin i on i.uid=p.uid 
					inner join tipe_izin ti on i.tipe_izin_id=ti.id
					where i.status in(0,1) '.$condition.' order by 3, 2 ';
		$model = 	new CSqlDataProvider($sql, array(	
							'pagination'=>false,
						));
				 
		if(isset(Yii::app()->request->cookies['uid'])){
			$flag = 1;
		}elseif(isset(Yii::app()->request->cookies['divisi'])){
			$flag = 2;
		}else{
			$flag = 0;
		}
		
		$this->render('excel',array(
				'model'=>$model->getData(),
				'tahun'=>$tahun,
				'bulan'=>$bulan,
				'flag'=>$flag,
		));
	}
	
	public function actionExcelDivisi()
	{				
		$model = array();
		$divisi = '';
		$tahun = date('Y');
		$bulan = date('m');
				
		if(isset(Yii::app()->request->cookies['tahun'])){		
			$tahunX = Yii::app()->request->cookies['tahun'];
			$tahun = $tahunX->value;				
		}
		
		if(isset(Yii::app()->request->cookies['bulan'])){		
			$month = Yii::app()->request->cookies['bulan'];
			$bulan = $month->value;				
		}
		
		$condition = ' and year(tgl_akhir)='.$tahun;
		$condition .= ' and (month(tgl_mulai) = '.$bulan.' or month(tgl_akhir) ='.$bulan.' )' ;	
		
		if(isset(Yii::app()->request->cookies['divisi'])){
			$divisi = Yii::app()->request->cookies['divisi'];
			$condition .= ' and lower(i.dept_divisi_nama) like lower("%'.$divisi->value.'%") ';
		}
		
		if( Yii::app()->user->level == 0 ){ //Associate
			$condition .= ' and p.uid="'.yii::app()->user->id.'" ' ;  
		}elseif( Yii::app()->user->level == 1 ){ //leader
			$condition .= ' and  (p.uid_atasan="'.yii::app()->user->id.'" or p.uid="'.yii::app()->user->id.'") '; 
		}elseif( Yii::app()->user->level == 2 || Yii::app()->user->level == 3 ){	//Admin 
			if( Yii::app()->user->akses_report == 0 ){	//Private 
				$condition .= ' and p.uid="'.yii::app()->user->id.'" ';  
			}
		}				
	
		$sql = '	select i.kode, i.pegawai_nama_lengkap, i.dept_divisi_nama
							, i.tgl_mulai, i.tgl_akhir, i.jumlah_hari, i.alasan, i.status_proses, i.status,ti.nama as tipe_izin  
					from pegawai p 								
					inner join izin i on i.uid=p.uid
					inner join tipe_izin ti on i.tipe_izin_id=ti.id						
					where i.status in(0,1) '.$condition.' order by 3, 2 ';			
		$data = new CSqlDataProvider($sql, array(	
						'pagination'=>false,
					 ));
		$model = $data->getData(); 
	

		$this->render('excel',array(
				'model'=>$model,
				'divisi'=>$divisi,
				'tahun'=>$tahun,
				'bulan'=>$bulan,
				'flag'=>2,
		));
	}
	
			
	public function actionXallasLD()
	{		
		$pegawai = Pegawai::model()->find('uid = "'.Yii::app()->user->id.'"');
		$condition = '';
		$tahun = date('Y');
		$bulan = date('m');
		
		if(isset(Yii::app()->request->cookies['tahun'])){
			$tahun = Yii::app()->request->cookies['tahun'];           
		}
				 
		if(isset(Yii::app()->request->cookies['bulan'])){		
			$month = Yii::app()->request->cookies['bulan'];
			$bulan = $month->value;				
		}
		
		$condition .= ' and year(tgl_akhir)='.$tahun;
		$condition .= ' and (month(tgl_mulai) = '.$bulan.' OR month(tgl_akhir) ='.$bulan.' )' ;	
		
		$sql = '	select kode,pegawai_nama_lengkap,dept_divisi_nama
							,tgl_mulai,tgl_akhir,jumlah_hari,alasan,status_proses,i.status,ti.nama as tipe_izin  
					from izin i 
					inner join tipe_izin ti on i.tipe_izin_id=ti.id
					inner join pegawai p on i.uid=p.uid
					where i.status in(0,1) and lower(dept_divisi_nama) like lower("%'.$pegawai->deptDivisi->nama.'%") and (uid_atasan="'.yii::app()->user->id.'" or i.uid="'.yii::app()->user->id.'")
					'.$condition.' order by dept_divisi_nama,pegawai_nama_lengkap';
		$model = 	new CSqlDataProvider($sql, array(	
							'pagination'=>false,
						));
				 
		if(isset(Yii::app()->request->cookies['uid'])){
			$flag = 1;
		}elseif(isset(Yii::app()->request->cookies['divisi'])){
			$flag = 2;
		}else{
			$flag = 0;
		}
		
		$this->render('excel',array(
				'model'=>$model->getData(),
				'tahun'=>$tahun,
				'bulan'=>$bulan,
				'flag'=>$flag,
		));
	}
}