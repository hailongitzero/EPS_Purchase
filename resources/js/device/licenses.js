(function (cash) {
    "use strict";

    cash('#limit_date').on('change', function(){
        if(cash(this).is(':checked')){
            cash(this).closest('.form-check').find('label').text('Có');
            cash('#expiration_date').prop('disabled', false);
        } else {
            cash(this).closest('.form-check').find('label').text('Không');
            cash('#expiration_date').prop('disabled', true);
        }
    });

    cash('#limit_seats').on('change', function(){
        if(cash(this).is(':checked')){
            cash(this).closest('.form-check').find('label').text('Giới hạn');
            cash('#seats').prop('disabled', false);
        } else {
            cash(this).closest('.form-check').find('label').text('Không giới hạn');
            cash('#seats').prop('disabled', true);
        }
    });

    cash('#license_type').on('change', function() {
        if (cash(this).val() == 'user'){
            cash(cash(this).closest('.lisence-control').find('.user_type')).removeClass('hidden');
            cash(cash(this).closest('.lisence-control').find('.asset_type')).addClass('hidden');
        } else {
            cash(cash(this).closest('.lisence-control').find('.user_type')).addClass('hidden');
            cash(cash(this).closest('.lisence-control').find('.asset_type')).removeClass('hidden');
        }
    });

    cash('#tb_deployLicense tbody').on('click', 'td button', function(){
        cash(this).closest('tr').detach();
    });

    cash('#btn_addDeployLicense').on('click', function(){
        var sType = cash('#license_type').val();
        var sId = cash('#license_id').val();
        var sValue = '';
        if (sType == 'user'){
            sValue = cash('#user_type').val();
        } else {
            sValue = cash('#asset_type').val();
        }

        if (!checkExistLicenseDeploy(sType, sId, sValue)){
            return false;
        }

        var formData= new FormData;

        formData.append('_token', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('header', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('type', sType);
        formData.append('id', sId);
        formData.append('value', sValue);

        axios.post(window.location.origin+'/license-check', formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                'Accept': 'application/json',
                },
        }).then(res => {
            var cls = cash('#tb_deployLicense tbody tr').length % 2 == 0 ? 'bg-gray-200 dark:bg-dark-1' : '';
            var sNo = cash('#tb_deployLicense tbody tr').length + 1
            var ele = '<tr class="'+ cls +'">';
            ele += '<td class="border-b dark:border-dark-5">'+ sNo +'</td>';
            ele += '<td class="border-b dark:border-dark-5 type hidden">'+ res.data.deploy['type'] +'</td>';
            ele += '<td class="border-b dark:border-dark-5 id hidden">'+ res.data.deploy['id'] +'</td>';
            ele += '<td class="border-b dark:border-dark-5 deploy_id hidden">'+ res.data.deploy['deploy_id'] +'</td>';
            ele += '<td class="border-b dark:border-dark-5">'+ res.data.deploy['asset_name'] +'</td>';
            ele += '<td class="border-b dark:border-dark-5">'+ res.data.deploy['username'] +'</td>';
            ele += '<td class="border-b dark:border-dark-5">'+ res.data.deploy['department'] +'</td>';
            ele += '<td class="border-b dark:border-dark-5"><button class="btn_removeSeat btn btn-primary ml-auto">Xóa</button></td>';
            ele += '</tr>';
            cash('#tb_deployLicense tbody').append(ele);
        }).catch(err => {
            alert(err.response.data.message);
        });
    });

    cash('#frm_insertLicenses, #frm_updateLicenses').on('submit', function(e){
        e.preventDefault();

        var postLink = '';
        if ( cash(this).attr('id') == 'frm_updateLicenses' ){
            postLink = '/licenses-update';
        } else {
            postLink = '/licenses-insert'
        }

        var formData= new FormData;

        formData.append('_token', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('header', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('name', cash('#name').val());
        formData.append('id', cash('#id').val());
        formData.append('license_name', cash('#license_name').val());
        formData.append('license_email', cash('#license_email').val());
        formData.append('serial', cash('#serial').val());
        formData.append('category', cash('#category').val());
        formData.append('limit_seats', cash('#limit_seats').is(":checked") ? 1 : 0);
        formData.append('seats', cash('#seats').val());
        formData.append('limit_date', cash('#limit_date').is(":checked") ? 1 : 0);
        formData.append('expiration_date', cash('#expiration_date').val());
        formData.append('purchase_date', cash('#purchase_date').val());
        formData.append('cost', cash('#cost').val());
        formData.append('order_no', cash('#order_no').val());
        formData.append('manufacturer', cash('#manufacturer').val());
        formData.append('supplier', cash('#supplier').val());
        formData.append('notes', cash('#notes').val());

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

    cash('#btn_saveDeployLicense').on('click', function(){
        var deployList = [];
        var ele;
        for (var i = 0; i < cash('#tb_deployLicense tbody tr').length; i++ ) {
            ele = cash(cash('#tb_deployLicense tbody tr')[i]);
            deployList.push({
                id: ele.find('td.id').text(),
                type: ele.find('td.type').text(),
                deployId: ele.find('td.deploy_id').text(),
            });
        }

        var formData= new FormData;

        formData.append('_token', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('header', cash('meta[name="csrf-token"]').attr('content'));
        formData.append('deployList', JSON.stringify(deployList));
        axios.post(window.location.origin+'/license-deploy', formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                'Accept': 'application/json',
                },
        }).then(res => {
            window.location.reload();
        }).catch(err => {
        });
    });

    function checkExistLicenseDeploy(sType, sId, sDeployId){
        var ele;
        for (var i = 0; i < cash('#tb_deployLicense tbody tr').length; i++ ) {
            ele = cash(cash('#tb_deployLicense tbody tr')[i]);
            if (ele.find('td.type').text() == sType && ele.find('td.id').text() == sId && ele.find('td.deploy_id').text() == sDeployId ){
                return false;
            }
        }
        return true
    }
    
})(cash);