var $jx = jQuery.noConflict();

$jx(document).ready(function()
{
    $jx('#fields ul.form-fields').sortable();
    $jx('#add-field').click(function(){
        type = $jx('#field-type').val();
        fieldLabel = $jx('#field-id').val();
	fieldId = fieldLabel.replace(/[^a-z0-9]+/gi, "");

        if (fieldId == '') {
            return false;
        }

        $iRequiredField = '<span class="is-required"><input type="checkbox" name="field[' + fieldId + '][is-required]" title="Required?" /></span>';
        $deleteField = '<span class="delete"><button id="delete-' + fieldId + '" class="delete-field">Delete</button></span>';
        switch(type) {
            case 'text':
                ret = $deleteField + '<span class="fieldtype">Text</span>' + $iRequiredField + '<input type="text" value="' + fieldLabel + '" placeholder="label" name="field[' + fieldId + '][label]">';                
                break;
            case 'checkbox':
                ret = $deleteField + '<span class="fieldtype">Checkbox</span>' + $iRequiredField + '<input type="text" value="' + fieldLabel + '" placeholder="label"  name="field[' + fieldId + '][label]">';
                ret += '<textarea name="field[' + fieldId + '][options]"></textarea>';
                break;
            case 'radio':
                ret = $deleteField + '<span class="fieldtype">Radio</span>' + $iRequiredField + '<input type="text" value="' + fieldLabel + '" placeholder="label"  name="field[' + fieldId + '][label]">';
                ret += '<textarea name="field[' + fieldId + '][options]"></textarea>';
                
                break;
            case 'dropdown':
                ret = $deleteField + '<span class="fieldtype">Dropdown</span>' + $iRequiredField + '<input type="text" value="' + fieldLabel + '" placeholder="label"  name="field[' + fieldId + '][label]">';
                ret += '<textarea name="field[' + fieldId + '][options]"></textarea>';
                break;
            case 'textarea':
                ret = $deleteField + '<span class="fieldtype">Textarea</span>' + $iRequiredField + '<input type="text" value="' + fieldLabel + '" placeholder="label"  name="field[' + fieldId + '][label]">';                             
                break;
            case 'email':
                ret = $deleteField + '<span class="fieldtype">Email</span>' + $iRequiredField + '<input type="text" value="' + fieldLabel + '" placeholder="label"  name="field[' + fieldId + '][label]">';                             
                break;
            case 'phone':
                ret = $deleteField + '<span class="fieldtype">Phone</span>' + $iRequiredField + '<input type="text" value="' + fieldLabel + '" placeholder="label"  name="field[' + fieldId + '][label]">';                             
                break;            
        }
        ret += '<input type="hidden" value="' + type + '" name="field[' + fieldId + '][type]" />';
        $jx('#fields ul.form-fields').append('<li id="item-' + fieldId + '">' + ret + '</li>');
        
        $jx('#fields ul.form-fields').sortable({
            stop: function(event, ui){
                /*var cnt = 1;
                $jx(this).children('li').each(function(){
                    $jx(this).children('input').val(cnt);
                    cnt++;
                });*/
            }
        });
        $jx('#field-id').val('');
        return false;
    });
    $jx('.date-picker').datepicker({
        dateFormat : 'dd-mm-yy'
    });
    
    $jx('.cb-ongoing').live('click', function(){
        isOngoing = $jx(this).is(":checked");
        expiryDate = $jx('.expiry-date').val();

        if (isOngoing == true) {
            $jx('.expiry-date').attr('disabled', 'disabled');
        }
        else {
            $jx('.expiry-date').removeAttr('disabled');
        }
        
        if (expiryDate == '' && isOngoing != true) {
            $jx('.expiry-date-note').html('This page is viewable by direct link. \n\
                To make it active, enter an expiry date or check ongoing checkbox');
        }
        else {
            $jx('.expiry-date-note').html('');
        }
             
    });
    
    isOngoing = $jx('.cb-ongoing').is(":checked");
    expiryDate = $jx('.expiry-date').val();
    //console.log(isOngoing + ' | ' + expiryDate);

    if (expiryDate == '' && isOngoing != true) {
        $jx('.expiry-date-note').html('This page is viewable by direct link. \n\
            To make it active, enter an expiry date or check ongoing checkbox');
    }
    
    $jx('.expiry-date').live('change', function(){
        expiryDate = $jx(this).val();
        isOngoing = $jx('.cb-ongoing').is(":checked");
        
        if (expiryDate == '' && isOngoing != true) {
            $jx('.expiry-date-note').html('This page is viewable by direct link. \n\
                To make it active, enter an expiry date or check ongoing checkbox');
        }
        else {
            $jx('.expiry-date-note').html('');
        }        
    });
    
    $jx('.delete-field').live('click', function(){
        if (confirm('Are you sure you want to delete this field?')) {
            $id = $jx(this).attr('id');
            $arr = $id.split('-');
            $fieldId = $arr[1];
            $jx(this).parents('li').remove();
        }
       return false; 
    });
});