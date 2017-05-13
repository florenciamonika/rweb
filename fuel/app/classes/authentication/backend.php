<?php
class Authentication_Backend {
	private $current_admin;
	const USER_SALT = '9627364872349';
	
	public function __construct($user) {
		if (empty($user)) {
			$user = Model_Admins::forge();
		}
		$this->current_admin = $user;
	}
	
	public function setCurrentAdmin($user) {
		$this->current_admin = $user;
	}
	
	public function getCurrentAdmin() {
		return $this->current_admin;
	}
	
	public function is_exists() {
		$result = false;
		if ($this->current_admin['id'] > 0) {
			// inactive admin will be regarded as not exists
			if ($this->current_admin['status'] == 1) {
				$result = true;
				// each admin must have role, except superadmin
				if ($this->current_admin['superadmin'] != 1) {
					if (empty($this->current_admin['role'])) {
						$result = false;
					}
				}
			}
		}
		return $result;
	}


	public function check_password($pswd) {
		$result = false;
		if (strcmp(self::make_password($this->current_admin['id'], $pswd), $this->current_admin['password']) == 0) {
			$result = true;
		}
		return $result;
	}
	public static function make_password($db_id, $pswd) {
		// Make Salt from user table id
		$salt = self::USER_SALT.$db_id;
		$hashed = '';
		for ($i = 0; $i < 987; $i++) {
			$hashed = hash('sha256', $hashed.$pswd.$salt, TRUE);
		}
		$digest = base64_encode($hashed);
		return $digest;
	}
}