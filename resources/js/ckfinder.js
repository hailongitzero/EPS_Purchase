(function (cash) {
    "use strict";
    if (cash('#ckfinder-widget-manual').length) {
        CKFinder.widget('ckfinder-widget-manual', {
            width: '100%',
            height: 700,
            id: 'manual_document',
        });
    }
})(cash);
