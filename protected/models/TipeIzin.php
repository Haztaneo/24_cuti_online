<?php
class TipeIzin extends CActiveRecord
{
	public function tableName()
	{
		return 'tipe_izin';
	}

	public function rules()
	{
		return array(
			array('nama', 'required','message'=>'{attribute} wajib diisi'),
			array('is_potong_cuti, jatah_cuti, is_must_attach, status', 'numerical', 'integerOnly'=>true,'message'=>'{attribute} harus nilai integer'),
			array('nama', 'length', 'max'=>50),
			array('id, nama, is_potong_cuti, jatah_cuti, is_must_attach, status', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'izins' => array(self::HAS_MANY, 'Izin', 'tipe_izin_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nama' => 'Nama',
			'is_potong_cuti' => 'Potong Jatah Cuti ?',
			'jatah_cuti' => 'Jatah Max Izin / Cuti',
			'is_must_attach' => 'Wajib Attach Dokumen ?',
			'status' => 'Status',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('is_potong_cuti',$this->is_potong_cuti);
		$criteria->compare('jatah_cuti',$this->jatah_cuti);
		$criteria->compare('is_must_attach',$this->is_must_attach);
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
        return CHtml::listData($this->findAll(array('condition'=>'status = 1','order'=>'is_potong_cuti,nama')), 'id', 'nama');
    }
}
