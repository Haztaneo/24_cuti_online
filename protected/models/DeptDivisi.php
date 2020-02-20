<?php
class DeptDivisi extends CActiveRecord
{
	public function tableName()
	{
		return 'dept_divisi';
	}

	public function rules()
	{
		return array(
			array('kode, nama', 'required','message'=>'{attribute} wajib diisi'), 
			array('max_cuti_tahunan, status', 'numerical', 'integerOnly'=>true),
			array('kode', 'length', 'max'=>5),
			array('nama', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, kode, nama, max_cuti_tahunan, status', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
			'atasans' => array(self::HAS_MANY, 'Atasan', 'dept_divisi_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kode' => 'Kode',
			'nama' => 'Nama',
			'max_cuti_tahunan' => 'Max Cuti Tahunan',
			'status' => 'Status',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('kode',$this->kode,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('max_cuti_tahunan',$this->max_cuti_tahunan);
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
