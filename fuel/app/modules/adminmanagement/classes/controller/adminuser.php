<?php
namespace AdminManagement;

class Controller_AdminUser extends \Controller_Backend
{
	private $_module_url = 'backend/admin-user';
	private $_menu_key = 'admin_user';
	
	public function before() {
		parent::before();
		$this->authenticate();
		// Check menu permission
		if (!$this->check_menu_permission($this->_menu_key, 'read')) {
			// if not have an access then redirect to error page
			\Response::redirect(\Uri::base().'backend/no-permission');
		}
		$this->_data_template['meta_title'] = 'Admin User';
		$this->_data_template['menu_parent_key'] = 'admin_management';
		$this->_data_template['menu_current_key'] = 'admin_user';
	}
	
	public function action_index() {
		$this->_data_template['admin_list'] = \Model_Admins::query()->get();
		$this->_data_template['success_message'] = \Session::get_flash('success_message');
		$this->_data_template['error_message'] = \Session::get_flash('error_message');
		return \Response::forge(\View::forge('adminmanagement::list/admin_user.twig', $this->_data_template, FALSE));
	}
	
	public function action_form($admin_id) {
		// find admin model by admin id
		$admin_model = \Model_Admins::find($admin_id);
		// if empty then define empty admin model
		if (empty($admin_model)) {
			// Admin ID "0" is only for add new admin, if greater than 0 then its mean edit admin
			if ($admin_id > 0) {
				\Session::set_flash('error_message', 'The Admin with ID "'.$admin_id.'" is not found here');
				\Response::redirect(\Uri::base().$this->_module_url);
			}
			$admin_model = \Model_Admins::forge();
		}
		$this->_save_setting_data($admin_model);
		$this->_data_template['content_header'] = 'Admin User';
		$this->_data_template['content_subheader'] = 'Form';
		$this->_data_template['breadcrumbs'] = array(
			array(
				'label' => 'Admin Management'
			),
			array(
				'label' => 'Admin User',
				'link' => \Uri::base().$this->_module_url
			),
			array(
				'label' => 'Form'
			)
		);
		$this->_data_template['form_data'] = $admin_model->get_form_data_basic();
		$this->_data_template['cancel_button_link'] = \Uri::base().$this->_module_url;
		$this->_data_template['success_message'] = \Session::get_flash('success_message');
		return \Response::forge(\View::forge('backend/form/basic.twig', $this->_data_template, FALSE));
	}
	
	private function _save_setting_data($admin_model) {
		$all_post_input = \Input::post();
		if (count($all_post_input)) {
			// Check menu permission
			$access_name = ($admin_model->id > 0) ? 'update' : 'create';
			if (!$this->check_menu_permission($this->_menu_key, $access_name)) {
				// if not have an access then redirect to error page
				\Response::redirect(\Uri::base().'backend/no-permission');
			}
			$admin_model->email = $all_post_input['email'];
			$admin_model->fName = $all_post_input['fullname'];
			$admin_model->phone = $all_post_input['phone'];
			$admin_model->status = $all_post_input['status'];
			$admin_model->superadmin = $all_post_input['superadmin'];
//			$admin_model->lock_count = 0;
			$admin_model->password = $this->_get_default_password();
			// Save with validation, if error then throw the error
			try {
				$admin_model->save();
				// Encrypt admin password
				$admin_model->password = \Authentication_Backend::make_password($admin_model->id, $admin_model->password);
				$admin_model->save();
				\Session::set_flash('success_message', 'Successfully Saved');
				\Response::redirect(\Uri::current());
			} catch (\Orm\ValidationFailed $e) {
				$this->_data_template['error_message'] = $e->getMessage();
			}
		}
	}
	
	private function _get_default_password() {
		return \Config::get('config_cms.admin_default_password');
	}
	
	public function action_delete($admin_id) {
		// Check menu permission
		if (!$this->check_menu_permission($this->_menu_key, 'delete')) {
			// if not have an access then redirect to error page
			\Response::redirect(\Uri::base().'backend/no-permission');
		}
		// find admin model by admin id
		$admin_model = \Model_Admins::find($admin_id);
		// if empty then redirect back with error message
		if (empty($admin_model)) {
			\Session::set_flash('error_message', 'The Admin with ID "'.$admin_id.'" is not found here');
			\Response::redirect(\Uri::base().$this->_module_url);
			$admin_model = \Model_Admins::forge();
		}
		// Delete the admin
		try {
			$admin_model->delete();
			\Session::set_flash('success_message', 'Delete Admin with email "'.$admin_model->email.'" is successfully');
		} catch (Orm\ValidationFailed $e) {
			\Session::set_flash('error_message', $e->getMessage());
		}
		\Response::redirect(\Uri::base().$this->_module_url);
	}
	
	public function action_reset_password($admin_id) {
		// Check menu permission
		if (!$this->check_menu_permission($this->_menu_key, 'update')) {
			// if not have an access then redirect to error page
			\Response::redirect(\Uri::base().'backend/no-permission');
		}
		// find admin model by admin id
		$admin_model = \Model_Admins::find($admin_id);
		// if empty then redirect back with error message
		if (empty($admin_model)) {
			\Session::set_flash('error_message', 'The Admin with ID "'.$admin_id.'" is not found here');
			\Response::redirect(\Uri::base().$this->_module_url);
			$admin_model = \Model_Admins::forge();
		}
//		// Reset password to default
		try {
			$admin_model->password = \Authentication_Backend::make_password($admin_model->id, $this->_get_default_password());
			$admin_model->save();
			
			\Session::set_flash('success_message', 'Reset Admin Password with email "'.$admin_model->email.'" is successfully');
		} catch (Orm\ValidationFailed $e) {
			\Session::set_flash('error_message', $e->getMessage());
		}
		\Response::redirect(\Uri::base().$this->_module_url);
	}
}

