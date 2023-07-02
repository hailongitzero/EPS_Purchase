import { each } from "lodash";
import { tns } from "tiny-slider/src/tiny-slider";

(function (cash) {
    "use strict";

    // make Request Slider
    if (cash(".make-request-item").length) {
        var slider = tns({
            container: '.make-request-item',
            slideBy: "page",
            loop: false,
            mouseDrag: false,
            autoplay: false,
            controls: false,
            nav: false,
            autoHeight: true,
            speed: 500,
        });
        
        cash('.next-button').each(function () {
            cash(this).on("click", function () {
                var curInfo = slider.getInfo(),
                curIndex = curInfo.index;
                
                //submit request
                // validate data
                if ( !fn_reqDataValidate(curIndex) ) {
                    return false;
                }
                // submit data
                fn_submitRequest();
                
                slider.goTo('next');
            });
        });

        slider.events.on('transitionEnd', function(){
            if (cash('#tns1-mw').length){
                cash('#tns1-mw').css('height', 'auto')
            }
        });

        cash('.prev-button').each(function () {
            cash(this).on("click", function () {
                slider.goTo('prev');
            });
        });
    }

    /*
    * 
    * Validate new request form
    *
    */
    function fn_reqDataValidate(slideIndex) {
        var sReturn = true;

        var requester = cash('#requester').val();
        var email = cash('#email').val();
        var department = cash('#department').val();
        var telephone = cash('#telephone').val();
        var priority = cash('#priority').val();
        var completion_date = cash('#completion_date').val();
        var request_tp = cash('#request_tp').val();
        var resource = cash('#resource').val();
        var cost = parseInt(cash('#cost').val().replace(/[^0-9]/gi, ''), 10);
        var req_cc = cash('#request_tp option:checked').data('cc-mail-check');
        var cc_email = cash('#cc_email').val();
        var subject = cash('#subject').val();
        var content = ckRequestContent.getData();
        
        if (!requester.length){
            cash('#requester').addClass('border-theme-24');
            cash('#requester').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#requester').removeClass('border-theme-24');
            cash('#requester').parent('div.form-control').find('label').removeClass('text-theme-24');
        }

        if (!email.length){
            cash('#email').addClass('border-theme-24');
            cash('#email').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#email').removeClass('border-theme-24');
            cash('#email').parent('div.form-control').find('label').removeClass('text-theme-24');
        }

        if (!department.length){
            cash('#department').addClass('border-theme-24');
            cash('#department').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#department').removeClass('border-theme-24');
            cash('#department').parent('div.form-control').find('label').removeClass('text-theme-24');
        }

        if (!telephone.length){
            cash('#telephone').addClass('border-theme-24');
            cash('#telephone').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#telephone').removeClass('border-theme-24');
            cash('#telephone').parent('div.form-control').find('label').removeClass('text-theme-24');
        }

        if (!priority.length){
            cash('#priority').addClass('border-theme-24');
            cash('#priority').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#priority').removeClass('border-theme-24');
            cash('#priority').parent('div.form-control').find('label').removeClass('text-theme-24');
        }

        if (!completion_date.length){
            cash('#completion_date').addClass('border-theme-24');
            cash('#completion_date').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#completion_date').removeClass('border-theme-24');
            cash('#completion_date').parent('div.form-control').find('label').removeClass('text-theme-24');
        }
        if (!request_tp.length){
            cash('#request_tp').addClass('border-theme-24');
            cash('#request_tp').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#request_tp').removeClass('border-theme-24');
            cash('#request_tp').parent('div.form-control').find('label').removeClass('text-theme-24');
        }
        if (!resource.length || resource == ' '){
            cash('#resource').addClass('border-theme-24');
            cash('#resource').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#resource').removeClass('border-theme-24');
            cash('#resource').parent('div.form-control').find('label').removeClass('text-theme-24');
        }
        if (cost == NaN){
            cash('#cost').addClass('border-theme-24');
            cash('#cost').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#cost').removeClass('border-theme-24');
            cash('#cost').parent('div.form-control').find('label').removeClass('text-theme-24');
        }
        if ( req_cc == '1' ){
            if (!cc_email.length){
                cash('#cc_email').addClass('border-theme-24');
                cash('#cc_email').parent('div.form-control').find('label').addClass('text-theme-24');
                sReturn = false;
            } else {
                cash('#cc_email').removeClass('border-theme-24');
                cash('#cc_email').parent('div.form-control').find('label').removeClass('text-theme-24');
            }
        }
        if (!subject.length){
            cash('#subject').addClass('border-theme-24');
            cash('#subject').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#subject').removeClass('border-theme-24');
            cash('#subject').parent('div.form-control').find('label').removeClass('text-theme-24');
        }
        if (!content.length){
            cash('#content').addClass('border-theme-24');
            cash('#content').parent('div.form-control').find('label').addClass('text-theme-24');
            sReturn = false;
        } else {
            cash('#content').removeClass('border-theme-24');
            cash('#content').parent('div.form-control').find('label').removeClass('text-theme-24');
        }
        return sReturn;
    }


    function fn_submitRequest() {
        var requester = cash('#requester option:checked').val();
        var email = cash('#email').val();
        var department = cash('#department option:checked').val();
        var telephone = cash('#telephone').val();
        var priority = cash('#priority option:checked').val();
        var completeDate = cash('#completion_date').val();
        var requestTp = cash('#request_tp option:checked').val();
        var cost = parseInt(cash('#cost').val().replace(/[^0-9]/gi, ''), 10);
        var resource = cash('#resource option:checked').val();
        var ccEmail = cash('#cc_email').val();
        var subject = cash('#subject').val();
        var content = ckRequestContent.getData();
        var attachment = window[cash('#attachment').data('id')].files;

        var data = new FormData();
        data.append('_token', cash('meta[name="csrf-token"]').attr('content'));
        data.append('header', cash('meta[name="csrf-token"]').attr('content'));
        data.append('requester', requester);
        data.append('email', email);
        data.append('department', department);
        data.append('telephone', telephone);
        data.append('priority', priority);
        data.append('completeDate', completeDate);
        data.append('requestTp', requestTp);
        data.append('cost', cost);
        data.append('resource', resource);
        data.append('ccEmail', ccEmail);
        data.append('subject', subject);
        data.append('content', content);
        cash(attachment).each(function(id, file){
            data.append('attachment[]', file);
        });

        console.log(data);


        var xhr = new XMLHttpRequest();
        
        xhr.open('POST', 'add-request', true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onreadystatechange = function() {
            if ( xhr.readyState == 4 && xhr.status == 200 ) {
                if ( cash('.request-success').hasClass('hidden') ) {
                    cash('.request-success').removeClass('hidden');
                }
                if ( !cash('.request-failure').hasClass('hidden') ) {
                    cash('.request-failure').addClass('hidden');
                }
                
                var itv = 5;
                var itvId = setInterval(function(){
                    if (itv > 0){
                        cash('#redirectInterval').text(itv);
                        itv --;
                    } else {
                        clearInterval(itvId);
                        window.location.reload();
                    }
                }, 1000);
            } else {
                // show request result
                if ( !cash('.request-success').hasClass('hidden') ) {
                    cash('.request-success').addClass('hidden');
                }
                if ( cash('.request-failure').hasClass('hidden') ) {
                    cash('.request-failure').removeClass('hidden');
                }
            }

            slider.goTo('next');
        }

        xhr.send(data);
    }
})(cash);
