(function (cash) {
    "use strict";
    cash('#asset_img').on("change", function(){
        var url = cash(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (cash(this)[0].files && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")){
            var reader = new FileReader();
            reader.onload = function (e) {
                cash('img.asset-img').attr('src', e.target.result);
            }
            reader.readAsDataURL(cash(this)[0].files[0]);
        }
    });

    if (cash('#cost').length) {
        cost.addEventListener("input", format, false);
        
        function format (){
            let val = +cost.value;
            
            if (document.querySelector("#cost_format")){
                document.querySelector("#cost_format").textContent =  val.toLocaleString('fullwide', {maximumFractionDigits:2, style:'currency', currency:'VND', useGrouping:true});
            }
        }
    }

    cash('#frm_updateAssets, #frm_insertAssets').on('submit', function(e){
        e.preventDefault();
        
        var postLink = '';
        if ( cash(this).attr('id') == 'frm_updateAssets' ){
            postLink = '/asset-update';
        } else {
            postLink = '/asset-insert'
        }
        var formData= new FormData;

        formData.append('_token', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('header', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('id', cash('#id').val());
        formData.append('name', cash('#name').val());
        formData.append('asset-tag', cash('#asset-tag').val());
        formData.append('serial', cash('#serial').val());
        formData.append('model', cash('#model').val());
        formData.append('quantity', cash('#quantity').val());
        formData.append('unit', cash('#unit').val());
        formData.append('cost', cash('#cost').val());
        formData.append('warranty', cash('#warranty').val());
        formData.append('status', cash('#status').val());
        formData.append('supplier', cash('#supplier').val());
        formData.append('order_no', cash('#order_no').val());
        formData.append('purchase_dt', cash('#purchase_dt').val());
        formData.append('requestable', cash('#requestable').is(":checked") ? 1 : 0);
        formData.append('location', cash('#location').val());
        formData.append('notes', cash('#notes').val());
        formData.append('clone_asset_img', cash('#clone_asset_img').val());
        formData.append('asset_img', cash('#asset_img')[0].files[0]);

        cash(window['attachment'].files).each(function(id, file){
            formData.append('attachment[]', file);
        });
        axios.post(window.location.origin+postLink, formData, {
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

    cash('.assigned').on('change', function(){
        if (cash(this).val() != ""){
            cash(this).closest('.assets-item').data('status', 'U');
        } else {
            cash(this).closest('.assets-item').data('status', '');
        }
    });

    cash('#btn_deploy').on('click', function(){
        var assets = [];
        cash('.assets-item').each(index => {
            var item = cash(cash('.assets-item')[index]);
            if (item.data('status') == "U"){
                var id = item.find('.asset-id').val();
                var assigned_id = item.find('.assigned').val();
                var serial = item.find('.serial').val();
                var asset_tag = item.find('.asset_tag').val();
                assets.push({
                    id: id,
                    assigned_id: assigned_id,
                    serial: serial,
                    asset_tag: asset_tag,
                });
            }
        });

        var formData= new FormData;

        formData.append('_token', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('header', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('assets', JSON.stringify(assets));
        axios.post(window.location.origin+'/assets-deploy', formData, {
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
    });

    cash('.btn_recall').on('click', function(){
        var item = cash(this).closest('.assets-item');
        var id = cash(item).find('.asset-id').val();
        console.log(id);
        axios.post(window.location.origin+'/assets-recall', {
            id: id,
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
    });

    cash('.btn_recall_cancel').on('click', function(){
        var item = cash(this).closest('.assets-item');
        var id = cash(item).find('.asset-id').val();

        axios.post(window.location.origin+'/assets-recall-cancel', {
            id: id,
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
    });
    
})(cash);