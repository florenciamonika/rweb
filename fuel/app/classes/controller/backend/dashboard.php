<?php
class Controller_Backend_Dashboard extends Controller_Backend
{
	public function action_index() {
		$this->authenticate();
		$this->_data_template['meta_title'] = 'Dashboard';
		$this->_data_template['menu_current_key'] = 'dashboard';
		return Response::forge(View::forge('backend/welcome.twig', $this->_data_template));
	}

	public function action_sign_in() {
		if ($this->admin_auth->is_exists()) {
            // Redirect to dashboard home when admin is already sign in
			Response::redirect(Uri::base().'backend', 'refresh');
		}
		// Sign in process
		if (count(Input::post()) > 0) {
			$is_success = false;
			$user_email = Input::post('email', '');
			$user_pswd = Input::post('password', '');
			
			$current_admin = Model_Admins::query()->where('email', $user_email)->get_one();
			$this->admin_auth->setCurrentAdmin($current_admin);
			
			if ($this->admin_auth->is_exists()) {
				if ($this->admin_auth->check_password($user_pswd)) {
					$is_success = true;
				}
			}
			
			if ($is_success) {
				if (Input::post('chkrememberme')) {
					Session::instance()->set_config('expire_on_close', true);
				}
				
				$session = Session::instance();
				Session::set(Config::get('config_cms.cms_session_name.admin_id'), $current_admin['id']);
				Response::redirect(Uri::base().'backend', 'refresh'); // Redirect to dashboard
			} else {
				$this->_data_template['error_message'] = 'Combination Email and Password is incorrect';
			}
		}
		$this->_data_template['meta_title'] = Config::get('config_cms.cms_name').' | Sign In';
		$this->_data_template['body_tag_class'] = 'bg-black';
		return Response::forge(View::forge('backend/form/sign_in.twig', $this->_data_template));
	}
	
	public function action_sign_out() {
		if ($this->admin_auth->is_exists()) {
			$current_admin = $this->admin_auth->getCurrentAdmin();
			$current_admin->save();
		}
		Session::delete(Config::get('config_cms.cms_session_name.admin_id'));
		Response::redirect(Uri::base().'backend/sign-in', 'refresh');// Redirect to sign in form
	}
	
	public function action_change_current_password() {
		$this->authenticate();
		$this->_do_change_password();
		$this->_data_template['meta_title'] = 'Dashboard';
		$this->_data_template['menu_current_key'] = 'dashboard';
		$this->_data_template['success_message'] = \Session::get_flash('success_message');
		$this->_data_template['error_message'] = \Session::get_flash('error_message');
		return Response::forge(View::forge('backend/form/change_password.twig', $this->_data_template));
	}
	
	private function _do_change_password() {
		$all_post_input = \Input::post();
		$admin_model = $this->admin_auth->getCurrentAdmin();
		if (count($all_post_input)) {
			// Check old password input
			if (!$this->admin_auth->check_password(Input::post('password_old'))) {
				\Session::set_flash('error_message', 'Old Password was wrong');
			} else {
				// Define validation rule
				$val = Validation::forge('change_password');
				$val->add('password_confirm', 'Password Confirm')
					->add_rule('required')
					->add_rule('trim')
					->add_rule('match_value', Input::post('password'), true);
				$val->set_message('match_value', ':label doesn\'t match');
				// Validate input post
				if (!$val->run()) {
					\Session::set_flash('error_message', $val->error('password_confirm')->get_message());
				} else {
					$admin_model->password = \Authentication_Backend::make_password($admin_model->id, $all_post_input['password']);
					// Save with validation, if error then throw the error
					try {
						$admin_model->save();
						\Session::set_flash('success_message', 'Successfully Changed');
					} catch (\Orm\ValidationFailed $e) {
						\Session::set_flash('error_message', $e->getMessage());
					}
				}
			}
			\Response::redirect(\Uri::current());
		}
	}
	
	public function action_my_profile_form() {
		$this->authenticate();
		$this->_do_save_my_profile();
		$this->_data_template['meta_title'] = 'Dashboard';
		$this->_data_template['menu_current_key'] = 'dashboard';
		$this->_data_template['success_message'] = \Session::get_flash('success_message');
		$this->_data_template['error_message'] = \Session::get_flash('error_message');
		return Response::forge(View::forge('backend/form/my_profile.twig', $this->_data_template));
	}
	
	private function _do_save_my_profile() {
		$all_post_input = \Input::post();
		$admin_model = $this->admin_auth->getCurrentAdmin();
		if (count($all_post_input)) {
			// save admin profile
			$admin_model->fullname = \Input::post('txtfullname');
			$admin_model->phone = \Input::post('txtphone');
			try {
				$admin_model->save();
				\Session::set_flash('success_message', 'Successfully Saved');
			} catch (\Orm\ValidationFailed $e) {
				\Session::set_flash('error_message', $e->getMessage());
			}
			\Response::redirect(\Uri::current());
		}
	}

}
