<?php
//Load database variable
$jquery_lib = get_option('wjmc_jquery_lib');

//Set defaults
if($jquery_lib == null) {
	$jquery_lib = '1.12.4';
}

if($jquery_lib == '1.12.4'): ?>
<script src="<?php echo XB_PLUGIN_DIR_URL; ?>jquery-lib/jquery-1.12.4.min.js?version=<?php echo XB_PLUGIN_VERSION; ?>"></script>
<?php endif; if($jquery_lib == '2.2.4'): ?>
<script src="<?php echo XB_PLUGIN_DIR_URL; ?>jquery-lib/jquery-2.2.4.min.js?version=<?php echo XB_PLUGIN_VERSION; ?>"></script>
<?php endif; if($jquery_lib == '3.1.0'): ?>
<script src="<?php echo XB_PLUGIN_DIR_URL; ?>jquery-lib/jquery-3.1.0.min.js?version=<?php echo XB_PLUGIN_VERSION; ?>"></script>
<?php endif; ?>