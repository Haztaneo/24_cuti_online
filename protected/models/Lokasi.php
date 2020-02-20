<?php

/**
 * This is the model class for table "lokasi".
 *
 * The followings are the available columns in table 'lokasi':
 * @property integer $id
 * @property string $nama
 * @property integer $status
 * @property integer $minggu
 * @property integer $senin
 * @property integer $selasa
 * @property integer $rabu
 * @property integer $kamis
 * @property integer $jumat
 * @property integer $sabtu
 * @property integer $potong_cuti_bersama
 */
class Lokasi extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lokasi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nama', 'required','message'=>'{attribute} wajib diisi'), 
			array('status, minggu, senin, selasa, rabu, kamis, jumat, sabtu, potong_cuti_bersama', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nama, status, minggu, senin, selasa, rabu, kamis, jumat, sabtu, potong_cuti_bersama', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nama' => 'Nama',
			'status' => 'Status',
			'minggu' => 'Minggu',
			'senin' => 'Senin',
			'selasa' => 'Selasa',
			'rabu' => 'Rabu',
			'kamis' => 'Kamis',
			'jumat' => 'Jumat',
			'sabtu' => 'Sabtu',
			'potong_cuti_bersama' => 'Potong Cuti Bersama',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('minggu',$this->minggu);
		$criteria->compare('senin',$this->senin);
		$criteria->compare('selasa',$this->selasa);
		$criteria->compare('rabu',$this->rabu);
		$criteria->compare('kamis',$this->kamis);
		$criteria->compare('jumat',$this->jumat);
		$criteria->compare('sabtu',$this->sabtu);
		$criteria->compare('potong_cuti_bersama',$this->potong_cuti_bersama);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Lokasi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getOptions(){
        return CHtml::listData($this->findAll(array('condition'=>'status=1','order'=>'nama')), 'id', 'nama');
    }
}
