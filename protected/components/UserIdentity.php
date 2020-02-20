<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	const ERROR_CONNECTION_LDAP = 2;
	const ERROR_UID_EMPTY = 22;
	const ERROR_LEADER_EMPTY = 222;
	private $_id;
    public $userType = 'Usere';
	public $listPermission = array('');
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{		
		Yii::app()->runController('pegawai/updateDOJ');
		$user = str_replace(Yii::app()->params['ldap_domain'],"",$this->username);
		$pass = $this->password;	
		
		$ldap_host		= Yii::app()->params['ldap_host']; 
		$ldap_port      = Yii::app()->params['ldap_port']; 	
		$dn			    = Yii::app()->params['ldap_dn']; 				
		$ldap_dn        = 'uid='.$user.','.$dn;

		$adconn = ldap_connect( $ldap_host , $ldap_port );
		ldap_set_option ($adconn, LDAP_OPT_REFERRALS, 0); 
		ldap_set_option($adconn, LDAP_OPT_PROTOCOL_VERSION, 3) ;
		
		if($ldap_bind = @ldap_bind($adconn)){
			if($bind = @ldap_bind($adconn, $ldap_dn, $pass)) { 
				$attribute = array("uid","co");
				$filter  = '(&(objectclass=inetOrgPerson)(uid='.$user.'))'; 
				$result = ldap_search($adconn, $ldap_dn, $filter, $attribute);
				$res = ldap_get_entries($adconn,$result);				
				$uid = isset($res[0]["uid"][0])?$res[0]["uid"][0]:'';					
				$status = isset($res[0]["co"][0])?$res[0]["co"][0]:'';					
				$this->_id = $uid;	
				
				// $userLDAP = UserLdap::model()->find(' uid="'.$user.'" and status="active" ');			
				if( $status != null || !empty($status) ){	
					$uAdmin = UserAdmin::model()->find('uid="'.$user.'"');
					$atasan = Atasan::model()->find('uid="'.$user.'"');
					$pegawai = Pegawai::model()->find('uid="'.$user.'"');
					//0=staff,1=manager,2=admin 
					if( count($uAdmin) > 0 ){ 
						$this->setState('uid', $uid);
						$this->setState('pwd', $pass);
						$this->setState('level', $uAdmin->level); 
						$this->setState('show_admin_page', $uAdmin->show_admin_page);
						
						$this->setState('show_report', $uAdmin->show_report);
						$this->setState('akses_report', $uAdmin->akses_report); //2=All Division,1=Private Division, 0= Private
						if( $uAdmin->show_report == 1 ){
							$permissions = UserPermission::model()->findAllBySql("select up.name
															from user_role_permission urp
															left join user_permission up on urp.user_permission_id = up.id
															where urp.user_role_id=".$uAdmin->user_role_id ); 								 
							foreach ($permissions as $perm){
								array_push($this->listPermission, $perm->name);
							}					
							$this->setState('permission', $this->listPermission);							
						}						
			
						$this->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']); 
						$this->errorCode = self::ERROR_NONE;	
					}else{
						if( count($pegawai) == 0 ){
							$this->errorCode = self::ERROR_UID_EMPTY;
						/*}elseif( empty($pegawai->nama_atasan) || $pegawai->nama_atasan==null ){
							$this->errorCode = self::ERROR_LEADER_EMPTY;*/
						}elseif( count($atasan) > 0 ){
							$this->setState('level', 1); 
							$this->setState('show_admin_page', 0 ); 
							$this->setState('show_report', 2 );
							$this->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']); 
							$this->errorCode = self::ERROR_NONE;	
						}else{
							$this->setState('level', 0 ); 
							$this->setState('show_admin_page', 0 ); 
							$this->setState('show_report', 3 );
							$this->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']); 
							$this->errorCode = self::ERROR_NONE;	
						}
					}	
					if( count($pegawai) > 0 ){
						$pegawai->last_login = date('Y-m-d H:i:s');
						$pegawai->save();
					}				
				}else{
					$this->errorCode = self::ERROR_USERNAME_INVALID;
				}
				ldap_close($adconn);
				return !$this->errorCode; 
			/*} else if( md5($this->username) == Yii::app()->params['keyA'] 
			        && md5($this->password) == Yii::app()->params['keyB'] ){
				$this->setState('level', 3); 
				$this->setState('userSessionTimeout', time() + Yii::app()->params['sessionTimeoutSeconds']); 
				$this->setState('show_admin_page', 1 ); 
				$this->_id = $user;   
				$this->errorCode=self::ERROR_NONE;					
				return !$this->errorCode; */
			} else {
				$this->errorCode = self::ERROR_USERNAME_INVALID;
				return !$this->errorCode;
			}
			ldap_close($adconn);
		}else{
			$this->errorCode=self::ERROR_CONNECTION_LDAP;
			return !$this->errorCode;
		}
	}
	
	public function getId()
	{
		return $this->_id;
	}   
}