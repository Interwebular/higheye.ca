<?php

function getActualFieldName($shortcode) {
    $len = strlen($shortcode);
    $field = substr($shortcode, 1, $len-2); //remove % from start and end

    $arr = explode('-', $field);
    $type = $arr[0];

    
    // old form components that is required 
    // starts with [Req] but the actual field name is field name[space]    
    if (substr($arr[1], 0, 5) == '[Req]') {
        $field_name = substr($arr[1], 5) . ' *';
    }
    else {
        $field_name = $arr[1];        
    }    
    
    switch ($type) {
        case 'shorttext';
            $newtype = 'short-text';
            break;
        case 'phone';
            $newtype = 'phone';
            break;
        case 'longtext';
            $newtype = 'long-text';
            break;
        case 'radio';
            $newtype = 'radio';
            break;
        case 'dp';
            $newtype = 'dp';
            break;        
        case 'cb';
            $newtype = 'cb';
            break;        
        case 'field':
            $newtype = 'field';
            break;
    }       
    
    return array('type' => $newtype, 'value' => $field_name);
}
/**
 * 
 * @param type $shortcode %name%
 * @param type $post
 * @return type
 */
function getShortcodeValue($shortcode, $post) {
    $len = strlen($shortcode);
    $field = substr($shortcode, 1, $len-2); //remove % from start and end
	
    $arr = explode('-', $field);
    $type = $arr[0];
    
    // old form components that is required 
    // starts with [Req] but the actual field name is field name[space]    
    if (substr($arr[1], 0, 5) == '[Req]') {
        $field_name = substr($arr[1], 5) . ' *';
    }
    else {
        $field_name = $arr[1];        
    }
    
    switch ($type) {
        case 'shorttext';
            $value = $post['short-text'][$field_name];
            break;
        case 'phone';
            $value = $post['phone'][$field_name][1] . '-' . $post['phone'][$field_name][2] . '-' . $post['phone'][$field_name][3];
            break;
        case 'longtext';
            $value = $post['long-text'][$field_name];
            break;
        case 'radio';
            $value = $post['radio'][$field_name];
            break;
        case 'dp';
            $value = $post['dp'][$field_name];
            break;        
        case 'cb';
            $value = $post['cb'][$field_name];
            break;        
        case 'field':
            $value = $post['field'][$field_name];
            break;
    }       

    return $value;
}