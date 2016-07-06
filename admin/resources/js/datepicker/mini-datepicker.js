(function($){
   $.fn.extend({
      miniDatePicker :  function(options){
   
         //get value
         
         var currentDay = (new Date).getDate();
         var currentYear = (new Date).getFullYear();
         var currentMonth = (new Date).getMonth() + 1;
         var dateValue  =  currentDay+'/'+currentMonth+'/'+currentYear;
         
         var defaults   =  {
            dp_position_y :false,
            dp_position_x :false 
         };
         
         
         var options    =  $.extend(defaults, options);
         return this.each(function(){
            //console.log(this);
            var _this = $(this);
            var dp_click   =  false;
            var str = $(this).val();
            var res = str.split("/");
            var userDay =  parseInt(res[0]);
            var userMonth =  parseInt(res[1]);
            var userYear =  parseInt(res[2]);
            
            var constructor   =  {
               dp_position_y    :  defaults.dp_position_y, 
               dp_position_x    :  defaults.dp_position_x,
               prevArrow   :  '<span type="text" class="dbtn miniDatePicker_prev">&lsaquo;</span>',
               nextArrow   :  '<span class="dbtn miniDatePicker_next">&rsaquo;</span>',
               userDay  :  userDay,
               userMonth:  userMonth,
               userYear:   userYear,
               buildDatetime : function() {
                  var str = '<div class="miniDatePicker_show_info">'+constructor.prevArrow + constructor.dayText + constructor.monthText + constructor.yearText + constructor.nextArrow+'</div>';
                  constructor.dp_wrap.html(str);
               },
               calculateDatetime : function() {
                  var str = _this.val();
                  var res = str.split("/");
                  constructor.userDay =  parseInt(res[0]);
                  constructor.userMonth =  parseInt(res[1]);
                  constructor.userYear =  parseInt(res[2]);
                  
                  
                  //tính lại dayText, monthText
                  constructor.dayText  =  constructor.userDay >= 10 ? '<input type="number" min="1" max="31" maxlength="2" class="miniDatePicker_box miniDatePicker_day" data-name="day" value='+constructor.userDay+'>' : '<input type="number" min="1" max="31" maxlength="2" class="miniDatePicker_box miniDatePicker_day" data-name="day" value=0'+constructor.userDay+'>',
                  constructor.monthText = constructor.userMonth >= 10 ? '<input type="number" min="1" max="12" maxlength="2" class="miniDatePicker_box miniDatePicker_month" data-name="month" value='+constructor.userMonth+'>' : '<input type="number" min="1" max="12" maxlength="2" class="miniDatePicker_box miniDatePicker_month" data-name="month" value=0'+constructor.userMonth+'>',
                  constructor.yearText = '<input type="number" min="1970" max="2100" maxlength="4" class="miniDatePicker_box miniDatePicker_year" data-name="year" value='+constructor.userYear+'>'
               },
               
               unique :  function() {
                    return 'miniDatePicker' + Math.random().toString(36).substr(2, 9) + (new Date).getTime();
               },
               show : function() {
                  constructor.calculateDatetime();
                  constructor.buildDatetime();
                  $('#'+constructor.dp_wrap_ID).children('.miniDatePicker_show_info').show();
                  $(document).mousedown(function(e){
                     if($(e.target).closest('.miniDatePicker_wrap').length == 0) {
                        constructor.hide();
                     }
                  })
               },
               hide : function() {
                  $('#'+constructor.dp_wrap_ID).children('.miniDatePicker_show_info').hide();
               },
               dayText  :  userDay >= 10 ? '<input type="number" min="1" max="31" maxlength="2" class="miniDatePicker_box miniDatePicker_day" data-name="day" value='+userDay+'>' : '<input type="number" min="1" max="31" maxlength="2" class="miniDatePicker_box miniDatePicker_day" data-name="day" value=0'+userDay+'>',
               monthText:  userMonth >= 10 ? '<input type="number" min="1" max="12" maxlength="2" class="miniDatePicker_box miniDatePicker_month" data-name="month" value='+userMonth+'>' : '<input type="number" min="1" max="12" maxlength="2" class="miniDatePicker_box miniDatePicker_month" data-name="month" value=0'+userMonth+'>',
               yearText :  '<input type="number" min="1970" max="2100" maxlength="4" class="miniDatePicker_box miniDatePicker_year" data-name="year" value='+userYear+'>'            
            }
            
            constructor.dp_wrap = $('<div class="noselect miniDatePicker_wrap" id="'+constructor.unique()+'"></div>').appendTo($('body'));
            constructor.miniDatePickerID  =  constructor.unique();
            constructor.dp_wrap_ID  = constructor.dp_wrap.attr('id');
            constructor.buildDatetime();
            //Prev
            $('#'+ constructor.dp_wrap_ID).on('click','.miniDatePicker_prev',function(){
               switch(constructor.userMonth){
                  case 1:
                  case 5:
                  case 7:
                  case 8:
                  case 10:
                  case 12: 
                     if(constructor.userDay  == 1){
                        if(constructor.userMonth   == 1){
                           constructor.userMonth   = 12;
                           constructor.userDay    =  31;
                           constructor.userYear    =  constructor.userYear - 1;
                        }else if(constructor.userMonth == 8){
                           constructor.userMonth   = 7;
                           constructor.userDay    =  31;
                        }else{
                           constructor.userDay     =  30;
                           constructor.userMonth   = constructor.userMonth - 1;
                        }
                     }else{
                        constructor.userDay  = constructor.userDay-1;
                     }
                  break;
                  case 3:
                     if(constructor.userDay  == 1){
                        if(constructor.userYear % 4 ==0){
                           constructor.userDay     =  29;
                           constructor.userMonth   =  2;
                        }else{
                           constructor.userDay    =  28;
                           constructor.userMonth   =  2;
                        }
                     }else{
                        constructor.userDay = constructor.userDay-1;
                     }
                  break;
                  case 2:
                  case 4:
                  case 6:
                  case 9:
                  case 11:
                     if(constructor.userDay  == 1){
                        constructor.userDay     =  31;
                        constructor.userMonth   =  constructor.userMonth-1;
                     }else{
                       constructor.userDay     =  constructor.userDay -1;
                     }
                  break;
               }
               if(constructor.userDay<10){
                  $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_day').val('0'+constructor.userDay);
               }else{
                  $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_day').val(constructor.userDay);
               }
               if(constructor.userMonth<10){
                  $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_month').val('0'+constructor.userMonth);
               }else{
                  $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_month').val(constructor.userMonth);
               }
               $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_year').val(constructor.userYear);
               if(constructor.userDay<10){
                  if(constructor.userMonth<10){
                     _this.val('0'+constructor.userDay+'/0'+constructor.userMonth+'/'+constructor.userYear);
                  }else{
                     _this.val('0'+constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
                  }
               }else if(constructor.userDay>=10){
                  if(constructor.userMonth<10){
                     _this.val(constructor.userDay+'/0'+constructor.userMonth+'/'+constructor.userYear);
                  }else{
                     _this.val(constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
                  }
               }
            });
            
            //Next
             $('#'+ constructor.dp_wrap_ID).on('click','.miniDatePicker_next',function(){
               dp_click =  true;
               constructor.userMonth   =  parseInt(constructor.userMonth);
               switch(constructor.userMonth){
                  case 1:
                  case 3:
                  case 5:
                  case 7:
                  case 8:
                  case 10:
                  case 12: 
                     if(constructor.userDay  == 31){
                        if(constructor.userMonth   == 12){
                           constructor.userMonth   = 1;
                           constructor.userDay    =  1;
                           constructor.userYear    =  constructor.userYear + 1;
                        }else if(constructor.userMonth == 7){
                           constructor.userMonth   = 8;
                           constructor.userDay    =  1;
                        }else{
                           constructor.userDay     =  1;
                           constructor.userMonth   = constructor.userMonth + 1;
                        }
                     }else{
                        constructor.userDay  = constructor.userDay+1;
                     }
                  break;
                  case 2:
                     if(constructor.userDay  == 28){
                        if(constructor.userYear % 4 ==0){
                           constructor.userDay     =  29;
                           constructor.userMonth   =  2;
                        }else{
                           constructor.userDay    =  1;
                           constructor.userMonth   =  3;
                        }
                     }else if(constructor.userDay  == 29){
                        constructor.userDay    =  1;
                        constructor.userMonth   =  3;
                     }else{
                        constructor.userDay = constructor.userDay+1;
                     }
                  break;
                  case 4:
                  case 6:
                  case 9:
                  case 11:
                     if(constructor.userDay  == 30){
                        constructor.userDay     =  1;
                        constructor.userMonth   =  constructor.userMonth+1;
                     }else{
                       constructor.userDay     =  constructor.userDay +1;
                     }
                  break;
               }
               if(constructor.userDay<10){
                  $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_day').val('0'+constructor.userDay);
               }else{
                  $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_day').val(constructor.userDay);
               }
               if(constructor.userMonth<10){
                  $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_month').val('0'+constructor.userMonth);
               }else{
                  $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_month').val(constructor.userMonth);
               }
               $('#'+constructor.dp_wrap_ID).find('.miniDatePicker_year').val(constructor.userYear);
               if(constructor.userDay<10){
                  if(constructor.userMonth<10){
                     _this.val('0'+constructor.userDay+'/0'+constructor.userMonth+'/'+constructor.userYear);
                  }else{
                     _this.val('0'+constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
                  }
               }else if(constructor.userDay>=10){
                  if(constructor.userMonth<10){
                     _this.val(constructor.userDay+'/0'+constructor.userMonth+'/'+constructor.userYear);
                  }else{
                     _this.val(constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
                  }
               }
            });
            $('#'+constructor.dp_wrap_ID).on('change','.miniDatePicker_day',function(){
               constructor.userDay  =  parseInt($(this).val());
               if(constructor.userDay<1)  constructor.userDay=1;
               if((constructor.userDay>28) && (constructor.userMonth == 2)){
                  if(constructor.userYear % 4 ==0){
                     constructor.userDay  =  29;
                  }else{
                     constructor.userDay  =  28;
                  }
               }else if((constructor.userDay>30) && (constructor.userMonth !== 2)){
                  switch(constructor.userMonth){
                     case 1:
                     case 3:
                     case 5:
                     case 7:
                     case 8:
                     case 10:
                     case 12:
                        constructor.userDay  =  31;
                     break;
                     case 2:
                     case 4:
                     case 6:
                     case 9:
                     case 11:
                        constructor.userDay  =  30;
                     break;
                  }
               }
               //console.log(constructor.userDay);
               if(constructor.userDay<10){
                  if(constructor.userMonth<10){
                     _this.val('0'+constructor.userDay+'/0'+constructor.userMonth+'/'+constructor.userYear);
                  }else{
                     _this.val('0'+constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
                  }
               }else{
                  _this.val(constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
               }
            });
            $('#'+constructor.dp_wrap_ID).on('change','.miniDatePicker_month',function(){
               constructor.userMonth  =  parseInt($(this).val());
               if(constructor.userMonth<1) constructor.userMonth = 1;
               if(constructor.userMonth>12) constructor.userMonth = 12;
               if(constructor.userDay<10){
                  if(constructor.userMonth<10){
                     _this.val('0'+constructor.userDay+'/0'+constructor.userMonth+'/'+constructor.userYear);
                  }else{
                     _this.val('0'+constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
                  }
               }else{
                  _this.val(constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
               }
            });
            $('#'+constructor.dp_wrap_ID).on('change','.miniDatePicker_year',function(){
               constructor.userYear  =  $(this).val();
               if(constructor.userDay<10){
                  if(constructor.userMonth<10){
                     _this.val('0'+constructor.userDay+'/0'+constructor.userMonth+'/'+constructor.userYear);
                  }else{
                     _this.val('0'+constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
                  }
               }else{
                  _this.val(constructor.userDay+'/'+constructor.userMonth+'/'+constructor.userYear);
               }
            });
            $(this).focusin(function(){
               constructor.show();
            });
            var dp_top = $(this).offset().top;
            var dp_left = $(this).offset().left;
            var dp_right = ($( window ).outerWidth() - dp_left - _this.outerWidth());
            console.log(_this.outerWidth());
            //alert(dp_left);
            // Option position Y
            if(constructor.dp_position_y){
               $('#'+constructor.dp_wrap_ID).css({
                  'top':dp_top-27
               })
            }else{
               $('#'+constructor.dp_wrap_ID).css({
                  'top':dp_top+25
               })
            }
            // Option position X
            if(constructor.dp_position_x){
               $('#'+constructor.dp_wrap_ID).css({
                  'left':dp_left
               })
            }else{
               $('#'+constructor.dp_wrap_ID).css({
                  'right':dp_right  
               })
            }
         })
      }
   })
})(jQuery)
