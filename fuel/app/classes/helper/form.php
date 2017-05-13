<?php
class Helper_Form
{
	/**
	* get base url for backend
	* @return
	* string value
	*/
	public static function horizontal_form_input($param) {
		$label = '';
		$input = '';
		
		if (isset($param['label'])) {
			$param_label = $param['label'];
			$label = Form::label($param_label['label'], $param_label['id'], $param_label['attributes']);
		}
		
		if (isset($param['input'])) {
			$param_input = $param['input'];
			
			$input_content = Form::input($param_input['name'], $param_input['value'], $param_input['attributes']);
			
			$input = html_tag('div', array('class' => $param_input['container_class']), $content = $input_content);
		}
		
		if (isset($param['select'])) {
			$param_input = $param['select'];
			
			$input_content = Form::select($param_input['name'], $param_input['value'], $param_input['options'], $param_input['attributes']);
			
			$input = html_tag('div', array('class' => $param_input['container_class']), $content = $input_content);
		}
		
		if (isset($param['textarea'])) {
			$param_input = $param['textarea'];
			
			$input_content = Form::textarea($param_input['name'], $param_input['value'], $param_input['attributes']);
			
			$input = html_tag('div', array('class' => $param_input['container_class']), $content = $input_content);
		}
		
		if (isset($param['file'])) {
			$param_input = $param['file'];
			
			$input_content = Form::file($param_input['name'], $param_input['attributes']);
			
			$input = html_tag('div', array('class' => $param_input['container_class']), $content = $input_content);
		}
		
		if (isset($param['select_image_picker'])) {
			$param_input = $param['select_image_picker'];
			
			$input_class = isset($param_input['attributes']['class']) ? $param_input['attributes']['class'] : '';
			$input_required = isset($param_input['attributes']['required']) ? 'required' : '';
			$input_content = '<select id="'.$param_input['name'].'" class="'.$input_class.'" name="'.$param_input['name'].'" '.$input_required.'>';
			if (isset($param_input['options']['thumbnail'.DS])) {
				foreach ($param_input['options']['thumbnail'.DS] as $data) {
					$input_selected = (isset($param_input['value']) && strcmp($param_input['value'], $data) == 0) ? 'selected' : '';
					$data_img_label = strlen($data) > 0 ? $data : 'No Image';
					$input_content .= '<option data-img-src="'.$param_input['image_url'].'thumbnail/'.$data.'" value="'.$data.'" '.$input_selected.' data-img-label="'.$data_img_label.'">'.$data.'</option>';
				}
			}
			$input_content .= '</select>';
			
			$input = html_tag('div', array('class' => $param_input['container_class']), $content = $input_content);
		}
		
		$result = html_tag('div', array('class' => 'form-group'), $content = $label.$input);
		
		return $result;
	}
}