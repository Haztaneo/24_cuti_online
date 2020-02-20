<?php
class UserLdap extends CActiveRecord
{
	public function tableName()
	{
		return 'user_ldap';
	}

	public function rules()
	{
		return array(
			array('uid, email', 'required','message'=>'{attribute} wajib diisi'), 
			array('uid, nama', 'length', 'max'=>50),
			array('is_visible', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>100),
			array('status', 'length', 'max'=>10),
			array('uid, email, nama, status', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'uid' => 'Uid',
			'email' => 'Email',
			'nama' => 'Nama',
			'status' => 'Status',
		);
	}

	public function search( $id = 0 )
	{
		$criteria=new CDbCriteria;
		if($id == 1){
			$criteria->condition= 'status<>"active" or is_visible=0' ;
		}else{ 
			$criteria->condition= 'status="active" and is_visible=1' ;
		}
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('status',$this->status,true);
		$criteria->order = 'is_visible desc,status,uid';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>25),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	// public function getOptions(){
        // return CHtml::listData($this->findAll(array('condition'=>'status="active" and is_visible=1','order'=>'uid')), 'uid', 'uid');
    // }
	
	public function getOptions(){
        $sql = ' select ul.uid from user_ldap ul left join pegawai p on ul.uid=p.uid where p.uid is null and status="active" and is_visible=1 ';        
        $sql = Yii::app()->db->createCommand($sql);
        $row = $sql->queryAll();
		
		return CHtml::listData($row,'uid','uid');
    }
	
	public function getOptionsInput(){
        $sql = ' select ul.uid, p.nama_lengkap from user_ldap ul inner join pegawai p on ul.uid=p.uid where ul.status="active" and ul.is_visible=1 and p.uid_atasan is not null order by 2 ';        
        $sql = Yii::app()->db->createCommand($sql);
        $row = $sql->queryAll();
		
		return CHtml::listData($row,'uid','nama_lengkap');
    }
		
	public function getOptionsAdmin(){
        $sql = ' select ul.nama from user_ldap ul left join user_admin a on ul.uid=a.uid where a.uid is null and ul.status="active" and ul.is_visible=1 order by 1 ';        
        $sql = Yii::app()->db->createCommand($sql);
        $row = $sql->queryAll();
		
		return CHtml::listData($row,'nama','nama');
    }
	
	public function getOptionsAtasan(){
        $sql = ' select ul.nama from user_ldap ul left join atasan a on ul.uid=a.uid where a.uid is null and ul.status="active" and ul.is_visible=1 order by 1 ';        
        $sql = Yii::app()->db->createCommand($sql);
        $row = $sql->queryAll();
		
		return CHtml::listData($row,'nama','nama');
    }
	
	public function getOptionsReport($id){
		$modelPegawai = Pegawai::model()->find( 'uid = "'.Yii::app()->user->id.'"' );		
        if( $id == 1 ){   //Private Division
			$condition = ' where p.dept_divisi_id ='.$modelPegawai->dept_divisi_id;
		}elseif( $id == 2 ){ //All Division
			$condition = ' ';
		}
		
		$sql = ' SELECT "black22" AS uid,"-- Semua Associate --" AS nama UNION
				  (SELECT p.uid,CONCAT(p.nama_lengkap," - [ ",dd.nama," ]")AS nama 
				  FROM user_ldap ul INNER JOIN pegawai p ON ul.uid=p.uid
				  INNER JOIN dept_divisi dd ON p.dept_divisi_id=dd.id '.$condition.') order by 2 ';    		
        $sql = Yii::app()->db->createCommand($sql);
        $row = $sql->queryAll();
		
		if (count($row)>0)
			return CHtml::listData($row,'uid','nama');
		else
			return CHtml::listData(array(''),'uid','nama');
    }
	
	public function getOptionsReportLeader(){
        $sql='SELECT "black22" AS uid,"-- Semua Associate --" AS nama UNION
				SELECT uid,CONCAT(p.nama_lengkap," - [ ",dd.nama," ]")AS nama 
				FROM pegawai p INNER JOIN dept_divisi dd ON p.dept_divisi_id=dd.id 
				WHERE uid="'.Yii::app()->user->id.'" 
				UNION SELECT p.uid,CONCAT(p.nama_lengkap," - [ ",dd.nama," ]")AS nama 
				FROM user_ldap ul INNER JOIN pegawai p ON ul.uid=p.uid
				INNER JOIN dept_divisi dd ON p.dept_divisi_id=dd.id 
				WHERE uid_atasan="'.Yii::app()->user->id.'" order by 2';        
        $sql = Yii::app()->db->createCommand($sql);
        $row = $sql->queryAll();
		
		if (count($row)>0)
			return CHtml::listData($row,'uid','nama');
		else
			return CHtml::listData(array(''),'uid','nama');
    }
}
