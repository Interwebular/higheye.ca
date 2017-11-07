<?php
	if($_POST['reactivate'] == 1) {
		deactivate_plugins( 'xbrowser-compatibility/xbrowser-compatibility.php' );
		activate_plugin( 'xbrowser-compatibility/xbrowser-compatibility.php' );
		update_option('wjmc_plugin_reactivate', XB_PLUGIN_VERSION);
		?>
		<script>
			jQuery(document).ready(function(){
				jQuery('#upgrade_refresh').append('<p style="border-left: 3px solid #4CAF50;" class="notice_upgrade"><b>Refreshing... Please wait!</b></p>');
				setTimeout(function(){
				window.location.replace('<?php echo $_SERVER['HTTP_REFERER']; ?>');
				}, 2000);
			});
		</script>
		<?php
	}

?>
<div id="upgrade_refresh"></div>
<p class="notice_upgrade"><b>The xBrowser plugin was updated. Please reactivate!</b></p>
<form action="" method="post">
	<input type="hidden" name="reactivate" value="1"/>
	<input class="button button-primary" type="submit" value="Reactivate xBrowser Plugin"/>
</form>