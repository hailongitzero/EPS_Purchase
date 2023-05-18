import cash from "cash-dom";

(function (cash) {
  "use strict";

    // Show tab content
    cash("body").on("click", '.manage_request a[data-toggle="tab"]', function (key, el) {
        // Set active tab nav
        activeTab(this);
    });

    cash('.notification-link').on('click', function(){
        var tabId = cash(this).data('target');
        if ( tabId ){
            sessionStorage.setItem('activeTab', tabId);
        }
    });

    if ( sessionStorage.getItem('activeTab') ) {
        var ele = cash('#'+sessionStorage.getItem('activeTab'));
        activeTab(ele);
        sessionStorage.setItem('activeTab', false);
    } else {
        const params = new URLSearchParams(location.search);
        if (params.get('tab')){
            var ele = cash('#'+params.get('tab'));
            activeTab(ele);
        }
    }

    function activeTab(ele){
        // Set active tab nav
        cash(ele)
        .closest(".nav-tabs")
        .find('a[data-toggle="tab"]')
        .removeClass("active")
        .attr("aria-selected", false);
        cash(ele).addClass("active").attr("aria-selected", true);

        // Set active tab content
        let elementId = cash(ele).attr("data-target");
        
        cash(elementId).each(function(idx, ele){
            let tabContentWidth = cash(ele).closest(".tab-content").width();
            cash(ele)
                .closest(".tab-content")
                .children(".tab-pane")
                .removeAttr("style")
                .removeClass("active");
            cash(ele)
                .width(tabContentWidth + "px")
                .addClass("active");
        });
    }

})(cash);
