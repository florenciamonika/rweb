<?php
namespace MediaUploader;

class Controller_Task extends \Controller_Backend
{
	private $_module_url = 'backend/media-uploader/task';
	private $_menu_key = 'media_uploader_task';
	
	public function before() {
		parent::before();
		$this->authenticate();
		// Check menu permission
		if (!$this->check_menu_permission($this->_menu_key, 'read')) {
			// if not have an access then redirect to error page
			\Response::redirect(\Uri::base().'backend/no-permission');
		}
		$this->_data_template['meta_title'] = 'Media Uploader - Task';
		$this->_data_template['menu_parent_key'] = 'media_uploader';
		$this->_data_template['menu_current_key'] = $this->_menu_key;
	}
	
	public function action_index() {

		$this->_data_template['success_message'] = \Session::get_flash('success_message');
		$this->_data_template['error_message'] = \Session::get_flash('error_message');
		
		$this->_data_template['media_uploader_subtitle'] = 'Task';
		$this->_data_template['form_action_url'] = \Uri::base().$this->_module_url.'/upload';
		$this->_data_template['information_text'] = '
			<ul>
				<li>Max filesize is 1 MB</li>
			</ul>
		';
		
		return \Response::forge(\View::forge('backend/jquery_image_uploader.twig', $this->_data_template, FALSE));
	}
	
	public function action_upload() {
		\Package::load('jqueryfileupload');
		$options = array(
			'script_url' => \Uri::base().$this->_module_url.'/upload',
			'upload_dir' => DOCROOT.'media/task/',
                        'upload_url' => \Uri::base().'media/task/',
			'max_file_size' => 1024 * 1024, // 1 MB
			'image_versions' => array(
				'' => array(
					'auto_orient' => false,
				),
				'thumbnail' => array(
					'crop' => false,
					'max_width' => 200,
					'max_height' => 200,
				)
			),
		);
		$upload_handler = new \JqueryFileUploadHandler($options);
	}
}

