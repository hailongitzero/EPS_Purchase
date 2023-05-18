export const format = [
    {
        id: 'txt_name',
        type: 'text',
        field: 'requester',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'name',
            value: 'username'
        }
    },
    {
        id: 'txt_telephone',
        type: 'text',
        field: 'requester',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'telephone',
            value: 'telephone'
        }
    },
    {
        id: 'txt_handler',
        type: 'text',
        field: 'handler',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'name',
            value: 'name'
        }
    },
    {
        id: 'txt_department',
        type: 'text',
        field: 'department',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'department_name',
            value: 'department_id'
        }
    },
    {
        id: 'txt_create_dt',
        type: 'date',
        field: 'created_at',
        check: false,
        has_sub: false
    },
    {
        id: 'txt_complete_dt',
        type: 'date',
        field: 'complete_date',
        check: false,
        has_sub: false
    },
    {
        id: 'txt_handle_dt',
        type: 'date',
        field: 'handle_date',
        check: false,
        has_sub: false
    },
    {
        id: 'txt_extend_dt',
        type: 'date',
        field: 'extend_to',
        check: false,
        has_sub: false
    },
    {
        id: 'txt_request_type',
        type: 'text',
        field: 'type',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'type_name',
            value: 'request_type'
        }
    },
    {
        id: 'dpk_complete_dt',
        type: 'datepicker',
        field: 'complete_date',
        check: false,
        has_sub: false
    },
    {
        id: 'cbx_request_tp',
        type: 'select',
        field: 'type',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'type_name',
            value: 'request_type'
        }
    },
    {
        id: 'txt_cc_mail',
        type: 'text',
        field: 'cc_email',
        check: false,
        has_sub: false
    },
    {
        id: 'txt_resource',
        type: 'text',
        field: 'src_tp',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'resource_name',
            value: 'resource_type'
        }
    },
    {
        id: 'txt_cost',
        type: 'text',
        field: 'cost',
        check: false,
        has_sub: false
    },
    {
        id: 'txt_final_resource',
        type: 'text',
        field: 'fn_src_tp',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'resource_name',
            value: 'resource_type'
        }
    },
    {
        id: 'txt_final_cost',
        type: 'text',
        field: 'final_cost',
        check: false,
        has_sub: false
    },
    {
        id: 'cbx_final_resource',
        type: 'select',
        field: 'fn_src_tp',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'resource_name',
            value: 'resource_type'
        }
    },
    {
        id: 'inp_final_cost',
        type: 'input',
        field: 'final_cost',
        check: false,
        has_sub: false
    },
    {
        id: 'txt_subject',
        type: 'text',
        field: 'subject',
        check: false,
        has_sub: false
    },
    {
        id: 'txt_assign_person',
        type: 'text',
        field: 'assign',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'name',
            value: 'username'
        }
    },
    {
        id: 'btn_edt_assign',
        type: 'edit_editor',
        field: 'assign_content',
        var_name: 'assign_content',
        check: false,
        has_sub: false
    },
    {
        id: 'txt_handler',
        type: 'text',
        field: 'handler',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'name',
            value: 'username'
        }
    },
    {
        id: 'cbx_handler',
        type: 'select',
        field: 'handler',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'name',
            value: 'username'
        }
    },
    {
        id: 'acd_request_content',
        type: 'html',
        field: 'content',
        check: false,
        has_sub: false,
    },
    {
        id: 'acd_request_assign_content',
        type: 'html',
        field: 'assign_content',
        check: false,
        has_sub: false,
    },
    {
        id: 'acd_request_handler_content',
        type: 'html',
        field: 'handle_content',
        check: false,
        has_sub: false,
    },
    {
        id: 'acd_request_sub_hander_content',
        type: 'html',
        field: 'sub_handler',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'name',
            value: 'username'
        }
    },
    {
        id: 'acd_request_extend_content',
        type: 'html',
        field: 'extend_content',
        check: false,
        has_sub: false,
    },
    {
        id: 'txt_sub_handler',
        type: 'text_arr',
        field: 'sub_handler',
        check: false,
        has_sub: true,
        sub_data: {
            field: 'name',
            value: 'username'
        }
    },
    {
        id: 'txt_attachment',
        type: 'file',
        field: 'files',
        check: true,
        has_sub: true,
        sub_data: {
            field: 'file_name',
            value: 'store_url'
        }
    },
    {
        id: 'assign_attachment',
        type: 'attachment',
        field: 'files',
        check: false,
        has_sub: false
    },
]