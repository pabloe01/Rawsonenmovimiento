
jQuery('map').imageMapResize();
jQuery('Submap').imageMapResize();

jQuery(document).load(function() {
    jQuery('map').imageMapResize();
    jQuery('Submap').imageMapResize();
//jQuery(function(){jQuery(".scroll").click(function(){jQuery("html,body").animate({scrollTop:jQuery("#target").offset().top},"500");return false})})
});

jQuery(document).ready(function() {
    jQuery('map').imageMapResize();
    jQuery('Submap').imageMapResize();
//jQuery(function(){jQuery(".scroll").click(function(){jQuery("html,body").animate({scrollTop:jQuery("#target").offset().top},"500");return false})})
});

jQuery("area#imgpopup").click(function(e) {
    e.preventDefault();
    PreviewImage(jQuery(this).attr('href'));
});

PreviewImage = function(uri) {

    //Get the HTML Elements
    imageDialog = jQuery("#idialog");
    imageTag = jQuery('#image_popup');

    uri = ajax_script.url + '/' + uri;

    //Split the URI so we can get the file name
    uriParts = uri.split("/");

    //Set the image src
    imageTag.attr('src', uri);

    //When the image has loaded, display the dialog
    imageTag.load(function() {

        jQuery('#idialog').dialog({
            modal: true,
            resizable: true,
            draggable: true,
            width: 'auto',
            maxWidth: '80%',
            maxHeight: '80%',
            title: ''
        });
        //jQuery("..ui-dialog-titlebar").hide();

        jQuery("#ui-dialog-title-dialog").hide();
        jQuery(".ui-dialog-titlebar").removeClass('ui-widget-header');

//        jQuery("#idialog .ui-dialog-titlebar").css({
//            "background-color": "transparent",
//            "border": "0px none"
//        });

    });
}

function cargaContenido(category) {
// Coloco un mensaje mientras se reciben los datos
    jQuery('#' + 'mainpro').removeClass("procesando");
    jQuery('#' + 'mainpro').addClass("procesando");
    jQuery('#' + 'info').html('');

    jQuery.ajax({
        url: ajax_script.ajaxurl,
        type: 'GET',
        data: {
            action: 'cargaContenido',
            category: category
        },
        success: function(results)
        {
            jQuery('#' + 'info').html(results);

            jQuery('Submap').imageMapResize();
            jQuery("area#imgpopup").click(function(e) {
                e.preventDefault();
                PreviewImage(jQuery(this).attr('href'));
            });

            jQuery('#' + 'mainpro').removeClass("procesando");
//            this.scroll(0,1000);
//            var y = jQuery('#content').scrollTop();
            jQuery('#content').scrollTop(y + 1000);


            //window.location.href = '#div_post';

        }
    });


}




