(function (cash) {
    "use strict";

    // modal 
    cash('#modal_edit').on("click", ".close_modal_edit", function (e) {
        var edtId = cash('#modal_edit').find('#edit_editor').data("content");
        if (edtId && window.requestData){
            window.requestData[edtId] = edit_editor.getData();
        } else {
            edit_editor.setData("");
        }
      });

    // show view content modal
    cash('.btn_view_editor').on('click', function(){
        var viewId = cash(this).data('content');
        if (window.requestData && window.requestData[viewId]) {
            view_editor.setData(window.requestData[viewId]);
        } else {
            view_editor.setData("");
        }
      });
  
      // show editor content modal
      cash('.btn_editor').on('click', function(){
        var viewId = cash(this).data('content');
        cash('#edit_editor').attr('data-content', viewId.replace("/\"/g", ""));
        
        if (window.requestData && window.requestData[viewId]) {
          edit_editor.setData(window.requestData[viewId]);
        } else {
          edit_editor.setData("");
        }
      });
})(cash);