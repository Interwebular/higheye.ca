var cmr = jQuery.noConflict();
function init_code_mirror () {
	
	CodeMirror.fromTextArea(document.getElementById('equalize'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true
	});
	
	var chrome_event = CodeMirror.fromTextArea(document.getElementById('chrome'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	//Get change event and then place the scroll info to the a hidden field
	chrome_event.on('change', function(){
		var chrome_event_array = chrome_event.getScrollInfo();
		jQuery('#chrome_event').val( chrome_event_array['left'] + ',' + chrome_event_array['top'] );
	});
	
	
	CodeMirror.fromTextArea(document.getElementById('firefox'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('internet_explorer'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('safari'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('general'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('extra_large'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('large_1'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('large'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('medium'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('small'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('generic_mobile'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('ipad'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('nexus'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
	CodeMirror.fromTextArea(document.getElementById('ipod'), {
		mode: "text/css",
		theme: "ambiance",
		lineNumbers: true,
		matchBrackets: true,
		autoRefresh: true,
		autoCloseBrackets: true,
		styleActiveLine: true,
		selectionPointer: true,
		gutters: ["CodeMirror-lint-markers"],
		lint: true
	});
	
}
cmr(window).load(init_code_mirror);