<?php
/*
Manual Plugin Updater
Version 1.0
Author: Virson Ebillo
Website: https://virson.wordpress.com/
Notes: This class uses jQuery and Wordpress file system handler, unzip method and the ABSPATH constant. Wordpress is required to work with this php class.
Usage:
1.	Include the file by using the include() method.

2.	//Instatiate the class and place the values inside it.
	new manualPluginUpdater(
		$string_version,	//String: Current plugin version in string format: 1.1.1
		$plugin_base_path,	//String: Current plugin base path in string format like: home\public_html\root\wp-content\plugins\plugin-dir-name/
		$uploaded_file,	//String: File upload POST method name attribute like <input type="file" name="file"/> where "file" is the name attribute.
		$plugin_dir_name	//String: Plugin dir name like "plugin-dir-name".
	);

*/

class manualPluginUpdater {
	
	//Define access properties
	private $string_version;
	private $plugin_base_path;
	private $uploaded_file;
	private $plugin_dir_name;
	
	//Define variable properties
	function __construct($string_version, $plugin_base_path, $uploaded_file, $plugin_dir_name) {
		$this->string_version = $string_version;
		$this->plugin_base_path = $plugin_base_path;
		$this->uploaded_file = $_FILES[''. $uploaded_file .''];
		$this->plugin_dir_name = $plugin_dir_name;
		$this->init_manual_plugin_updater();
	}
	
	//Define the init function
	function init_manual_plugin_updater() {
		
		//Checks if plugin base dir path is writable
		if( !wp_is_writable($this->plugin_base_path) ) {
			
			echo "
			<div class='error mpu_notice permission_error'>
				<p><span>Warning:</span> File permissions for the dir path: <b class='error_info_writable'>" . $this->plugin_base_path . "</b> is incorrect! <b>Manual Plugin Updater</b> might not work properly.</p>
			</div>
			";
			
		}
		
		//Checks if the plugins dir path is writable
		if( !wp_is_writable(ABSPATH . "wp-content/plugins/") ) {
			echo "
			<div class='error mpu_notice permission_error'>
				<p><span>Warning:</span> File permissions for the dir path: <b class='error_info_writable'>" . ABSPATH . "wp-content/plugins/</b> is incorrect! <b>Manual Plugin Updater</b> might not work properly.</p>
			</div>
			";
		}
		
		if( wp_is_writable($this->plugin_base_path) ) {
			
			//Read file with read and write permissions
			$handle = fopen($this->plugin_base_path . 'version.txt', 'w+');

			//Write the version string to the file
			fwrite($handle, $this->string_version);

			//Closes the file that was opened.
			fclose($handle);
			
		}
		
		//String version num to Int version num
		$string_version = explode('.', $this->string_version);
		$string_version_imploded = implode('', $string_version);
		$version_current_int = intval($string_version_imploded);
		
		//Define file info
		$uploaded_file = $this->uploaded_file;
		$file_name = $uploaded_file['name'];
		$file_format = $uploaded_file['type'];

		if( isset($file_name) && !empty($file_name) ) {
			
			//Include Wordpress File Handler
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			
			//Define upload override for wp_handle_upload()
			$upload_overrides = array( 'test_form' => false );
			
			//Handle uploaded file
			$movefile = wp_handle_upload( $uploaded_file, $upload_overrides );
			
			//Define uploaded file path
			$zipped_file_path = $movefile['file'];

			//Open archive and then read content of version.txt
			//$version_target_string = @file_get_contents('zip://'. $zipped_file_path . $this->plugin_dir_name);
			
			if( $file_format == 'application/x-zip-compressed' ) {
				
				//Define dir paths
				$wp_content_dir = ABSPATH . "wp-content/";
				$tmp_dir = ABSPATH . "wp-content/mpu_tmp/";
				
				if( !wp_is_writable($wp_content_dir) ) {
					
					//Deletes the file uploaded
					unlink($zipped_file_path);
					
					echo "
					<div class='error mpu_notice'>
						<p>Update Failed! File permissions for the file path: <b class='error_info_writable'>" . $wp_content_dir . "</b> is incorrect!</p>
					</div>
					";
					
				} else {
					
					//Create the temp directory path with 775 permissions prior to plesk servers
					$mkdir = mkdir($tmp_dir, 0775);
					
					//Checks if dir path is created
					if ( $mkdir ){
						
						//Load Wordpress File system handler
						WP_Filesystem();
						$unzip_tmp_file = unzip_file($zipped_file_path, $tmp_dir);
						
						if( $unzip_tmp_file ){
							
							//Search the directory and return the array key
							$array_search = array_search($this->plugin_dir_name, scandir($tmp_dir));
							
							//Define function that removes the entire directory including the directories and files in it
							function rrmdir($tmp_dir) {
								if (is_dir($tmp_dir)) {
									$objects = scandir($tmp_dir);
									foreach ($objects as $object) {
										if ($object != "." && $object != "..") {
											if (is_dir($tmp_dir."/".$object)) {
											rrmdir($tmp_dir."/".$object);
											} else {
											unlink($tmp_dir."/".$object);
											}
										}
									}
									rmdir($tmp_dir);
								}
							}
							
							//Perform quick check 
							if($array_search) {
								
								//Get the version.txt content
								$target_version_string = @file_get_contents($tmp_dir . scandir($tmp_dir)[$array_search] . '/version.txt', FILE_USE_INCLUDE_PATH);
								
								//Run the function to delete the entire tmp directory
								rrmdir($tmp_dir);
								
								//String version num to Int version num
								$target_string_exploded = explode('.',$target_version_string);
								$target_string_imploded = implode('', $target_string_exploded);
								$version_target_int = intval($target_string_imploded);
								
								//Begin Checking
								if ($version_current_int == $version_target_int) {
									
									//Deletes the file uploaded
									unlink($zipped_file_path);
									
									echo "
									<div class='updated mpu_notice'>
										<p>Plugin is already up to date. No need to update!</p>
									</div>
									";

								} elseif ($version_current_int < $version_target_int) {
									
									//Define uncompression path
									$dir = ABSPATH . "wp-content/plugins/";
									   
									//Checks if destination path is writable
									if ( wp_is_writable( $dir ) ){
										
										//Uncompress the archive file
										$unzipfile = unzip_file( $zipped_file_path, $dir);
										
										if ( $unzipfile ) {
											//Deletes the file uploaded
											unlink($zipped_file_path);
											
											echo "
											<div class='updated mpu_notice'>
												<p>Successfully updated the Plugin!</p>
											</div>
											";
											?>
												<script>
													jQuery(document).ready(function(){
														setTimeout(function(){
														window.location.replace('<?php echo $_SERVER['HTTP_REFERER']; ?>');
														}, 2000);
													});
												</script>
											<?php
										
										} else {
											//Deletes the file uploaded
											unlink($zipped_file_path);
											
											echo "
											<div class='error mpu_notice'>
												<p>Update Failed! Zip file extraction is not Enabled.</p>
											</div>
											";
											
										}
										
									} else {
										
										//Deletes the file uploaded
										unlink($zipped_file_path);
										
										echo "
										<div class='error mpu_notice'>
											<p>Update Failed! File permissions for the file path: <b class='error_info_writable'>" . $dir . "</b> is incorrect!</p>
										</div>
										";
									
									}
								   
								} elseif ( $version_target_int == 0 ) {
									
									//Deletes the file uploaded
									@unlink($zipped_file_path);
									
									echo "
									<div class='error mpu_notice'>
										<p>Incorrect Plugin File! Please upload the correct Plugin File in Zipped format. <span style='color: #ff0000;'>Error Code:</span> Could not find the version.txt file.</p>
									</div>
									";
									
								} elseif ($version_current_int > $version_target_int) {
									
									//Deletes the file uploaded
									unlink($zipped_file_path);
									
									echo "
									<div class='error mpu_notice'>
										<p>Update Failed! The plugin uploaded is older than the current plugin installed.</p>
									</div>
									";
									
								} else {
									
									echo "
									<div class='error mpu_notice'>
										<p>End of code execution. Please notify the <b>Plugin Developer</b> to resolve this issue.</p>
									</div>
									";
									
								}
								
							} else {

								//Deletes the file uploaded
								unlink($zipped_file_path);
								
								echo "
								<div class='error mpu_notice'>
									<p>Incorrect Plugin File! Please upload the correct Plugin File in Zipped format. <span style='color: #ff0000;'>Error Code:</span> Could not find the plugin folder from the given plugin directory name.</p>
								</div>
								";
								
								//Run the function to delete the entire directory
								rrmdir($tmp_dir);
							}
							
						} else {
							
							//Deletes the file uploaded
							unlink($zipped_file_path);
							
							//Deletes the temp dir path
							rmdir($tmp_dir);
							
							echo "
							<div class='error mpu_notice'>
								<p>Update Failed! Zip file extraction is not enabled in the server!</p>
							</div>
							";
							
						}
						
					} else {
						//Deletes the file uploaded
						unlink($zipped_file_path);
						
						echo "
						<div class='error mpu_notice'>
							<p>The directory path <b class='error_info_writable'>" . $tmp_dir . "</b> could not be created and returned an error due to incorrect file permissions!</p>
						</div>
						";
						
					}
					
				}
				
			} else {
				//Deletes the file uploaded
				unlink($zipped_file_path);
				echo "
				<div class='error mpu_notice'>
					<p>Incorrect Plugin File! Please upload the correct Plugin File in Zipped format. <span style='color: #ff0000;'>Error Code:</span> The file is not a ancrhive (ZIP) format.</p>
				</div>
				";
			}
			
		}
		
		if( isset($file_name) && empty($file_name) ) {
			
			echo "
			<div class='error mpu_notice'>
				<p>No File Choosen! Please choose a file to upload.</p>
			</div>
			";
			
		}
		
		?>
			<style>
			.mpu_notice {
				font-weight: normal;
				transition: all 0.5s;
				-webkit-transition: all 0.5s;
				-moz-transition: all 0.5s;
				-ms-transition: all 0.5s;
			}
			.mpu_notice span {
				font-weight: 600;
				color: #ff0000;
			}
			.error_info_writable {
				color: #FF5722;
			}
			</style>
			<script>
			jQuery(document).ready(function(){
				
				function init_interval() {
					var counter = 2,
						interval = setInterval(function(){
							
							if(counter == parseInt(counter/2)*2 ) {
								jQuery('.permission_error').css({
									boxShadow: '#dc3232 0 0 20px'
								});
							} else {
								jQuery('.permission_error').css({
									boxShadow: '0 1px 1px 0 rgba(0,0,0,0.1)'
								});
							}
							
							counter = counter + 1;
							
							if(counter == 10) {
								clearInterval(interval);
								init_interval();
							}
						
					}, 500);
				}
				
				//Checks if wrapper element is rendered
				if( jQuery('div').is('.permission_error') ) {
					init_interval();
				}
				
			});
			</script>
		<?php
		
	}//init function wrapper
	
}//class wrapper
?>