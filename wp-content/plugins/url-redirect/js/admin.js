jQuery(document).ready(function () {
    jQuery('.url_redirect_delete_form').submit(function(e) {
        if ((!confirm('Are you sure you want to delete the field?'))) {
            return false;
        }
    });
    
    jQuery('#url_redirect_name').keyup(function() {
        var val = jQuery('#url_redirect_name').val();
        jQuery('#url_redirect_name').val( val.replace(" ","-") );

    });

    
    jQuery('.url_redirect_reset_form').submit(function(e) {
        if ((!confirm('Are you sure you want to reset the counter?'))) {
            return false;
        }
    });

    jQuery('#url_redirect_name').change(function () {

        jQuery.each(aName, function (key, value) {
            if (value.name == jQuery('#url_redirect_name').val()) {
                jQuery('#url_redirect_link').val(value.link);

                jQuery('#url_redirect_submit').val('Modify');
                jQuery('#url_redirect_name').prop('readonly', true);
                jQuery('#url_redirect_cancel').show();
            }
        })
    })

    jQuery('.edit').click(function () {
        jQuery(this).parent().parent().css('border', 'solid 1x red');
        name = jQuery(this).attr('data-name');
        link = jQuery(this).attr('data-link');

        jQuery('#url_redirect_name').val(name);
        jQuery('#url_redirect_link').val(link);

        jQuery('#url_redirect_submit').val('Modify');
        jQuery('#url_redirect_name').prop('readonly', true);
        jQuery('#url_redirect_cancel').show();
    });

    jQuery('#url_redirect_cancel').click(function () {
        jQuery('#url_redirect_name').val('');
        jQuery('#url_redirect_link').val('');
        jQuery('#url_redirect_submit').val('Save');

        jQuery('#url_redirect_name').prop('readonly', false);
        jQuery('#url_redirect_cancel').hide();
    });
})