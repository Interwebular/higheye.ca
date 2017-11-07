$jx = jQuery.noConflict();

$jx(document).ready(function(){
    $jx('.sortable-socials').sortable();
	$jx('#lssc_options_btn').off('click').on('click', function(){
		$jx('.lssc_hidden_options').slideDown();
	});
	$jx('#fs-socialbuttons .clear_btn, #fs-contactinfo .clear_btn').off('click').on('click', function(e){
		$jx($jx($jx(e.target).parent()[0]).children()[1]).val('');
	});
});