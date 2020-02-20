<?php
class Agama extends CActiveRecord
{
	public function tableName()
	{
		return 'agama';
	}

	public function rules()
	{
		return array(
			array('nama', 'required','message'=>'{attribute} wajib diisi'), 
			array('nama', 'length', 'max'=>10),
			array('id, nama', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'nama' => 'Nama',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('nama',$this->nama,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getOptions(){
        return CHtml::listData($this->findAll(array('order'=>'id')), 'id', 'nama');
    }
}
