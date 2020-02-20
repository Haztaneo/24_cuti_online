<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
    <div class="container">
	
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
		  <a class="brand" href="#" style="color: WHITE; text-decoration: none;font-size: 14px !important;text-shadow: 0 0 0 #ccc;font-weight:bold"><small>Cuti Online</small> <big>PT. K-24 INDONESIA</big></a>
		  
       <div class="nav-collapse">				
			<?php  
				$listPermission = isset(Yii::app()->user->permission)?Yii::app()->user->permission:array();	
				$sumMember = count(Pegawai::model()->findAll(array("condition"=>"uid_atasan='".Yii::app()->user->id."'")));
				
				$pegawai = Pegawai::model()->find(' uid = "'.Yii::app()->user->id.'"'); 	
				if( count($pegawai) == 0 ){
					$cb = count(CutiBersama::model()->search()->getData());
				}else{
					$cb = $pegawai->lokasi->potong_cuti_bersama;
				}
				
				$this->widget('zii.widgets.CMenu',array(
                    'htmlOptions'=>array('class'=>'pull-right nav'),
                    'submenuHtmlOptions'=>array('class'=>'dropdown-menu'),
					'itemCssClass'=>'item-test',
                    'encodeLabel'=>false,
                    'items'=>array(  
						array('label'=>'Anggota Anda&nbsp;<span class="badge pull-right">'.$sumMember.'</span>', 
									'url'=>array('/pegawai/member'), 'visible'=> $sumMember > 0 ),
						array('label'=>'Associate&nbsp;<span class="badge badge-warning pull-right">'.count(Pegawai::model()->findAll()).'</span>', 'url'=>array('/pegawai'), 'visible'=>Yii::app()->user->show_admin_page==1),
						array('label'=>'Cuti Bersama&nbsp;<span class="badge badge-warning pull-right">'.$cb.'</span>', 'url'=>array('/cutiBersama'), 'visible'=>isset(Yii::app()->user->level) ),
						array('label'=>'LDAP&nbsp;<span class="badge badge-warning pull-right">'.count(UserLdap::model()->findAll("status='active' and is_visible=1")).'</span>', 'url'=>array('/userLdap'), 'visible'=>Yii::app()->user->show_admin_page==1),						
						array('label'=>'Laporan <span class="caret"></span>', 'url'=>'#',
								'itemOptions'=>array('class'=>'dropdown','tabindex'=>"-2"),
								// 'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
								'linkOptions'=>array('data-toggle'=>"dropdown"), 
								'items'=>array(                           
									array('label'=>'Riwayat Penggunaan Cuti', 'url' => array('/report/historyPegawai'),
										'visible'=> (Yii::app()->user->show_report == 2) || (Yii::app()->user->show_report == 3) || (Yii::app()->user->show_report == 1 && in_array('laporan_penggunaan_cuti',$listPermission)) ), 
									array('label'=>'Sisa Cuti Associate', 'url' => array('/report/historyDivisi'), 
										'visible'=> (Yii::app()->user->show_report == 2) || (Yii::app()->user->show_report == 1 && in_array('laporan_sisa_cuti',$listPermission)) ), 
									array('label'=>'Kalender cuti Associate', 'url' => array('/report/calendarCuti'), 
										'visible'=> (Yii::app()->user->show_report == 2) || (Yii::app()->user->show_report == 3) || (Yii::app()->user->show_report == 1 && in_array('laporan_kalender_pegawai',$listPermission)) ), 
									array('label'=>'Kalender cuti divisi', 'url' => array('/report/calendarDivisi'), 
										'visible'=> (Yii::app()->user->show_report == 2) || (Yii::app()->user->show_report == 1 && in_array('laporan_kalender_divisi',$listPermission)) ), 
								), 'visible'=> Yii::app()->user->show_report > 0  ),	
						array('label'=>'Settings <span class="caret"></span>', 'url'=>'#',
								'itemOptions'=>array('class'=>'dropdown','tabindex'=>"-1"),
								// 'linkOptions'=>array('class'=>'dropdown-toggle','data-toggle'=>"dropdown"), 
								'linkOptions'=>array('data-toggle'=>"dropdown"), 
								'items'=>array(                           
									array('label'=>'Agama', 'url' => array('/agama')),
									array('label'=>'Atasan', 'url' => array('/atasan')),
									array('label'=>'Divisi', 'url' => array('/deptDivisi')),
									array('label'=>'Lokasi', 'url' => array('/lokasi')),
									array('label'=>'Tipe Izin', 'url' => array('/tipeIzin')),
									array('label'=>'Konfigurasi', 'url' => array('/konfig')),
									array('label'=>'Role Laporan', 'url' => array('/userRole/admin'), 'visible'=>Yii::app()->user->show_admin_page==1 ),
									array('label'=>'Permission', 'url' => array('/userPermission/admin'), 'visible'=>Yii::app()->user->level==3),
									array('label'=>'Admin & HRD', 'url' => array('/userAdmin'), 'visible'=>Yii::app()->user->level==3),
									array('label'=>'Input Izin & Cuti', 'url' => array('/izin/input')),
								), 'visible'=>Yii::app()->user->show_admin_page==1),
                        array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                        array('label'=>'Logout ('.Yii::app()->user->id.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                    ),
                )); ?>
    	</div>
    </div>
	</div>
</div>