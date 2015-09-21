 var flag=null;
 $.extend($.fn.validatebox.defaults.rules, {
	//检测两次密码是否存在
    equals:{
        validator: function(value,param){
            return value==$(param[0]).val();
        },
        message: '两次密码不同!'
    },

    //检测目标是否存在,type:类型(用户名或邮箱);val:值;M:模型
    //param有2或3个参数;2:提示信息;2:条件对象;3:请求路径
    exists:{
    	validator:function(value,param){
    		var len=param.length;
    		var d=len==3?{'val':value,'id':$(param[1]).val()}:{'val':value};
            var __url=len==3?param[2]:param[1];
    		$.ajax({
                async:false,
                type:"POST",
                url:__url,
                cache:false,
                data:d,
                success: function(data){
                	if(len==3 && $(param[1]).val()==data.id){
                		flag=true;
                	}else{
                		data.status?flag=true:flag=false;
                	}
                }
            });
			return flag;
    	},
    	message:'该{0}已经存在!请换个{0}',
    },
    longness:{
        validator:function(value, param){
            return value.length >= param[0];
        },
        message: '长度必须为{0}个字符',
    }


});