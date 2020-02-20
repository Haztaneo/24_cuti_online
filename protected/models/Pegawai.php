<?php
class Pegawai extends CActiveRecord
{
	public function tableName()
	{
		return 'pegawai';
	}

	public function rules()
	{
		return array(
			array('nik, nama_lengkap, doj, dept_divisi_id, lokasi_id, sex, agama_id, tgl_lahir', 'required','message'=>'{attribute} wajib diisi'), 
			array('uid', 'required','message'=>'Email wajib diisi'),
			// array('uid', 'compare', 'compareAttribute'=>'nama_atasan' , 'message'=>'email and email12 Password do not match'), 			
			array('dept_divisi_id, lokasi_id, agama_id, sisa_cuti, pending_cuti, hp, is_doj_full, max_cuti, allow_izin, ctl', 'numerical', 'integerOnly'=>true,'message'=>'{attribute} harus nilai integer'), 
			array('uid, nama_lengkap, uid_atasan, nama_atasan', 'length', 'max'=>50),
			array('nik, uid, nama_lengkap','unique', 'message'=>'Data sudah tersedia'),
			array('uid', 'uniqueUID'),			
			array('nama_atasan', 'uniqueNama'),			
			array('nama_atasan', 'validateUID'),			
			// array('uid', 'unique', 'className' => 'UserLdap', 'attributeName' => 'uid', 'message'=>'This uid is already in use'),			
			array('email', 'length', 'max'=>100),
			array('nama_panggilan', 'length', 'max'=>15),
			array('sex', 'length', 'max'=>1),
			array('ktp', 'length', 'max'=>16),
			array('alamat_ktp', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, email, nik, nama_lengkap, doj, dept_divisi_id, lokasi_id, nama_panggilan, sex, agama_id, tgl_lahir, ktp, alamat_ktp, hp, sisa_cuti, pending_cuti, is_doj_full, uid_atasan, nama_atasan, max_cuti, allow_izin, ctl', 'safe', 'on'=>'search'), 
		);
	}

	public function uniqueUID($attribute, $params)
	{
		$uid = str_replace(Yii::app()->params['ldap_domain'],"",$this->uid);
		if($user = UserLdap::model()->exists('uid=:uid',array('uid'=>$uid)) || $this->uid==''){
		}else{
		  $this->addError($attribute, 'Email tidak tersedia!!'); }
	}

	public function uniqueNama($attribute, $params)
	{
		if( $user = Atasan::model()->exists('nama=:nama',array('nama'=>$this->nama_atasan)) || $this->nama_atasan=='' ){
		}else{
		  $this->addError($attribute, 'Nama Associate tidak tersedia!!'); }
	}
	
	public function validateUID($attribute, $params)
	{
		$user = UserLdap::model()->find('nama = "'.$this->nama_atasan.'"'); 	
		if( count($user)>0 ){
			if( $user->uid == $this->uid ){
				$this->addError($attribute, 'Nama Atasan harus berbeda dengan Nama Email Associate !!'); 
			}
		}
	}
	
	public function relations()
	{
		return array(
			'atasans' => array(self::HAS_MANY, 'Atasan', 'pegawai_id'),
			'izins' => array(self::HAS_MANY, 'Izin', 'pegawai_id'),
			'izins1' => array(self::HAS_MANY, 'Izin', 'disetujui_id'),
			'izins2' => array(self::HAS_MANY, 'Izin', 'diketahui_id'),
			'u' => array(self::BELONGS_TO, 'UserLdap', 'uid'),
			'deptDivisi' => array(self::BELONGS_TO, 'DeptDivisi', 'dept_divisi_id'),
			'lokasi' => array(self::BELONGS_TO, 'Lokasi', 'lokasi_id'),
			'agama' => array(self::BELONGS_TO, 'Agama', 'agama_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => 'UID',
			'email' => 'Email',
			'nik' => 'NIK',
			'nama_lengkap' => 'Nama Lengkap',
			'doj' => 'DOJ',
			'dept_divisi_id' => 'Divisi',
			'lokasi_id' => 'Lokasi',
			'nama_panggilan' => 'Nama Panggilan',
			'sex' => 'Jenis Kelamin',
			'agama_id' => 'Agama',
			'tgl_lahir' => 'Tanggal Lahir',
			'ktp' => 'KTP',
			'alamat_ktp' => 'Alamat KTP',
			'hp' => 'No. HP',
			'sisa_cuti' => 'Sisa Cuti',
			'is_doj_full' => 'Is Doj Full',
			'uid_atasan' => 'UID Atasan',
			'nama_atasan' => 'Nama Atasan',
			'max_cuti' => 'Max Ambil Cuti',
			'allow_izin' => 'Status',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('nik',$this->nik,true);
		$criteria->compare('nama_lengkap',$this->nama_lengkap,true);
		$criteria->compare('doj',$this->doj,true);
		$criteria->compare('dept_divisi_id',$this->dept_divisi_id);
		$criteria->compare('lokasi_id',$this->lokasi_id);
		$criteria->compare('nama_panggilan',$this->nama_panggilan,true);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('agama_id',$this->agama_id);
		$criteria->compare('tgl_lahir',$this->tgl_lahir,true);
		$criteria->compare('ktp',$this->ktp,true);
		$criteria->compare('alamat_ktp',$this->alamat_ktp,true);
		$criteria->compare('hp',$this->hp,true);
		$criteria->compare('sisa_cuti',$this->sisa_cuti);
		$criteria->compare('is_doj_full',$this->is_doj_full);
		$criteria->compare('uid_atasan',$this->uid_atasan,true);
		$criteria->compare('nama_atasan',$this->nama_atasan,true);		
		if (!Yii::app()->request->isAjaxRequest) {
            $criteria->order = 'email, nama_atasan';
        }
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchMember()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('uid_atasan',Yii::app()->user->id,true);
		// $criteria->addColumnCondition(array('t.uid_atasan' => Yii::app()->user->id));
		// if (!Yii::app()->request->isAjaxRequest) {
            // $criteria->order = 'email, nama_atasan';
        // }
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
