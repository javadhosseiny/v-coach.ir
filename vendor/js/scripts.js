(function(window, undefined) {
    'use strict';
    if ( $.isFunction($.fn.DataTable) ) {
        $('.table-list-data').DataTable({
            dom: 'Bfrtip',
            "ordering": true,
            "order": [],
            "aaSorting": [],
            buttons: [
                // 'copy', 'csv', 'excel', 'pdf', 'print'
                'excel',
                // {extend: 'excel', className: 'btn btn-primary waves-effect waves-light'}
            ],
            "bPaginate": true,
            bFilter: true,
            bInfo: false,
            "language": {
                "sProcessing": "درحال پردازش...",
                "sLengthMenu": "نمایش محتویات _MENU_",
                "sZeroRecords": "موردی یافت نشد",
                "sInfo": "نمایش _START_ تا _END_ از مجموع _TOTAL_ مورد",
                "sInfoEmpty": "تهی",
                "sInfoFiltered": "(فیلتر شده از مجموع _MAX_ مورد)",
                "sInfoPostFix": "",
                "sSearch": "جستجو:",
                "search": "جستجو:",
                "previous": "قبلی",
                "sUrl": "",
                "oPaginate": {
                    "sFirst": "ابتدا",
                    "sPrevious": "قبلی",
                    "sNext": "بعدی",
                    "sLast": "انتها"
                },
                "paginate": {
                    "first": "ابتدا",
                    "previous": "قبلی",
                    "next": "بعدی",
                    "last": "انتها"
                }
            }
        });
    }

/*    
    $(document).ready(function (){
        $('#loader').hide();
        $('.date-time-picker').persianDatepicker({
            initialValue: false,
            calendar:{
                persian:{
                    locale:'fa'
                }
            },
            persianDigit:false,
            format: 'YYYY/MM/DD HH:m',
            timePicker: {
                enabled: true,
                meridiem: {
                    enabled: true
                },
                second: {
                    enabled: false,
                },
                minute: {
                    enabled: true,
                    step: true,
                }
            }        ,
            onSelect:function (e){
                toEn()
            },
            onSet:function (e){
                toEn()
            },
            onHide:function (e){
                toEn()
            }
        });
        $('.date-picker').persianDatepicker({
            initialValue: false,
            calendar:{
                persian:{
                    locale:'fa'
                }
            },
            persianDigit:false,
            format: 'YYYY/MM/DD',
            timePicker: {
                enabled: false,
            }        ,
            onSelect:function (e){
                toEn()
            },
            onSet:function (e){
                toEn()
            },
            onHide:function (e){
                toEn()
            }
        });
        $('.time-picker').persianDatepicker({
            initialValue: false,
            onlyTimePicker: true,
            calendar:{
                persian:{
                    locale:'en'
                }
            },
            format: 'HH:m',
            timePicker: {
                enabled: true,
                meridiem: {
                    enabled: true
                },
                second: {
                    enabled: false,
                },
                persianDigit:false
                // minute: {
                //   enabled: true,
                //   step: true,
                // }
                ,
                onSelect:function (e){
                    toEn()
                },
                onSet:function (e){
                    toEn()
                },
                onHide:function (e){
                    toEn()
                }
            }
        });
        setTimeout(()=>{
            toEn()
        },500)
        setTimeout(()=>{
            $('#table-list2_filter input').removeClass('form-control-sm');
        },500)
    })
*/    
    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */

})(window);
function toEn(){
    $('.date-time-picker').each(function (){
        $(this).val(convertNumber($(this).val()))
    })
    $('.time-picker').each(function (){
        $(this).val(convertNumber($(this).val()))
    })
    $('.date-picker').each(function (){
        $(this).val(convertNumber($(this).val()))
    })
}
var convertNumber= function (str)
{
    var
        persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g],
        arabicNumbers  = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g];
    if(typeof str === 'string')
    {
        for(var i=0; i<10; i++)
        {
            str = str.replace(persianNumbers[i], i).replace(arabicNumbers[i], i);
        }
    }
    return str;
};
function request(sorceUrl, preload, formData, method, dataType, callback) {
    if (!dataType) {
        dataType = 'json';
    }
    preLoad(preload);
    var data = new FormData();
    if (formData.length > 0) {
        if ($('#image') != undefined && $('#image') != null && $('#image').length > 0) {
            $.each($('#image')[0].files, function (i, file) {
                data.append("image", file);
                $("#image-error").text('');
            });
        }
        // if ($('#file') != undefined && $('#file') != null && $('#file').length > 0) {
        //   $.each($('#file')[0].files, function (i, file) {
        //     data.append("file", file);
        //     $("#file-error").text('');
        //   });
        // }
        $.each($("input[type=file]"), function (i, obj) {
            $.each(obj.files, function (j, file) {
                data.append('attachment_file[' + j + ']', file);
            })
        });
        for (j = 0; j < formData.length; j++) {
            data.append(formData[j].name, formData[j].value);
        }
    }
    $.ajax(
        {
            method: method,
            type: method,
            dataType: dataType,
            url: sorceUrl,
            data: data,
            contentType: false,
            processData: false,
            crossDomain: true,
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('[name="_token"]').val()
            },
            mimeType: "multipart/form-data",
            success: function (data) {
                if (callback != null && callback !== undefined) {
                    callback(data, formData);
                }
                // message('عملیات با موفقیت انجام شد.', 'success');
                preLoad(0);
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(xhr);
                if (xhr.responseJSON.errors !== undefined) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        message(value[0], 'error', null, 5000);
                    });
                } else if (xhr.responseJSON.message !== undefined) {
                    message(xhr.responseJSON.message, 'error');
                } else {
                    message('عملیات ناموفق بود،ورودی های خود را بررسی کرده و سپس دوباره تلاش کنید.', 'error');
                }
                preLoad(0);
            }
        });
    // } else {
    //   message('لطفا فرم را با دفت تکمیل نمائید.', 'error');
    // }
}


function message(message, type, time,  id) {
    message = '<strong>' + message + '</strong>';
    time = time != null && time != '' ? time : 10000;
    var progressBar = true;
    if (time>10000) progressBar = false;
    var element_txt;
    var heading = '';
    var icon = '';
    var loaderBg = '';
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": progressBar,
        "rtl": true,
        "positionClass": "toast-top-left",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "50",
        "hideDuration": "1000",
        "timeOut": time,
        "extendedTimeOut": time,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    if (type == 'error') {
        element_txt = 'alert_danger';
        heading = 'خطا';
        icon = 'error';
        loaderBg = '';
        toastr.error(message);
    } else if (type == 'success') {
        element_txt = 'alert_success';
        heading = 'پیام';
        icon = 'success';
        toastr.success(message);
    } else if (type == 'info') {
        element_txt = 'alert_info';
        heading = 'پیام';
        icon = 'info';
        toastr.info(message);
    } else if (type == 'warning') {
        element_txt = 'alert_warning';
        heading = 'هشدار';
        icon = 'warning';
        toastr.warning(message);
    }
    if (id != null && id != '' && document.getElementById(id) != null && typeof(document.getElementById(id)) != undefined) {
        document.getElementById(id).focus();
    }
}


function preLoad(flag) {
    if (flag==1)
        $('#loader').show();
    else
        $('#loader').hide();
}
function removeElement(id, callback) {
    $(id).remove();
    if (callback!==undefined && callback!=null)
        callback();
}
var set1 = null;
function addSliderRow(id, i,url,id2, callback) {
    if (set1 != null)
        clearTimeout(set1);
    set1 = setTimeout(function () {
        $('#'+id2).val(+i + 1);
        request(url + i, 0, [], 'get', 'html', function (data) {
            $('#' + id).append(data);
            var products = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.title);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    wildcard: '%QUERY',
                    url: '/products/search/?search=title:%QUERY',

                    filter: function (products) {
                        products = products.data;
                        return $.map(products, function (item) {
                            $('#row_product_'+i).val(item.id);
                            return item.id + '_' + item.title;
                        });
                    }
                }
            });
            products.initialize();
            var elt = $(document).find('#row_product_'+i);
            elt.tagsinput({
                maxTags: 1,
                confirmKeys: [9, 13, 44],
                freeInput: false,
                trimValue: false,
                typeaheadjs: {
                    name: 'products',
                    limit: 100,
                    source: products.ttAdapter(),
                }
            });
            if (callback!==undefined && callback!=null) callback();
            setTimeout(function () {
                $('#' + id).find('.select2').select2();
            }, 100);
        });
    }, 100);
}
function addAttachmentRow(id, i) {
    if (set1 != null)
        clearTimeout(set1);
    set1 = setTimeout(function () {
        $('#attachments_count').val(+i + 1);
        request('/attachments/row/' + i, 0, [], 'get', 'html', function (data) {
            $('#' + id).append(data);
            setTimeout(function () {
                $('#' + id).find('.select2').select2();
            }, 100);
        });
    }, 100);
}
function addSectionRow(id, i) {
    if (set1 != null)
        clearTimeout(set1);
    set1 = setTimeout(function () {
        $('#sections_count').val(+i + 1);
        request('/sections/row/' + i, 0, [], 'get', 'html', function (data) {
            $('#' + id).append(data);
            setTimeout(function () {
                // CKEDITOR.replaceClass = 'ckeditor';
                $('#' + id).find('.ckeditor:not([style*="display: none"])').each(function (index,item){
                    CKEDITOR.replace(item);
                });
                $('#' + id).find('.select2').select2();
                // window.scrollTo()
                document.getElementById('row_section_'+i).scrollIntoView();
            }, 100);
        });
    }, 100);
}
function addSectionQuizRow(id, i) {
    if (set1 != null)
        clearTimeout(set1);
    set1 = setTimeout(function () {
        $('#sections_count').val(+i + 1);
        request('/sections/row_quiz/' + i, 0, [], 'get', 'html', function (data) {
            $('#' + id).append(data);
            setTimeout(function () {
                // CKEDITOR.replaceClass = 'ckeditor';
                $('#' + id).find('.ckeditor:not([style*="display: none"])').each(function (index,item){
                    CKEDITOR.replace(item);
                });
                $('#' + id).find('.select2').select2();
                // window.scrollTo()
                document.getElementById('row_section_quiz_'+i).scrollIntoView();
            }, 100);
        });
    }, 100);
}
function addQuizQuestionRow(id, i,i2) {
    if (set1 != null)
        clearTimeout(set1);
    set1 = setTimeout(function () {
        var el=$(document).find('#quiz_questions_count_'+i);
        el.val(parseInt(el.val()) + 1);
        request('/quizzes/row/' + i +'/'+ i2, 0, [], 'get', 'html', function (data) {
            $('#' + id).append(data);
            document.getElementById('row_quiz_question_'+i+'_'+i2).scrollIntoView();
        });
    }, 100);
}
function deleteR(url, msg) {
    if (window.confirm(msg)) {
        window.location.href = url;
    }
}
function search(e, url, param, value) {
    e.preventDefault();
    window.location.href = '/' + url + '?search=' + param + ':' + value+'&title='+value + '&param=' + param;
    return false;
}
function search2(e, url,params, value,fragment) {
    e.preventDefault();
    var s='';
    for (var i in params) {
        if(s!=''){
            s+=';'+params[i]+':'+value
        }else {
            s=params[i]+':'+value
        }
    }
    if (fragment===undefined || fragment==null){
        fragment='';
    }
    window.location.href = '/' + url + '?search=' +s+'&title='+value+fragment;
    return false;
}
function search3(e, url,params, value,search,search2) {
    e.preventDefault();
    var s='';
    if (search===undefined || search==null){
        search='';
    }
    if (value!=''){
        for (var i in params) {
            if(s!=''){
                s+=';'+params[i]+':'+value
            }else {
                s=params[i]+':'+value
            }
        }
    }
    if (s!='' && search!=''){
        s=s+';'+search;
    }else if(search!=''){
        s=search;
    }
    if (search2!==undefined && search2!=''){
        window.location.href = '/' + url + '?search=' +s+'&'+search2+'&title='+value+'&searchJoin=or';
    }else{
        window.location.href = '/' + url + '?search=' +s+'&title='+value+'&searchJoin=or';
    }
    return false;
}
function searchWhitParam(e, url, params, value, value2 = [], limit,custom='',order='',sort='desc',append='') {
    e.preventDefault();
    var s = '';
    var s2 = '';
    var urll = '';
    limit = limit !== undefined && limit != '' && limit != null ? limit : 50;
    sort = sort !== undefined && sort != '' && sort != null ? sort : 'desc';
    if (value2!='' && typeof value2=="string")
        value2=value2.split(',')
    if (value.trim() != '') {
        var tt=value.trim();
        if (value.trim()=='تائید نشده'){
            value='0';
        }else if (value.trim()=='تائید شده'){
            value='1';
        }
        if (value2 === undefined || value2 == '' || value2.length == 0 || value2[0] == 0) {
            // for (var i in params) {
            //   if (params[i] != 'd_vch_digitalprice') {
            //     if (s != '') {
            //       s += ';' + params[i] + ':' + value;
            //     } else {
            //       s = params[i] + ':' + value;
            //     }
            //     s2 += '&' + params[i] + '=' + value;
            //   }
            // }
            if (s != '') {
                s += ';' + params[0] + ':' + value;
            } else {
                s = params[0] + ':' + value;
            }
            s2 += '&' + params[0] + '=' + value;
        } else {
            for (var i in value2) {
                if (s != '') {
                    s += ';' + value2[i] + ':' + value;
                } else {
                    s = value2[i] + ':' + value;
                }
                s2 += '&' + value2[i] + '=' + value;
            }
        }
        if (custom!==undefined && custom!=''){
            s+=';'+custom;
        }
        s2 += '&title=' + tt;
        urll = '/' + url + '?search=' + s + s2 + '&limit=' + limit;
    } else if(custom!==undefined && custom!='') {
        urll = '/' + url + '?search=' + custom  + '&limit=' + limit;
    } else {
        urll = '/' + url + '?limit=' + limit;
    }
    if (urll!=''){
        if (order!=''){
            urll+='&orderBy='+order+'&sortedBy='+sort;
        }
        urll=urll+append;
        // var sj='or';
        // if (urll.indexOf('status_id')!==false){
        //   sj='and';
        // }
        window.location.href = urll;
    }
    return false;
}
function addAv(el,id,callback) {
    var html=$(el).parents().eq(1).clone();
    $(html).find('input').val('');
    $('#'+id).append(html);
    if (callback!==undefined){
        callback()
    }
}
function removeAv(el,id,callback) {
    if($('.'+id).length>1){
        var html=$(el).parents().eq(1).remove();
        if (callback!==undefined){
            callback()
        }
    }
}
function sendForm(formId,callback) {
    // if(window.confirm('آیا از انجام این عملیات مطمئن هستید؟')){
    //   for (var instanceName in CKEDITOR.instances)
    //     CKEDITOR.instances[instanceName].updateElement();
    request($('#'+formId).attr('action'), 1, $('#'+formId).serializeArray(), 'post', 'json', function (data) {
        message(data.message, data.status, null, 5000);
        if (callback!==undefined && callback!=null){callback(data)}else if(data.message!==undefined){message(data.message,'success')}
    })
    // }
}
function getSearchType() {
    var val=$('#search_type').val();
    return '&searchJoin='+val;
}
