<?php
class Controller_Backend_Install extends Controller_Backend
{
	public function action_setup() {
		// Redirect to signin form when admin is already sign in
		if ($this->admin_auth->is_exists()) {
			Response::redirect(Uri::base().'backend', 'refresh');
		}
		// check input POST if exists then create superadmin
		if (count(Input::post()) > 0) {
			$this->_create_superadmin();
		}
		// Check superadmin if exists
		$superadmin_count = \Users\Model_Members::query()->where('role_id', 1)->count();
		if ($superadmin_count > 0) {
			Response::redirect(Uri::base().'backend/sign-in', 'refresh');
		}
		// Open superadmin registration form
		$this->_data_template['meta_title'] = Config::get('config_cms.cms_name').' | Admin Registration';
		$this->_data_template['body_tag_class'] = 'bg-black';
		$this->_data_template['txtfullname'] = Input::post('fName');
		$this->_data_template['txtphone'] = Input::post('phone');
		$this->_data_template['txtemail'] = Input::post('email');
		return Response::forge(View::forge('backend/form/superadmin.twig', $this->_data_template, FALSE));
	}
	
	private function _create_superadmin() {
		try {
			$val = Validation::forge('role_id');
			$val->add('password_confirm', 'Password Confirm')
				->add_rule('required')
				->add_rule('trim')
				->add_rule('match_value', Input::post('password'), true);
			$val->set_message('match_value', ':label doesn\'t match.');
			
			if (!$val->run()) {
				$this->_data_template['error_message'] = $val->error('password_confirm')->get_message();
			} else {
				$admin = \Users\Model_Members::forge();
				$admin->fName = Input::post('fName');
				$admin->phone = Input::post('phone');
				$admin->email = Input::post('email');
				$admin->password = Input::post('password');
				$admin->role_id = 2;
				$admin->save();
				// Encrypt password then save again
				$admin->password = Authentication_Backend::make_password($admin['id'], $admin->password);
				$admin->save();
			}
		} catch (Orm\ValidationFailed $e) {
			$this->_data_template['error_message'] = $e->getMessage();
		}
	}
}
