(function (cash) {
    "use strict";

    cash('#frm_maintenance_setup').on('submit', function(e){
        e.preventDefault();
        
        var formData= new FormData;

        formData.append('_token', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('header', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('id', cash('#id').val());
        formData.append('asset_id', cash('#asset_id').val());
        formData.append('title', cash('#title').val());
        formData.append('supplier_id', cash('#supplier').val());
        formData.append('notes', cash('#notes').val());
        formData.append('start_date', cash('#start_date').val());
        formData.append('completion_date', cash('#completion_date').val());
        formData.append('status', cash('#status').val());
        formData.append('is_warranty', cash('#is_warranty').is(":checked") ? 1 : 0);
        formData.append('cost', cash('#cost').val());

        cash(window['attachment'].files).each(function(id, file){
            formData.append('attachment[]', file);
        });
        axios.post(window.location.origin+'/maintenance-setup', formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                'Accept': 'application/json',
                },
        }).then(res => {
            cash('.success').removeClass('hidden');
            cash('.success .alert').text(res.data.message);
            var itv = 2;
                var itvId = setInterval(function(){
                    if (itv > 0){
                        itv --;
                    } else {
                        clearInterval(itvId);
                        window.location.reload();
                    }
                }, 1000);
        }).catch(err => {
            cash('.failure').removeClass('hidden');
            if (err.response.data.errors) {
                for (const [key, val] of Object.entries(err.response.data.errors)) {
                    cash('.failure ul').append('<li>'+val+'</li>');
                }
            } else if (err.response.data.message){
                cash('.failure ul').append('<li>'+err.response.data.message+'</li>');
            }
        });
        return false;
    });
})(cash);