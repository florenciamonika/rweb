<?php
class Controller_Mama extends Controller
{
	public function before() {
		// Initialize Config CMS
		$config_basic = \Model_Settings::filter_basic()->get();
		if (count($config_basic)) {
			foreach($config_basic as $config_data) {
				\Config::set('config_basic.'.$config_data->setting_name, $config_data->setting_value);
			}
		}
	}
	
	// Fetch config data from database if any
	protected function fetch_config_data($config_name) {
		$config_app = \Model_Settings::query()
			->where('setting_type', $config_name)
			->get();
		if (count($config_app)) {
			foreach($config_app as $config_data) {
				\Config::set($config_name.'.'.$config_data->setting_name, $config_data->setting_value);
			}
		}
	}
}
