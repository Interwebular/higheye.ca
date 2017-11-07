<?php
/**
 * FRAMEWORK 1.0.0
 */
add_action('wp_head', 'child_theme_custom_fonts');

function child_theme_custom_fonts()
{
    global $child_theme_custom_fonts;
?>
<style type="text/css"><?php
    foreach ($child_theme_custom_fonts as $c) {
    echo "
        @font-face {
          font-family: '" .$c['name']. "';
          src: url(" . $c['src'] . ");
        }";
    } ?>
</style><?php
}

if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'featured-wide', 1000, 400, true ); //(cropped)
    add_image_size( 'excerpt-icon-200', 200, 200, true ); //(cropped)
    add_image_size( 'excerpt-icon-300200', 300, 200, true ); //(cropped)
    add_image_size( 'excerpt-icon-100', 100, 100, true ); //(cropped)
    add_image_size( 'excerpt-icon-10050', 100, 100, true ); //(cropped)
    add_image_size( 'excerpt-icon-50', 50, 50, true ); //(cropped)	
}

function df_excerpt_length( $length ) {
	return 200;
}

add_filter( 'excerpt_length', 'df_excerpt_length', 999 );


add_action('admin_footer', 'styleAdmin');

function styleAdmin()
{
    global $child_theme_admin_css;
?>
	<style type="text/css">
            <?php echo $child_theme_admin_css ?>
	</style>
<?php
}

function childThemeCustomStyle()
{
    $bgSlider = ChildThemeOptions::get('bgSlider');    
?>
        <style type="text/css">
            
        </style>
<?php
}

    
    

$c = new ChildThemeOptions(array('title' => 'Child Theme Options', 'slug' => 'child-theme-options'));

class ChildThemeOptions
{
    protected $_copyrightText;
    protected $_slug;
    private $_title;
    private $_values = array();
    
    function __construct($args = array())
    {
        $this->_title = $args['title'];
        $this->_slug = $args['slug'];
        add_action('admin_menu', array($this, 'child_submenu'), 11);
        $this->_values = get_option('elegant-child-options');
        add_shortcode('custom-theme', array($this, 'customThemeShortCode'));
    
    }

    
    function customThemeShortCode($atts, $content)
    {
        $value = $this->_getElementValue($atts['name']);
        return $value;
    }
    
    public static function replaceText($text)
    {
        $arr[] = array('find' => '#url', 'replace' =>get_bloginfo('wpurl'));
        $arr[] = array('find' => '[year]', 'replace' =>date('Y'));
		
        foreach ($arr as $replace) {
            $text = str_replace($replace['find'], $replace['replace'], $text);
        }

        return $text;
    }
    
    /** get data - from front end **/
    public static function get($field)
    {
        $options = get_option('elegant-child-options');
        return do_shortcode(stripslashes(ChildThemeOptions::replaceText($options[$field])));
    }
    
    function child_submenu()
    {
        add_submenu_page('themes.php', $this->_title, $this->_title, 'administrator', $this->_slug, array($this, 'options'));
    }

    function getStandardFooter(){
        $contact = get_option('lssc-contact');
		//echo htmlentities('&copy [year] <a href=&#8220;#url&#8220;>' . $contact['name'] . ', ' . $contact['city'] . ', ' . $contact['state'] . '</a> | Powered by <a href=&#8220;http://wordjack.com&#8220;>WordJack Media</a>');
		//echo "<div style='border-left: 3px solid #24ff00; background-color: #ffffff; padding: 1px 0 30px 10px;'><h4>Copyright Format</h4>" . "<span style='background-color: #D2FFD3;'>" . htmlentities("© 2015 <a href='/'>Wordjack, Collingwood, ON</a> | Powered by <a href='http://wordjack.com'>WordJack Media</a>") . "</span></div>";
	}
    
    function options()
    {
        global $child_theme_options;
        $fields = $child_theme_options;
        ?>
        <div class="wrap">
            <?php
            if ($_POST['submit'] == 1) {
                update_option('elegant-child-options', $_POST['data']);
                $this->_values = get_option('elegant-child-options');
                
            }
            
            ?>
            <h2>Divi Child Options</h2>
			<style>
			.save-info p {
				color: #000000;
				background-color: #C5FFA2;
				border-left: 3px solid #24ff00;
				padding: 8px;
				font-weight: bold;
			}
			</style>
			
			<script type="text/javascript">
				var toggle_content = function() {
					
					setTimeout(function() {
					   jQuery('.save-info').css("display", "none");
				   }, 5000);
				   
				}
				// Launch function after window load and after ajax request
				jQuery(window).load(toggle_content);
				//jQuery(document).ajaxSuccess(toggle_content);
			</script>
			<?php 
			$save_info = '';
			if($_POST['is_submit'] == '1') {
				$save_info = "Settings Saved!";
			}

			if($_POST['is_submit']== '1'):?>
			<div class="save-info"><p><?php echo $save_info; ?></p></div>
			<?php
			endif;
			
			echo "<div style='border-left: 3px solid #FF9E00; background-color: #ffffff; padding: 1px 0 30px 10px;'><h4>Copyright Format</h4>" . "<span style='background-color: #fff000;'>" . htmlentities("<a href='/'>Business Name, City, State</a>") . "</span></div>";
			?>
			<style>
			input[type=checkbox], input[type=radio] {
				width: 20px;
				height: 20px;
				margin: 10px 0px 0 10px;
				border-radius: 3px;
				border: 1px solid #20486F;
				background-color: #D2FFD3;
			}
			input[type=checkbox]:checked:before {
				font-size: 25px;
			}
			</style>
            <form name="custom" method="post" action="" class="child-theme-options">
                <input type="hidden" name="submit" value="1" />
                <?php
                    foreach ($fields as $idx => $details) {

                        echo '<div class="form-row"><label style="border-left: 3px solid #24ff00; background-color: #20486F; padding: 10px; color: #ffffff;">' . '<span style="color: #fff000;">' . $details['label'] . '</span>' . ' [custom-theme name="' . $idx . '"]</label>';
                        if ($details['type'] == 'wp-editor') {
                            wp_editor($this->_getElementValue($idx), $idx, array('textarea_name' => 'data[' . $idx . ']'));
                            
                        }
                        else {
                            echo $this->_formatElement($idx, $details['type'], $this->_getElementValue($idx));
                        }                           
                        echo '</div>';
                    }
                    
                    echo '<hr /><div class="child_theme_options_notes">' . $this->getStandardFooter() . '</div>';
                ?>
            <input type="hidden" value="1" name="is_submit">
            <div class="submit"><input class="button-primary" type="submit" value="Save" /></div>
            </form>
        </div>
        <?php
    }
    
    private function _formatElement($name, $type, $value = '')
    {
        switch ($type) {
            case 'textarea':
                return '<textarea name="data[' . $name . ']">' . $value . '</textarea>';
            case 'text':
                return '<input type="text" name="data[' . $name . ']" value="' . $value . '">';
            case 'checkbox':
                return '<input type="checkbox" name="data[' . $name . ']" ' . (($value == 'on') ? 'checked="checked"' : '') . '">';
                
        }
    }
    
    /** admin side */
    private function _getElementValue($idx) {
        if (is_array($this->_values)) {
             return stripcslashes($this->_values[$idx]);
        }
        return '';
    }
    
}
