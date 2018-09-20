(function($) {
    $('figure.wp-caption.aligncenter').removeAttr('style');
    // target any figure that has 2 classes
    // "wp-caption" & "aligncenter"
    // and remove the style attribute
    $('img.aligncenter').wrap('<figure class="centered-image" />');
    // find any image that has the class "aligncenter"
    // then take that image and wrap a figure
    // with a class of "centered-image" around it
}) (jQuery); 

