var Validate=function(data){
    var messageErrorJSValidate = $('#messageErrorJSValidate');
    var count_error = 0;
    var message = "";
    var messageError = {
        set_error_background:function(obj,background,color){
            var tag = obj.prop("tagName");
            switch(tag){
                case "INPUT":
                case "TEXTAREA":
                    obj.css('background',background);
                    obj.css('color',color);
                    break;
            }
        },
        set_text_error:function(obj,wrong){
            obj.parent().parent().attr('class','has-error');
            obj.parent().find(".error").html(wrong);
        },
        rm_tag_error:function(obj){
            obj.parent().parent().removeAttr('class');
        },
        tag:function(obj,type){
            this.set_error_background(obj,'red');
        }
    }
    var check_vali = function(func,value,object){
        var name =  (object.attr('data-label'))?object.attr('data-label'):"";
        var obj = {
            self:this,
            build_param:function(func,value){
                var ifs = function(_func){
                    var not_empty =function(str){
                        return !str.length ==0;
                    };
                   var empty =function(str){
                        return str.length ==0;
                   };
                   var action ={

                       'ac_?':function(str,value){
                          var _fuc = str.split('.');
                          if(_fuc.length == 2){
                             switch("__"+_fuc[0]){
                                 case '__!empty':
                                     if(empty(value)){
                                         return "no";
                                     }
                                 break;
                                 case '__empty':
                                     if(not_empty(value)){
                                         return "no";
                                     }
                                 break;
                             }
                          }
                          return _fuc[_fuc.length-1];
                       }
                   };
                   var a = _func.split("-");
                   if(a.length ==2){
                      var doTestFunc = action["ac_"+a[0]];
                       if(doTestFunc){
                          return doTestFunc.call(null,a[1],value);
                       }
                   }
                   return _func;
                }
                var exec = /\(([^\)]+)\)/.exec(func);
                var param = new Array;
                param.push(value);
                if(exec){
                    var sub_param = exec[1].split(",");
                    $.each(sub_param,function(i,v){
                        param.push(v);
                    })
                    func = func.replace(exec[0],"");
                }
                func = ifs(func,value);
                return {
                    'func':func,
                    'param':param
                }
            },
            'is_url':function(value){
                var RegExp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
                if(RegExp.test(value)){
                    return true;
                }else{

                   if(message.length == 0){
                        message = $("#messageErrorJs").find(".validatorDateFormat").html();
                   }
                        count_error++;
                    return false;
                }
            },
            empty: function(value) {
                if(typeof value == 'undefined' || value.length == 0){
                    if(message.length == 0){
                        var _mesage = $("#messageErrorJs").find(".validatorContentEmpty").html();
                        message = _mesage;
                    }
                    count_error++;
                    return true;
                }else{

                    return false;
                }
            },
            date_time:function(value){
                var regEx = /^\d{4}-\d{2}-\d{2}$/;
                if (value.match(regEx) != null){
                    object.parent().parent().removeAttr('class');
                    return true;
                }else{
                    if(message.length == 0){message = name+" date_time !";}
                    object.parent().parent().attr('class','has-error');
                    count_error++;
                    return false;
                }
            },
            length:function(value,min,max){
                    max = (typeof(max)==='undefined')?9999:max;
                    if(value.length < min || value.length > max){
                      //  messageError.set_error_background(object,'red',"#FFFFFF");
                        var _mesage = $("#messageErrorJs").find(".validatorContent").html();
                        if(message.length == 0){
                            message =_mesage.replace('[1]',min);
                            message = message.replace('[2]',max);
                        }
                        count_error++;
                        return true;
                    }else{
                       // messageError.set_error_background(object,'#FFFFFF',"#000000");
                        return false;
                    }
            }
        };
        build = obj.build_param(func,value);
        func = build.func;
        var param = build.param;
        var doTestFunc = obj[func];
        //console.log(func+":"+param);
        if(doTestFunc)
            doTestFunc.apply(obj,param);
    }
    var get_value = function(dom){
        var tag = dom.prop("tagName");
        var value = "";
        switch(tag){
            case "INPUT":
            case "TEXTAREA":
                value = dom.val();
                break;
        }
        return value;
    }
    var run_validate = function(object,key){
        var array = object.vali.split("|");
        var value = get_value(object.object);
        $.each(array,function(i,func){
            check_vali(func,value,object.object);
        });
    }
    $.each(data,function(i,object){
        run_validate(object,i);
        messageError.set_text_error(object.object,message);
        if(message.length == 0){
            messageError.rm_tag_error(object.object);
        }
        message = "";
    });
    return count_error;
}