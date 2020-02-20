<?php
class Atasan extends CActiveRecord
{
	public function tableName()
	{
		return 'atasan';
	}

	public function rules()
	{
		return array(
			array('dept_divisi_id, uid, email, nama', 'required','message'=>'{attribute} wajib diisi'),
			array('dept_divisi_id, is_manager, status', 'numerical', 'integerOnly'=>true),
			array('nama', 'uniqueNama'),	
			array('uid, nama, keterangan', 'length', 'max'=>50),
			array('email', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dept_divisi_id, uid, email, nama, keterangan, is_manager, status', 'safe', 'on'=>'search'),
		);
	}

	public function uniqueNama($attribute, $params)
	{
		if($user = UserLdap::model()->exists('nama=:nama',array('nama'=>$this->nama)) || $this->nama==''){
		}else{
		  $this->addError($attribute, 'Nama Associate tidak tersedia!!'); }
	}

	public function relations()
	{
		return array(
			'deptDivisi' => array(self::BELONGS_TO, 'DeptDivisi', 'dept_divisi_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'dept_divisi_id' => 'Dept / Divisi',
			'uid' => 'UID',
			'email' => 'Email',
			'nama' => 'Nama',
			'keterangan' => 'Keterangan',
			'is_manager' => 'Is Manager',
			'status' => 'Status',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('dept_divisi_id',$this->dept_divisi_id);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('keterangan',$this->keterangan,true);
		$criteria->compare('is_manager',$this->is_manager);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getOptions(){
        return CHtml::listData($this->findAll(array('condition'=>'status=1','order'=>'nama')), 'id', 'nama');
    }
}
