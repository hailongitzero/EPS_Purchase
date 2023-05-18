(function (cash) {
    "use strict";

    cash('.btn-add-user').on('click', function(){
        axios.post(`add-user`, {
            name: cash('#name').val(),
            department: cash('#department').val(),
            username: cash('#username').val(),
            position: cash('#position').val(),
            email: cash('#email').val(),
            role: cash('#role').val(),
            gender: cash('#gender').val(),
            telephone: cash('#telephone').val(),
        }).then(res => {
            alert(res.data.message);
            window.location.reload();
        }).catch(err => {
            if (err.response.data.errors) {
                for (const [key, val] of Object.entries(err.response.data.errors)) {
                    cash(`#${key}`).addClass('border-theme-6')
                    cash(`#error-${key}`).html(val)
                }
            } else if (err.response.data.message){
                alert(err.response.data.message);
            }
        });
    });

    cash('.btn-delete-user').on('click', function(){
        var sConfirm = confirm("Bạn muốn xoá người dùng này?");
        if (!sConfirm){
            return false;
        }
        var username = cash(this).data('username');

        axios.post(`delete-user`, {
            username: username,
        }).then(res => {
            alert(res.data.message);
            window.location.reload();
        }).catch(err => {
            alert(err);
        });
    });

    cash('#avatar').on("change", function(){
        var url = cash(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (cash(this)[0].files && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")){
            var reader = new FileReader();
            reader.onload = function (e) {
                cash('img.avatar-img').attr('src', e.target.result);
            }
            reader.readAsDataURL(cash(this)[0].files[0]);
        }
    });
    
})(cash);