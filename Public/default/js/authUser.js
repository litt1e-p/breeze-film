$(function(){
	$('#authUserDatagrid').datagrid({
		//url:authUserUrl+"?randnum="+Math.floor(Math.random()*1000000),
		url:authUserUrl,
		title:'用户列表',
		fitColumns:true,
		rownumbers:true,
		pageSize:15,
		pageNumber:1,
		pageList:[2,5,10,15,20,25,30,40,50],
		columns:[[
		{field:'uid',title:'id',checkbox:true},
		{field:'name',title:'用户',sortable:true},
		{field:'realname',title:'姓名'},
		{field:'email',title:'邮箱'},
		{field:'score',title:'积分',sortable:true},
		{field:'title',title:'角色',sortable:true},
		{field:'lastloginip',title:'最后一次登录ip'},
		{field:'lastlogintime',title:'最后一次登录时间',formatter:timestampToDateTime}
		]],
		onLoadError:function(data){
			var info=eval('('+data.responseText+')');
			$.messager.confirm('错误提示',info.message,function(r){
				var tab=$('#黄药师Tabs').tabs('getSelected');
				var index=$('#黄药师Tabs').tabs('getTabIndex',tab);
				$('#黄药师Tabs').tabs('close',index);
			});
		},
		pagination:true,		
	});
});

authUserObj={
	add:function(url){
		$('#authUserAddForm').form('submit',{
			url:url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
            	var result=eval('('+result+')');
                if(!result.status){
                    $.messager.confirm('错误提示',result.message,function(r){
                    	$('#authUserAddDialog').dialog('close');  
                    });
                }else{
                    $('#authUserAddDialog').dialog('close');       
                    $('#authUserDatagrid').datagrid('reload'); 
                }
            },
		});
	},
	save:function(url){
		$('#authUserEditForm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
            	 var result=eval('('+result+')');
                if (!result.status){
                    $.messager.confirm('错误提示',result.message,function(r){
                    	$('#authUserEditDialog').dialog('close');
                    });
                }else{
                    $('#authUserEditDialog').dialog('close');      
                    $('#authUserDatagrid').datagrid('reload');    
                }
            }
        });
	},
	showAll:function(){
		$('#authUserDatagrid').datagrid({ queryParams:''});
	},
	move:function(url){
		$('#authUserMoveForm').form('submit',{
            url: url,
            queryParams:{uid:authUserObj.uid},
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
            	 var result=eval('('+result+')');
                if (!result.status){
                    $.messager.confirm('错误提示',result.message,function(r){
                    	$('#authUserMoveDialog').dialog('close');
                    });
                }else{
                    $('#authUserMoveDialog').dialog('close');      
                    $('#authUserDatagrid').datagrid('reload');    
                }
            }
        });
	},
	addBar:function(){
		$('#authUserAddDialog').dialog('open').dialog('setTitle','用户添加');
		$('#authUserAddForm').form('clear');
		$('#authUserGroup').combobox('select',2);
	},
	editBar:function(){
		var authUserRow=$('#authUserDatagrid').datagrid('getSelections');
        if (authUserRow.length>1){		            	
            $.messager.alert('提示','一次只能修改一条记录!','info');
        }else if(authUserRow.length==1){
        	//if(authUserRow[0].uid==1) $('#authUserGroupMove').combobox({
        	if(authUserRow[0].uid==1) $('#authUserGroupEdit').combobox('readonly');	            	
        	$('#authUserEditDialog').dialog('open').dialog('setTitle','编辑用户');
            $('#authUserEditForm').form('load',authUserRow[0]);
        }
	},
	removeBar:function(){
		var authUserRow=$('#authUserDatagrid').datagrid('getSelections');
        if (authUserRow.length>0){
        	$.messager.confirm('删除提示','真的要删除这'+authUserRow.length+'个用户吗?删除将不能再恢复！',function(r){
                if (r){
                	var ids=[];
                	for(var i=0;i<authUserRow.length;i++){
                		if(authUserRow[i].uid==1) continue;//超级管理员时,跳出此次循环
                		ids.push(authUserRow[i].uid);
                	}
                	var id=ids.join(',');
                	if(id==''){
                		$.messager.alert('错误提示','系统默认的超级管理员不能删除!','error');
                		return false;
                	}
                    $.post(authUserDelUrl,{id:id},function(result){
                        if (result.status){
                            $('#authUserDatagrid').datagrid('reload');
                        }else{
                            $.messager.alert('错误提示',result.message,'error');
                        }
                    },'json').error(function(data){
                    	var info=eval('('+data.responseText+')');
						$.messager.confirm('错误提示',info.message,function(r){
							//$('#authAccessSetDialog').dialog('close');
						});
                    });	
                }
        	});
		}
	},
	switchBar:function(){
		var authUserRow=$('#authUserDatagrid').datagrid('getSelections');
		var str='';
		if(authUserRow[0].uid==1){
			$.messager.alert('提示','系统默认的超级管理员不能移动!','info');
			return false;
		}
		if(authUserRow.length>0){
			var ids=[];
			for(var i=0;i<authUserRow.length;i++){
				if(authUserRow[i].uid==1) continue;//超级管理员跳出,不能移动
				str+="<span class=\"span_黄药师\">"+authUserRow[i].name+'</span>&nbsp;';
				ids.push(authUserRow[i].uid);
			}
			authUserObj.uid=ids.join(',');
			$('#authUserMovePanel').html(str);
			$('#authUserMoveDialog').dialog('open').dialog('setTitle','批量移动');
			$('#authUserGroupMove').combobox('select',2);
		}
	}
}