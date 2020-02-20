<?php
class CutiBersama extends CActiveRecord
{
	public function tableName()
	{
		return 'cuti_bersama';
	}

	public function rules()
	{
		return array(
			array('tgl', 'required','message'=>'{attribute} wajib diisi'),
			// array('periode_id', 'numerical', 'integerOnly'=>true),
			array('hari', 'length', 'max'=>6),
			array('keterangan', 'safe'),
			array('id, periode, tgl, hari, keterangan', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'periode' => array(self::BELONGS_TO, 'Periode', 'periode_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'periode' => 'Periode',
			'tgl' => 'Tanggal',
			'hari' => 'Hari',
			'keterangan' => 'Keterangan',
		);
	}

	public function search( $id = 0 )
	{
		$criteria=new CDbCriteria;
		if($id == 1){
			$criteria->condition= 'periode < '.date('Y');
		}else{ 
			$criteria->condition= 'periode = '.date('Y');
		}
		$criteria->compare('id',$this->id);
		$criteria->compare('periode',$this->periode,true);
		$criteria->compare('tgl',$this->tgl,true);
		$criteria->compare('hari',$this->hari,true);
		$criteria->compare('keterangan',$this->keterangan,true);
		$criteria->order = 'periode desc, tgl';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>20),
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
