<?php
class Controller_Backend extends Controller
{
	protected $_data_template = array(
		'meta_title' => '',
		'body_tag_class' => 'skin-blue',
		'html_tag_class' => '',
		'cms_menus' => array(),
		'menu_parent_key' => '',
		'menu_current_key' => '',
	);
	protected $admin_auth;
	
	public function before() {
		parent::before();
		$this->admin_auth = new Authentication_Backend( //Set Authentication Backend Instance
			Model_Admins::find(\Session::get(Config::get('config_cms.cms_session_name.admin_id'))) //By Admin ID from session
		);
		if ($this->admin_auth->is_exists()) {
			$this->_data_template['cms_menus'] = $this->_get_cms_menus();
			$this->_data_template['current_admin_fullname'] = $this->admin_auth->getCurrentAdmin()->fullname;
			$this->_data_template['current_admin_phone'] = $this->admin_auth->getCurrentAdmin()->phone;
		}
	}
	
	public function authenticate() {
		if (!$this->admin_auth->is_exists()) {
			// Redirect to signin form when admin isn't sign in
			Response::redirect(Uri::base().'backend/sign-in', 'refresh');
		}
	}
	
	private function _get_cms_menus() {
		$menus = \Config::get('config_cms.menus');
		if ($this->admin_auth->getCurrentAdmin()->superadmin != 1) {
			// Filter menu by role permission
			foreach ($menus as $key => $menu) {
				if ($menu['permission']) {
					if (!$this->check_menu_permission($key, 'read')) {
						unset($menus[$key]);
					}
				}
				if (isset($menu['submenus'])) {
					foreach ($menu['submenus'] as $key_sub => $submenu) {
						if (!$this->check_menu_permission($key_sub, 'read')) {
							unset($menus[$key]['submenus'][$key_sub]);
						}
					}
					if (count($menus[$key]['submenus']) == 0) {
						unset($menus[$key]);
					}
				}
			}
		}
		return $menus;
	}
	
	protected function check_menu_permission($menu_key, $access_name) {
		$has_access =  false;
		if ($this->admin_auth->getCurrentAdmin()->superadmin == 1) {
			$has_access =  true;
		} else {
			$role_id = $this->admin_auth->getCurrentAdmin()->role->role_id;
			$menu_permission = \Model_RolesPermission::query()
				->where('role_id', $role_id)
				->where('menu_key', $menu_key)
				->where('access_'.$access_name, 1)
				->get_one();
			if (!empty($menu_permission)) {
				$has_access = true;
			}
		}
		return $has_access;
	}
	
	// Rearrange sequance of model which has field "seq"
	protected function rearrange_seq($model, $instance, $custom_model=null) {
		if (is_null($custom_model)) {
			$afftected_list = $model->query()
				->where('seq', '>=', $instance->seq)
				->where('id', '!=', $instance->id)
				->order_by('seq')
				->get();
		} else {
			$afftected_list = $custom_model;
		}
		$next_seq = $instance->seq + 1;
		$afftected_cnt = 0;
		foreach ($afftected_list as $afftected_item) {
			if ($afftected_item->seq < $next_seq) {
				$afftected_item->seq = $next_seq;
				$afftected_item->save();
				$afftected_cnt++;
			}
			$next_seq++;
		}
		\Session::set_flash('success_message', 'Rearrange is afftect for '.$afftected_cnt.' items');
	}
        
        /*
         * Check admin who can access their own data
         * Filter by created_at field
        */
        protected function check_limitation_by_admin() {
            // Special privilege for admin who has access for "admin_user" and "admin_role_permission"
            if ($this->admin_auth->getCurrentAdmin()->superadmin == 1) {
                return FALSE;
            }
            $role_id = $this->admin_auth->getCurrentAdmin()->role->role_id;
            $permissions = \Model_RolesPermission::query()
                    ->where('role_id', $role_id)
                    ->where_open()
                        ->where('menu_key', 'admin_user')
                    ->where_close()
                    ->where('access_create', 1)
                    ->where('access_read', 1)
                    ->where('access_update', 1)
                    ->where('access_delete', 1)
                    ->get();
            // return TRUE if admin user don't have special priviledge
            return count($permissions) == 2 ? FALSE : TRUE;
        }
}
