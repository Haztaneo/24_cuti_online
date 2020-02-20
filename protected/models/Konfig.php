<?php

/**
 * This is the model class for table "konfig".
 *
 * The followings are the available columns in table 'konfig':
 * @property integer $id
 * @property integer $jumlah_cuti_setahun
 * @property integer $max_ambil_cuti
 * @property integer $tgl_min_doj
 * @property integer $pending_cuti
 * @property integer $periode
 * @property integer $min_tgl_pengajuan
 * @property integer $app
 */
class Konfig extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'konfig';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('jumlah_cuti_setahun, max_ambil_cuti, tgl_min_doj, pending_cuti, periode', 'required','message'=>'{attribute} wajib diisi'),  
			array('jumlah_cuti_setahun, max_ambil_cuti, tgl_min_doj, pending_cuti, periode, min_tgl_pengajuan, app', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, jumlah_cuti_setahun, max_ambil_cuti, tgl_min_doj, pending_cuti, periode, min_tgl_pengajuan, app', 'safe', 'on'=>'search'),
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
			'jumlah_cuti_setahun' => 'Jumlah Cuti Setahun',
			'max_ambil_cuti' => 'Max Ambil Cuti',
			'tgl_min_doj' => 'Tgl Min DOJ',
			'pending_cuti' => 'Pending Cuti',
			'periode' => 'Periode',
			'min_tgl_pengajuan' => 'Minimal Hari Pengajuan',
			'app' => 'App',
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
		$criteria->compare('jumlah_cuti_setahun',$this->jumlah_cuti_setahun);
		$criteria->compare('max_ambil_cuti',$this->max_ambil_cuti);
		$criteria->compare('tgl_min_doj',$this->tgl_min_doj);
		$criteria->compare('pending_cuti',$this->pending_cuti);
		$criteria->compare('periode',$this->periode);
		$criteria->compare('min_tgl_pengajuan',$this->min_tgl_pengajuan);
		$criteria->compare('app',$this->app);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Konfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
