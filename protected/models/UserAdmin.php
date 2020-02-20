<?php

/**
 * This is the model class for table "user_admin".
 *
 * The followings are the available columns in table 'user_admin':
 * @property string $uid
 * @property string $email
 * @property string $nama
 * @property integer $is_receive_email
 * @property integer $status
 * @property integer $show_admin_page
 * @property integer $show_report
 * @property integer $akses_report
 * @property integer $level
 * @property integer $user_role_id
 */
class UserAdmin extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_admin';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, email, user_role_id', 'required','message'=>'{attribute} wajib diisi'), 
			array('is_receive_email, status, show_admin_page, show_report, akses_report, level, user_role_id', 'numerical', 'integerOnly'=>true),
			array('uid, nama', 'length', 'max'=>50),
			array('email', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uid, email, nama, is_receive_email, status, show_admin_page, show_report, akses_report, level, user_role_id', 'safe', 'on'=>'search'),
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
			'uid' => 'UID',
			'email' => 'Email',
			'nama' => 'Nama',
			'is_receive_email' => 'Terima Notif Email ?',
			'status' => 'Status',
			'show_admin_page' => 'Akses Halaman Admin ?',
			'show_report' => 'Akses Halaman Laporan ?',
			'level' => 'Level',
			'user_role_id' => 'Role',
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

		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('is_receive_email',$this->is_receive_email);
		$criteria->compare('status',$this->status);
		$criteria->compare('show_admin_page',$this->show_admin_page);
		$criteria->compare('show_report',$this->show_report);
		$criteria->compare('level',$this->level);
		$criteria->compare('user_role_id',$this->user_role_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserAdmin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
