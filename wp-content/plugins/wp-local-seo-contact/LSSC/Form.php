<?php

class LSSC_Form
{
	public function __construct()
	{

	}
	
	public function display_text_element($name, $options = array())
	{
		if($name == 'logo') {
			return '<p><label>' . $options['label'] . ' <span style="color: #ff0000; font-style: italic; font-weight: 600;" >(Required)</span></label> <input style="width: 100%; max-width: 300px;" class="' . $name . '" type="text" name="' . $name . '" value="' . $options['value'] . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="sample.com/sample-logo.png"> <span class="clear_btn">Clear</span></p>';
		} else {
			return '<p><label>' . $options['label'] . '</label> <input class="' . $name . '" type="text" name="' . $name . '" value="' . $options['value'] . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"></p>';
		}
	}
	
	public function display_social_element($name, $options = array()) {
		$element_type = strpos($name, 'icon');
		
		if( $element_type ) {
			return '<p><label>' . $options['label'] . '</label> <input class="' . $name . '" type="text" name="' . $name . '" value="' . $options['value'] . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="sample.com/sample-logo.png"> <span class="clear_btn">Clear</span></p>';
		} else {
			return '<p><label>' . $options['label'] . '</label> <input class="' . $name . '" type="text" name="' . $name . '" value="' . $options['value'] . '" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="sample.com/sample-link"> <span class="clear_btn">Clear</span></p>';
		}
	}
	
	public function display_phone_element($name, $options = array())
	{
		return '<p class="el-phone"><label>' . $options['label'] . '</label> 
			<input type="text" name="' . $name . '[1]" value="' . $options['value'][1] . '">
			<input type="text" name="' . $name . '[2]" value="' . $options['value'][2] . '">
			<input type="text" name="' . $name . '[3]" value="' . $options['value'][3] . '">
			<input type="text" name="' . $name . '[4]" value="' . $options['value'][4] . '">
			</p>';
	}
	
	function display_cb_element($name, $options = array())
	{
		return '<p><label>' . $options['label'] . '</label> <input type="checkbox" name="' . $name . '"' . (($options['value'] == 'on') ? 'checked="checked"' : '') . '></p>';
	}
	
	function dislay_day_operation($name, $options)
	{
		$html = '<td><input type="checkbox" name="' . $name . '[is-open]" ' . (($options['value']['is-open'] == 'on') ? 'checked="checked"' : '') . '></td>';
		$html .= '<td><input type="checkbox" name="' . $name . '[is-hide]" ' . (($options['value']['is-hide'] == 'on') ? 'checked="checked"' : '') . '></td><td>' . $options['label'] . ':</td>';
		$html .= '<td><input type="text" name="' . $name . '[opens]" value="' . $options['value']['opens'] . '"></td>';
		$html .= '<td><input type="text" name="' . $name . '[closes]" value="' . $options['value']['closes'] . '"></td>	';
		return $html;
	}
}