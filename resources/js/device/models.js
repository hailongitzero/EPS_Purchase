(function (cash) {
    "use strict";
    cash('#model_img').on("change", function(){
        var url = cash(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (cash(this)[0].files && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")){
            var reader = new FileReader();
            reader.onload = function (e) {
                cash('img.model-img').attr('src', e.target.result);
            }
            reader.readAsDataURL(cash(this)[0].files[0]);
        }
    });
})(cash);