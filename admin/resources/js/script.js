var submit_available = true;
var ajax_paging = false;
var paging_callback;
var globalParams = {
    moduleID : 0,
    frameSourceRequest : false
};
if (!Date.now) {
    Date.now = function() { return new Date().getTime(); }
}

function check_edit(i){
    document.getElementById(i).checked = true;
}
function addRow(item){
    var parent = $(item).closest('.row_insert').find('>.col-sm-4');
    var $item = parent.find('.item_template').clone().removeClass('hidden item_template');
    parent.append($item);
}
function getValue(name, type, method) {
    var value_return = false;
    switch(method) {
        case 'GET':
        default :
            var array_get_params = {};
            document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
                function decode(s) {
                    return decodeURIComponent(s.split("+").join(" "));
                }

                array_get_params[decode(arguments[1])] = decode(arguments[2]);
            });
            if(isset(array_get_params[name])) {
                value_return = array_get_params[name];
            }
            break;
    }
    switch (type) {
        case 'bool':
            value_return = Boolean(value_return);
            break;
        case 'str':
            value_return = String(value_return);
            break;
        case 'int':
            value_return = parseInt(value_return);
            if(isNaN(value_return)){
                value_return = 0;
            }
            break;
        case 'flo':
            value_return = parseFloat(value_return);
            if(isNaN(value_return)) {
                value_return = 0;
            }
            break;
    }
    return value_return;
}
function isset() {
    //  discuss at: http://phpjs.org/functions/isset/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: FremyCompany
    // improved by: Onno Marsman
    // improved by: Rafał Kukawski
    //   example 1: isset( undefined, true);
    //   returns 1: false
    //   example 2: isset( 'Kevin van Zonneveld' );
    //   returns 2: true

    var a = arguments,
        l = a.length,
        i = 0,
        undef;

    if (l === 0) {
        throw new Error('Empty isset');
    }

    while (i !== l) {
        if (a[i] === undef || a[i] === null) {
            return false;
        }
        i++;
    }
    return true;
}
function removeRow(item){
    var parent = $(item).closest('.row_insert').find('.col-sm-4');
    if(parent.find('.row_insert_item').length > 1){
        $(item).closest('.row_insert_item').remove();
    }
}
function trim(sString){
    if(isNaN(sString)){
        return sString;
    }
	while(sString.substring(0,1) == ' '){
		sString = sString.substring(1, sString.length);
	}
	while(sString.substring(sString.length-1, sString.length) == ' '){
		sString = sString.substring(0,sString.length-1);
	}
	return sString;
}
function isBlank(str){
    return trim(str) == '';
}
function validForm(obj){
    obj = $.parseJSON(obj);
    for(i in obj.elements){
        var ele = obj.elements[i];
        //console.log(ele);
//        console.log($('#'+ele.id).val());
        var $ele = $('#'+ele.id);
        if(isBlank($ele.val())){
            if(ele.msg) alert(ele.msg);
            $ele.css({border:'1px solid #FF0000'}).focus();
            submit_available = false;
            return false;
        }else{
            $ele.css('border','1px solid #CCC');
            submit_available = true;
        }  
    }
    //submit
    form_name = obj.form_name;
    //gán ô auto numeric vào number
    $('[data-role="auto-numeric"]').each(function(){
        var target = $('#'+$(this).data('target-value'));
        if(target.length){
            target.val($(this).autoNumeric('get'));
        }
    });
    if(submit_available == true){
        document[form_name].submit();
    }
}
function Grid(){
    this.tr = '#tr_';
    this.input_active = '#active_field_';
}
function showPopup(e) {
    var t = 632;
    var n = 400;
    var r = (screen.width - t) / 2;
    var i = (screen.height - n) / 2;
    var s = "width=" + t + ", height=" + n;
    s += ", top=" + i + ", left=" + r;
    s += ", location=1";
    s += ", menubar=no";
    s += ", resizable=no";
    s += ", scrollbars=no";
    s += ", status=no";
    s += ", toolbar=no";
    var o = window.open(e, "windowname5", s);
    if (o)o.focus();
}
function ajaxPaging(selector, container, event) {
    if (ajax_paging !== true) {
        return true;
    } else {
        event.preventDefault();
        var url = $(selector).attr('href');
        var action = 'pagingAjax';
        $.ajax({
            type : 'post',
            url : url,
            data : {action : action, container : container},
            success : function (html) {
                loadingProgress('hide');
                $(container).html(html);
                //gọi đến callback
                if(typeof paging_callback == 'function') {
                    window[paging_callback()];
                }else{
                    if(typeof paging_callback == 'string' && paging_callback != '') {
                        window[paging_callback]();
                    }
                }
            },
            beforeSend : function () {
                loadingProgress('show');
            }
        })
    }
}
Grid.prototype.checkall = function(){
    var input = $('#table-listing input.check');
    if($('#check_all').attr('checked') == 'checked'){
        input.attr('checked','checked');
    }else{
        input.removeAttr('checked');
    }
};
Grid.prototype.delete_one = function(id){
    if(confirm('Bạn muốn xóa bản ghi này ?')){
        var tpl = this.tr + id;
        $(tpl).remove();    
        $.ajax({
            type:'post',
            url:'delete.php',
            data:{'record_id':id},
            success:function(html){
                alert(html);
            }
        })
    }
    return false;
};
Grid.prototype.delete_all = function(total){
    console.log(total);
    if(confirm('Bạn muốn xóa các bản ghi đã chọn ?')){
        var listid = '0';
        var selected = false;
        for(i=1;i<=total;i++){
            if(document.getElementById("record_"+i).checked == true){
                id = document.getElementById("record_"+i).value;
                listid += ','+id;
                var tpl = this.tr + id;
                $(tpl).remove();
                selected = true;
            }
        }
        if(selected===true){
            $.ajax({
                type: "POST",
                url: "delete.php",
                data: "record_id="+listid,
                success: function(msg){
                    if(msg!=''){
                        alert( msg );
                    }
                }
            });
        }else{
            alert('Vui lòng chọn ít nhất 1 bản ghi!');
        }
    }
    return false;
}
Grid.prototype.update_active = function(field,id){
    var tpl = '#'+field+'_'+id;
    var td = $(tpl).closest('td');
    $.ajax({
        type:'post',
        data:{id:id,field:field},
        url:'active.php',
        success:function(html){
            td.html(html);
        },
        beforeSend:function(){
            td.html('<img src="../img/loading.gif"/>');
        }
    });
    return false;
}
//JS cấu hình hàm uploader chung cho toàn bộ cms
var UploaderScript = UploaderScript || {};
UploaderScript.config = {
    url : '../../resources/php/upload.php',
    file_ext : 'jpg,gif,png,jpeg,bmp',
    browse_button : '',
    image_wrapper : '',
    loading : '',
    error_wrapper : '',
    max_file_size : '10mb',
    file_name : ''
}
UploaderScript.init = function(option, callback){
    $.extend(UploaderScript.config, option);
    if(!UploaderScript.config.browse_button || !$('#'+UploaderScript.config.browse_button).length){
        console.log('Error : Khong tim thay browse_button');
        return false;
    }

    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,html4',
        browse_button : UploaderScript.config.browse_button,
        max_file_size : UploaderScript.config.max_file_size,
        url : UploaderScript.config.url,
        flash_swf_url : 'plupload/js/plupload.flash.swf',
        silverlight_xap_url : 'plupload/js/plupload.silverlight.xap',
        filters : [
            {title : "Image files", extensions : UploaderScript.config.file_ext}
        ]
    });
    uploader.init();
    uploader.bind('FilesAdded',function(up,file){
        uploader.start();
    });
    uploader.bind('UploadProgress',function(up,file){
        if(UploaderScript.config.loading){
            $('#'+UploaderScript.config.loading).html(file.percent + '%');
        }
    });
    uploader.bind('FileUploaded',function(up,file,resp){
        if(resp.status == 200){
            var fileInfo = resp.response;
            fileInfo = JSON.parse(fileInfo);
            filename = fileInfo.result.filename;
            filepath = fileInfo.result.filepath;
            $('#'+UploaderScript.config.image_wrapper).attr('src',filepath);
            UploaderScript.config.file_name = filename;
            callback();
        }
    });
}

var NewsJS = NewsJS || {};
NewsJS.search_relate = function(){
    data = $('#search_relate').val();
    $.ajax({
        type:'post',
        url:'../news/ajax.php',
        data:{keyword:data,action:'search_relate'},
        success:function(html){
            $('#relate_result').html(html);
        }
    })
}
NewsJS.add_relate = function (){
    var array_relate_list = [];
    $('.new_relate_result').each(function(){
        if($(this).attr('checked') == 'checked'){
            var dataRN = {
                value:$(this).val(),
                title:$(this).attr('title')
            }
            array_relate_list.push(dataRN);    
        }
    });
    if(array_relate_list.length){
        var listBuild = '<div class="relate_element">';
        var checkEmpty = true;
        for(var i in array_relate_list){
            if(!$('#new_relate_list_'+array_relate_list[i].value).length){
                var buildEle = '';
                buildEle += '<label class="checkbox"><input type="checkbox" class="checkbox" checked="checked" name="new_relate_list[]" value="'+array_relate_list[i].value+'" id="new_relate_list_'+array_relate_list[i].value+'"/>';
                buildEle += array_relate_list[i].title;
                buildEle += '<span style="margin-left:5px;padding:5px;color:red;font-weight:bold;font-size:14px;cursor:pointer;" onclick="NewsJS.del_relate(this);return false;">&times;</span></label>';
                listBuild += buildEle;
                checkEmpty = false;    
            }
        }
        listBuild += '</div>';
        if(!checkEmpty){
            $('#news_relate_after_search').append(listBuild);    
        }
    }
}
NewsJS.del_relate = function (a){
    relateDiv = $(a).closest('.relate_element');
    $(a).closest('label').remove();
    if(!relateDiv.find('label').length) relateDiv.remove();
}

var ProductJS = ProductJS || {};
ProductJS.uploadSlide = function(){
    var addFileBtn = $('#add-item'), slides = $('.slides');
    var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,html4',
        browse_button : 'browser-file',
        max_file_size : '10mb',
        url : '../../resources/php/upload.php',
        flash_swf_url : 'plupload/js/plupload.flash.swf',
        silverlight_xap_url : 'plupload/js/plupload.silverlight.xap',
        filters : [
            {title : "Image files", extensions : "jpg,gif,png,jpeg,bmp"}
        ]
    });
    uploader.init();
    addFileBtn.click(function(){
        $('#browser-file').trigger('click');
    });
    uploader.bind('FilesAdded',function(up,file){
        uploader.start();
    });
    uploader.bind('UploadProgress',function(up,file){
        addFileBtn.find('span').hide();
        addFileBtn.find('.uploading-msg').html('Uploading... '+file.percent + '%');
        console.log(file.percent);
    });
    uploader.bind('FileUploaded',function(up,file,resp){
        addFileBtn.find('span').show();
        addFileBtn.find('.uploading-msg').html('');
        console.log(resp);
        if(resp.status == 200){
            var fileInfo = resp.response;
            fileInfo = JSON.parse(fileInfo);
            filename = fileInfo.result.filename;
            var btnXoa = '<div class="thumb-del"><span class="label label-warning" onclick="ProductJS.slideDeleteItem(this)">Xóa</span></div>';
            slides.prepend('<div class="thumb"><img src="../../../temp/'+filename+'"><input type="hidden" name="pro_slide[]" value="'+filename+'">'+btnXoa+'</div>');
        }
    });
}
ProductJS.slideDeleteItem = function(a){
    $(a).closest('.thumb').remove();
}

ProductJS.search_relate = function(){
    data = $('#search_pro_relate').val();
    $.ajax({
        type:'post',
        url:'../products/ajax.php',
        data:{keyword:data,action:'search_relate'},
        success:function(html){
            $('#relate_pro_result').html(html);
        }
    })
}

ProductJS.add_relate = function (){
    var array_relate_list = [];
    $('.pro_relate_result').each(function(){
        if($(this).attr('checked') == 'checked'){
            dataRN = {
                value:$(this).val(),
                title:$(this).attr('title')
            }
            array_relate_list.push(dataRN);
        }
    });
    if(array_relate_list.length){
        var listBuild = '<div class="relate_element">';
        var checkEmpty = true;
        for(i in array_relate_list){
            if(!$('#pro_relate_list_'+array_relate_list[i].value).length){
                var buildEle = '';
                buildEle += '<label class="checkbox"><input type="checkbox" class="checkbox" checked="checked" name="pro_relate_list[]" value="'+array_relate_list[i].value+'" id="pro_relate_list_'+array_relate_list[i].value+'"/>';
                buildEle += array_relate_list[i].title;
                buildEle += '<span style="margin-left:5px;padding:5px;color:red;font-weight:bold;font-size:14px;cursor:pointer;" onclick="ProductJS.del_relate(this);return false;">&times;</span></label>';
                listBuild += buildEle;
                checkEmpty = false;
            }
        }
        listBuild += '</div>';
        if(!checkEmpty){
            $('#pro_relate_after_search').append(listBuild);
        }
    }
}
ProductJS.del_relate = function (a){
    relateDiv = $(a).closest('.relate_element');
    $(a).closest('label').remove();
    if(!relateDiv.find('label').length) relateDiv.remove();
}

var TemplateJS = TemplateJS || {};
TemplateJS.disableTemplateListFile = function(){
    $('#tpl_css_file, #tpl_js_file').attr('disabled','disabled');
}

//đánh dấu các ô input đang được nhập
var trigger_oninput = false;
$(document).on('change','input,textarea,select',function(){
    trigger_oninput = true;
});
$(window).on('load',function(){
    if(trigger_oninput){
        alert(1);
    }
});

//bắt đầu xử lý khi trang được tải xong
$(document).ready(function(){
    //active tab menu trên cùng
    globalParams.frameSourceRequest = getValue('frame_source_request','str','GET');
    //console.log(globalParams.frameSourceRequest);
    if(globalParams.moduleID && globalParams.frameSourceRequest != 'mindow' && window.parent.hasOwnProperty('communicateParentWindow')) {
        window.parent.communicateParentWindow('activeTab',globalParams.moduleID);
    }
    //auto complete
    if($('input[js-autocomplete="1"]').length){
        $('input[js-autocomplete="1"]').autocomplete({
            serviceUrl : '../../resources/php/autocomplete.php'
        });
    }

    //select cat multi
    $('.select-cat-multi').find('[data-target]').change(function(){
        var target = $(this).data('target');
        var value = $(this).val();
        var table = $(this).data('table');
        var id_field = $(this).data('id-field');
        var name_field = $(this).data('name-field');
        var parent_field = $(this).data('parent-field');
        if(!value) return false;
        $.ajax({
            type : 'post',
            url : '/ajax/getDropdownCatChild',
            data : {record_id : value, table : table, id_field : id_field, name_field : name_field, parent_field : parent_field},
            success : function(html){
                if(html)
                    $('#'+target).html(html).removeAttr('disabled');
            }
        })
    });
    //select multi ajax
    $(document)
        .off('change','[data-auto-form-name="select-multi-relate"] [data-target]')
        .on('change','[data-auto-form-name="select-multi-relate"] [data-target]',function () {
            var target = $(this).data('target');
            var value = $(this).val();
            if(!value)  return false;
            $.ajax({
                type : 'post',
                url : 'ajax.php',
                data : {action : $(this).data('action'), data : value},
                success : function(html){
                    if(html){
                        $('#'+target).html(html).removeAttr('disabled');
                    }
                }
            })
        });
    //auto numeric
    if($('[data-role="auto-numeric"]').length){
        $('[data-role="auto-numeric"]').autoNumeric('init',{lZero:'allow'});
    }

    //date picker
    if($('input[datepick-element="1"]').length){
        $('input[datepick-element="1"]').datepicker({
            format:'dd/mm/yyyy'
        });
    }

    //unique field
    if($('input[data-unique="1"]').length){
        $('input[data-unique="1"]').blur(function(){
            var $this = $(this);
            if($this.val()){
                $.ajax({
                    type : 'post',
                    url : '/ajax/checkUnique',
                    data : {
                        table : $this.data('unique-table'),
                        field : $this.data('unique-field'),
                        value : $this.val()
                    },
                    dataType : 'json',
                    success : function(resp){
                        if(!resp)   alert(1);
                        if(resp.unique == 1){
                            submit_available = false;
                            $this.closest('.form-group').find('.alert-unique-input').show();
                        }else{
                            submit_available = true;
                            $this.closest('.form-group').find('.alert-unique-input').hide();
                        }
                    }
                });
            }
        });

    }

    ProductJS.uploadSlide();
    //List combo
    $('#add-combo').click(function(){
        var select = $('#combo_id');
        var option = select.find('option[value="'+select.val()+'"]');
        var node = '<label class="checkbox">' +
            '<input type="checkbox" value="'+select.val()+'" name="comboDevide[]" id="comboDevide_'+select.val()+'">' + option.html() +
            '<span style="margin-left:5px;padding:5px;color:red;font-weight:bold;font-size:14px;cursor:pointer;" class="removeRow">×</span></label>';
        //nếu có rồi thì thôi
        if(!$('#comboDevide_'+select.val()).length){
            $('#list-combo').append(node);
        }
    });
    $(document).on('click','.removeRow',function(){
        $(this).parent().remove();
    });
    //Grid header submit
    $(document).on('submit','.grid_header >form',function(e) {
        var form = $(this);
        var wrapper = form.parent().parent();
        var action = form.attr('action');
        if(action.search('ajax.php') > -1) {
            //là ajax table
            //alert(1);
            $.ajax({
                type : 'post',
                url : action + '?' + form.serialize(),
                data : {action : 'searchRecord'},
                dataType : 'html',
                success : function (html) {
                    wrapper.html(html);
                }
            })

        }else{
            return;
        }
        e.preventDefault();
    });
    //Grid Table sorting
    $(document).on('click','.table-sorting', function(e){
        var href = $(this).attr('href');
        var wrapper = $(this).closest('.column-wrapper');
        if(href.search('ajax.php') > -1) {
            //là ajax table
            $.ajax({
                type : 'post',
                url : href,
                data : {action : 'searchRecord'},
                dataType : 'html',
                success : function (html) {
                    wrapper.html(html);
                }
            })
        }else {
            return ;
        }
        e.preventDefault();
    })
});

function number_format (number, decimals, dec_point, thousands_sep) {
    // http://kevin.vanzonneveld.net
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     bugfix by: Michael White (http://getsprink.com)
    // +     bugfix by: Benjamin Lupton
    // +     bugfix by: Allan Jensen (http://www.winternet.no)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +     bugfix by: Howard Yeend
    // +    revised by: Luke Smith (http://lucassmith.name)
    // +     bugfix by: Diogo Resende
    // +     bugfix by: Rival
    // +      input by: Kheang Hok Chin (http://www.distantia.ca/)
    // +   improved by: davook
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Jay Klehr
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +      input by: Amir Habibi (http://www.residence-mixte.com/)
    // +     bugfix by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Theriault
    // +      input by: Amirouche
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: number_format(1234.56);
    // *     returns 1: '1,235'
    // *     example 2: number_format(1234.56, 2, ',', ' ');
    // *     returns 2: '1 234,56'
    // *     example 3: number_format(1234.5678, 2, '.', '');
    // *     returns 3: '1234.57'
    // *     example 4: number_format(67, 2, ',', '.');
    // *     returns 4: '67,00'
    // *     example 5: number_format(1000);
    // *     returns 5: '1,000'
    // *     example 6: number_format(67.311, 2);
    // *     returns 6: '67.31'
    // *     example 7: number_format(1000.55, 1);
    // *     returns 7: '1,000.6'
    // *     example 8: number_format(67000, 5, ',', '.');
    // *     returns 8: '67.000,00000'
    // *     example 9: number_format(0.9, 0);
    // *     returns 9: '1'
    // *    example 10: number_format('1.20', 2);
    // *    returns 10: '1.20'
    // *    example 11: number_format('1.20', 4);
    // *    returns 11: '1.2000'
    // *    example 12: number_format('1.2000', 3);
    // *    returns 12: '1.200'
    // *    example 13: number_format('1 000,50', 2, '.', ' ');
    // *    returns 13: '100 050.00'
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
function error_show(error,elementShow){
    elementShow = $(elementShow);
    if(!elementShow.html()){
        elementShow = elementShow.closest('div');
    }
    if(!$.isArray(error)){
        error = [error];
    }
    number_error = error.length;
    for(var i = 0; i < number_error; i++){
        elementShow.append('<div class="alert alert-danger alert-dismissable"><button class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+error[i]+'</div>');
    }
    return elementShow;
}
function check_url(str){
    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    return regexp.test(str);
}
//jquery.cookie.js
/*!
 * jQuery Cookie Plugin v1.4.0
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // CommonJS
        factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {

    var pluses = /\+/g;

    function encode(s) {
        return config.raw ? s : encodeURIComponent(s);
    }

    function decode(s) {
        return config.raw ? s : decodeURIComponent(s);
    }

    function stringifyCookieValue(value) {
        return encode(config.json ? JSON.stringify(value) : String(value));
    }

    function parseCookieValue(s) {
        if (s.indexOf('"') === 0) {
            // This is a quoted cookie as according to RFC2068, unescape...
            s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
        }

        try {
            // Replace server-side written pluses with spaces.
            // If we can't decode the cookie, ignore it, it's unusable.
            // If we can't parse the cookie, ignore it, it's unusable.
            s = decodeURIComponent(s.replace(pluses, ' '));
            return config.json ? JSON.parse(s) : s;
        } catch(e) {}
    }

    function read(s, converter) {
        var value = config.raw ? s : parseCookieValue(s);
        return $.isFunction(converter) ? converter(value) : value;
    }

    var config = $.cookie = function (key, value, options) {

        // Write

        if (value !== undefined && !$.isFunction(value)) {
            options = $.extend({}, config.defaults, options);

            if (typeof options.expires === 'number') {
                var days = options.expires, t = options.expires = new Date();
                t.setTime(+t + days * 864e+5);
            }

            return (document.cookie = [
                encode(key), '=', stringifyCookieValue(value),
                options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
                options.path    ? '; path=' + options.path : '',
                options.domain  ? '; domain=' + options.domain : '',
                options.secure  ? '; secure' : ''
            ].join(''));
        }

        // Read

        var result = key ? undefined : {};

        // To prevent the for loop in the first place assign an empty array
        // in case there are no cookies at all. Also prevents odd result when
        // calling $.cookie().
        var cookies = document.cookie ? document.cookie.split('; ') : [];

        for (var i = 0, l = cookies.length; i < l; i++) {
            var parts = cookies[i].split('=');
            var name = decode(parts.shift());
            var cookie = parts.join('=');

            if (key && key === name) {
                // If second argument (value) is a function it's a converter...
                result = read(cookie, value);
                break;
            }

            // Prevent storing a cookie that we couldn't decode.
            if (!key && (cookie = read(cookie)) !== undefined) {
                result[name] = cookie;
            }
        }

        return result;
    };

    config.defaults = {};

    $.removeCookie = function (key, options) {
        if ($.cookie(key) === undefined) {
            return false;
        }

        // Must not alter options, thus extending a fresh object...
        $.cookie(key, '', $.extend({}, options, { expires: -1 }));
        return !$.cookie(key);
    };

}));
$.fn.removeAttributes = function(only, except) {
    if (only) {
        only = $.map(only, function(item) {
            return item.toString().toLowerCase();
        });
    }
    if (except) {
        except = $.map(except, function(item) {
            return item.toString().toLowerCase();
        });
        if (only) {
            only = $.grep(only, function(item, index) {
                return $.inArray(item, except) == -1;
            });
        }
    }
    return this.each(function() {
        var attributes;
        if(!only){
            attributes = $.map(this.attributes, function(item) {
                return item.name.toString().toLowerCase();
            });
            if (except) {
                attributes = $.grep(attributes, function(item, index) {
                    return $.inArray(item, except) == -1;
                });
            }
        } else {
            attributes = only;
        }
        var handle = $(this);
        $.each(attributes, function(index, item) {
            handle.removeAttr(item);
        });
    });
};
