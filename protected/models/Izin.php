<?php
class Izin extends CActiveRecord
{
	public function tableName()
	{
		return 'izin';
	}

	public function rules()
	{
		return array(
			array('tahun, bulan, kode, tgl_pengajuan, pegawai_id, pegawai_nama_lengkap, dept_divisi_nama, tipe_izin_id, tgl_mulai, tgl_akhir, jumlah_hari, sisa_cuti, jumlah_cuti_bersama, alasan', 'required','message'=>'{attribute} wajib diisi'),
			array('tahun, bulan, pegawai_id, tipe_izin_id, jumlah_hari,  sisa_cuti,jumlah_cuti_bersama, disetujui_id, diketahui_id, status_proses, status', 'numerical', 'integerOnly'=>true),		  
			array('attach_dokumen', 'required','on'=>'attach','message'=>'{attribute} wajib diisi'), 
			// array('attach_dokumen', 'length', 'max' => 255, 'tooLong' => 'Nama {attribute} terlalu panjang (max {max} chars).', 'on' => 'attach'),
			// array('attach_dokumen', 'file', 'types' => 'jpeg/jpg', 'allowEmpty' => true, 'maxSize' => 1024 * 1024 * 0.5,'wrongType'=>'{attribute} harus dalam format jpeg/jpg', 'tooLarge' => 'Ukuran {attribute} tidak boleh lebih besar dari 512KB.', 'on' => 'attach'),
			array('cancel_note', 'required','on'=>'cnc','message'=>'{attribute} wajib diisi'), 
			array('approval_note', 'required','on'=>'approval','message'=>'Keterangan wajib diisi'), 
			array('tgl_akhir','compare','compareAttribute'=>'tgl_mulai','operator'=>'>=','message'=>'Tanggal Akhir harus lebih besar dari Tanggal Mulai'),
			array('kode', 'length', 'max'=>25),
			array('uid, pegawai_nama_lengkap, dept_divisi_nama, disetujui_nama, diketahui_nama, cancel_by, created_by', 'length', 'max'=>50),
			array('uid, attach_dokumen, approval_note, disetujui_tgl, diketahui_tgl, cancel_note, cancel_date, created_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tahun, bulan, kode, tgl_pengajuan, pegawai_id, uid, pegawai_nama_lengkap, dept_divisi_nama, tipe_izin_id, tgl_mulai, tgl_akhir, jumlah_hari,  sisa_cuti, jumlah_cuti_bersama, alasan, attach_dokumen, approval_note, disetujui_id, disetujui_nama, disetujui_tgl, diketahui_id, diketahui_nama, diketahui_tgl, status_proses, status, cancel_by, cancel_note, cancel_date, created_by, created_date', 'safe', 'on'=>'search'),
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
			'pegawai' => array(self::BELONGS_TO, 'Pegawai', 'pegawai_id'),
			'tipeIzin' => array(self::BELONGS_TO, 'TipeIzin', 'tipe_izin_id'),
			'approval' => array(self::BELONGS_TO, 'Approval', 'approval_id'),
			'disetujui' => array(self::BELONGS_TO, 'Pegawai', 'disetujui_id'),
			'diketahui' => array(self::BELONGS_TO, 'Pegawai', 'diketahui_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tahun' => 'Tahun',
			'bulan' => 'Bulan',
			'kode' => 'Nomor Form',
			'tgl_pengajuan' => 'Tanggal Pengajuan',
			'pegawai_id' => 'Associate',
			'pegawai_nama_lengkap' => 'Nama Associate',
			'dept_divisi_nama' => 'Nama Divisi',
			'tipe_izin_id' => 'Tipe Izin',
			'tgl_mulai' => 'Tanggal Mulai',
			'tgl_akhir' => 'Tanggal Akhir',
			'jumlah_hari' => 'Jumlah Hari',
			'sisa_cuti' => 'Sisa Cuti',
			'jumlah_cuti_bersama' => 'Jumlah Cuti Bersama',
			'alasan' => 'Alasan',
			'attach_dokumen' => 'Attachment',
			'approval_note' => 'Approval Note',
			'disetujui_id' => 'Disetujui',
			'disetujui_nama' => 'Disetujui Nama',
			'disetujui_tgl' => 'Disetujui Tgl',
			'diketahui_id' => 'Diketahui',
			'diketahui_nama' => 'Diketahui Nama',
			'diketahui_tgl' => 'Diketahui Tgl',
			'status_proses' => 'Status Proses',
			'status' => 'Status',
			'cancel_by' => 'Dibatalkan Oleh',
			'cancel_note' => 'Alasan Pembatalan',
			'cancel_date' => 'Tanggal Dibatalkan',
			'created_by' => 'Dibuat Oleh',
			'created_date' => 'Tanggal Dibuat',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('tahun',$this->tahun);
		$criteria->compare('bulan',$this->bulan);
		$criteria->compare('kode',$this->kode,true);
		$criteria->compare('tgl_pengajuan',$this->tgl_pengajuan,true);
		$criteria->compare('pegawai_id',$this->pegawai_id);
		$criteria->compare('pegawai_nama_lengkap',$this->pegawai_nama_lengkap,true);
		$criteria->compare('dept_divisi_nama',$this->dept_divisi_nama,true);
		$criteria->compare('tipe_izin_id',$this->tipe_izin_id);
		$criteria->compare('tgl_mulai',$this->tgl_mulai,true);
		$criteria->compare('tgl_akhir',$this->tgl_akhir,true);
		$criteria->compare('jumlah_hari',$this->jumlah_hari);
		$criteria->compare('sisa_cuti,',$this->sisa_cuti);
		$criteria->compare('jumlah_cuti_bersama,',$this->jumlah_cuti_bersama);
		$criteria->compare('alasan',$this->alasan,true);
		$criteria->compare('attach_dokumen',$this->attach_dokumen,true);
		$criteria->compare('approval_note',$this->approval_note,true);
		$criteria->compare('disetujui_id',$this->disetujui_id);
		$criteria->compare('disetujui_nama',$this->disetujui_nama,true);
		$criteria->compare('disetujui_tgl',$this->disetujui_tgl,true);
		$criteria->compare('diketahui_id',$this->diketahui_id);
		$criteria->compare('diketahui_nama',$this->diketahui_nama,true);
		$criteria->compare('diketahui_tgl',$this->diketahui_tgl,true);
		$criteria->compare('status_proses',$this->status_proses);
		$criteria->compare('status',$this->status);
		$criteria->compare('cancel_by',$this->cancel_by,true);
		$criteria->compare('cancel_note',$this->cancel_note,true);
		$criteria->compare('cancel_date',$this->cancel_date,true);
		$criteria->compare('created_by',$this->created_by,true);
		$criteria->compare('created_date',$this->created_date,true);
		if (!Yii::app()->request->isAjaxRequest) {
            $criteria->order = 'tahun desc,bulan,tgl_mulai';
        }
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchCustom($id) //list izin personal
	{
		$criteria = new CDbCriteria;		
		if($id == 1){
			$criteria->condition= 'tahun='.date('Y').' and uid="'.Yii::app()->user->id.'"' ;
		}elseif ($id == 0){
			$criteria->condition= 'tahun<'.date('Y').' and uid="'.Yii::app()->user->id.'"' ;
		}
		if (!Yii::app()->request->isAjaxRequest) {
            $criteria->order = 'tahun desc,bulan,tgl_mulai';
        }
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
                'pageSize' => 20, 
            ),
			// 'sort'=>array('defaultOrder'=>'aktif,nama'),
		));
	}
	
	public function searchRequest( $id=0 )
	{
        $criteria=new CDbCriteria;	
		
		$atasan = Atasan::model()->find('uid = "'.Yii::app()->user->id.'"' );
		$level = 0; 
		$where = '';
		if( Yii::app()->user->level > 1 && count($atasan)>0 ){ 
			$level = 3; //leader + HR
		}elseif( Yii::app()->user->level == 1 ){ 
			$level = 2; //leader
		}elseif( Yii::app()->user->level > 1 && count($atasan)==0 ){ 
			$level = 1; //HR
		}				
  
		if( $level == 2 ){ 
			// $where = 'tahun='.date('Y').' and p.uid_atasan="'.Yii::app()->user->id.'" and ';
			$where = ' p.uid_atasan="'.Yii::app()->user->id.'" and ';
		}elseif( $level == 1 || $level == 3 ){ 
			// $where = 'tahun='.date('Y').' and ';
			$where = '  ';
		}	
		
		switch ($id) {
			case 0: //pending
				$criteria->condition = $where.' status_proses in (0,1) and status=0 ' ;
				break;
			case 1: //Approved
				$criteria->condition = $where.' status_proses=2 and status=1 ' ;
				break;
			case 2: //Cancelled
				$criteria->condition = $where.' status=2 ' ;
				break; 
		}
		
		$criteria->join = 'inner join pegawai p on t.pegawai_id = p.id';
		$criteria->order = 't.tgl_mulai desc';
		if (!Yii::app()->request->isAjaxRequest) {
            $criteria->order = 't.tgl_mulai desc';
        }
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination' => array(
                'pageSize' => 20, 
            ),
			// 'sort'=>array('defaultOrder'=>'aktif,nama'),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Izin the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
