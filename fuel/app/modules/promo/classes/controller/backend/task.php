<?php

namespace Promo;

class Controller_Backend_Task extends \Controller_Backend {

    private $_module_url = 'backend/task';
    private $_menu_key = 'task';

    public function before() {
        parent::before();
        $this->authenticate();
        // Check menu permission
        if (!$this->check_menu_permission($this->_menu_key, 'read')) {
            // if not have an access then redirect to error page
            \Response::redirect(\Uri::base() . 'backend/no-permission');
        }
        $this->_data_template['meta_title'] = 'Task';
        $this->_data_template['menu_parent_key'] = 'task';
    }

    public function action_index() {

        $this->_data_template['task_list'] = Model_Task::find('all');
        $this->_data_template['success_message'] = \Session::get_flash('success_message');
        $this->_data_template['error_message'] = \Session::get_flash('error_message');
        return \Response::forge(\View::forge('promo::backend/list_task.twig', $this->_data_template, FALSE));
    }

    public function action_form($id) {
        // find model by id
        $the_model = Model_Task::find($id);
        // if empty then define empty model
        if (empty($the_model)) {
            // The ID "0" is only for add new thing, if greater than 0 then its mean edit thing
            if ($id > 0) {
                \Session::set_flash('error_message', 'The Task with ID "' . $id . '" is not found here');
                \Response::redirect(\Uri::base() . $this->_module_url);
            }
            $the_model = Model_Task::forge();
        }
        $this->_save_setting_data($the_model);
        $this->_data_template['content_header'] = 'Task';
        $this->_data_template['content_subheader'] = 'Form';
        $this->_data_template['breadcrumbs'] = array(
            array(
                'label' => 'Task',
                'link' => \Uri::base() . $this->_module_url
            ),
            array(
                'label' => 'Form'
            )
        );
        
        $promo_data = \Promo\Model_Promo::get_as_array();
        
        $this->_data_template['form_data'] = $the_model->get_form_data_basic($promo_data);
        $this->_data_template['cancel_button_link'] = \Uri::base() . $this->_module_url;
        $this->_data_template['success_message'] = \Session::get_flash('success_message');
        return \Response::forge(\View::forge('backend/form/basic.twig', $this->_data_template, FALSE));
    }

    private function _save_setting_data($the_model) {
        $all_post_input = \Input::post();
            if (count($all_post_input)) {
                // Check menu permission
                $access_name = ($the_model->id > 0) ? 'update' : 'create';
                if (!$this->check_menu_permission($this->_menu_key, $access_name)) {
                        // if not have an access then redirect to error no permission page
                        \Response::redirect(\Uri::base().'backend/no-permission');
                }
                $the_model->promo_id = isset($all_post_input['promo_id']) ? $all_post_input['promo_id'] : null;
                $the_model->name = $all_post_input['name'];
                $the_model->description = isset($all_post_input['description']) ? $all_post_input['description'] : null;
                $the_model->image = isset($all_post_input['image_filename']) ? $all_post_input['image_filename'] : null;
                $the_model->type = $all_post_input['type'];
                $the_model->point = $all_post_input['point'];
                $the_model->status = isset($all_post_input['status']) ? $all_post_input['status'] : null;
                // Set created_by/updated_by
                if ($the_model->id > 0) {
                        $the_model->updated_by = $this->admin_auth->getCurrentAdmin()->id;
                } else {
                        $the_model->created_by = $this->admin_auth->getCurrentAdmin()->id;
                }
                // Save with validation, if error then throw the error
                try {
                        $the_model->save();
                        \Session::set_flash('success_message', 'Successfully Saved');
                        \Response::redirect(\Uri::current());
                } catch (\Orm\ValidationFailed $e) {
                        $this->_data_template['error_message'] = $e->getMessage();
                }
        }
    }

    public function action_delete($id) {
        // Check menu permission
        if (!$this->check_menu_permission($this->_menu_key, 'delete')) {
            // if not have an access then redirect to no permission page
            \Response::redirect(\Uri::base() . 'backend/no-permission');
        }
        // find model by id
        $the_model = Model_Task::find($id);
        // if empty then redirect back with error message
        if (empty($the_model)) {
            \Session::set_flash('error_message', 'The Task with ID "' . $id . '" is not found here');
            \Response::redirect(\Uri::base() . $this->_module_url);
            exit;
        }
        // Delete the admin
        try {
            $the_model->delete();
            \Session::set_flash('success_message', 'Delete Task "' . $the_model->name . '" with ID "' . $id . '" is successfully');
        } catch (Orm\ValidationFailed $e) {
            \Session::set_flash('error_message', $e->getMessage());
        }
        \Response::redirect(\Uri::base() . $this->_module_url);
    }

}
