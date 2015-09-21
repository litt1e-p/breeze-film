$(function(){
	$('#authGroupDatagrid').datagrid({
		title:'角色列表',
		//url:authGroupUrl+"?randnum="+Math.floor(Math.random()*1000000),
		url:authGroupUrl,
		fitColumns:true,
		rownumbers:true,
		singleSelect:true,
		pageSize:15,
		pageNumber:1,
		pageList:[10,15,20,25,30,40,50],
		columns:[[
		{field:'title',title:'角色名称'},
		{field:'describe',title:'角色描述'},
		{field:'status',title:'状态',formatter:checkYesNo},
		]],
		pagination:true,
		toolbar:[
			{
				iconCls:'icon-add',
				text:'添加',
				handler:function(){
					$('#authGroupAddDialog').dialog('open').dialog('setTitle','角色添加');
					$('#authGroupAddForm').form('clear');
					$('#authGroupStatus').combobox('select',1);
				}
			},'-',{
				iconCls:'icon-edit',
				text:'修改',
				handler:function(){
					var authGroupRow =$('#authGroupDatagrid').datagrid('getSelected');
		            if (authGroupRow){
		            	if(authGroupRow.id==1||authGroupRow.id==2){
		                    $.messager.alert('错误提示','超级管理员和默认组不能修改！','error');
		                    return false;
		                }
		                $('#authGroupEditDialog').dialog('open').dialog('setTitle','编辑角色');
		                $('#authGroupEditForm').form('load',authGroupRow);
		            }
				}
			},'-',{
				iconCls:'icon-remove',
				text:'删除',
				handler:function(){
					var authGroupRow=$('#authGroupDatagrid').datagrid('getSelected');
		            if (authGroupRow){
		                if(authGroupRow.id==1||authGroupRow.id==2){
		                    $.messager.alert('错误提示','超级管理员和默认组不能删除！','error');
		                    return false;
		                }
	                	$.messager.confirm('删除提示','真的要删除此角色组吗?删除将不能再恢复！',function(r){
		                    if (r){
		                        $.post(authGroupDelUrl,{id:authGroupRow.id},function(result){
		                        	
		                            if (result.status){
		                                $('#authGroupDatagrid').datagrid('reload');
		                            }else{
		                                $.messager.alert('错误提示',result.message,'error');
		                            }
		                        },'json').error(function(data){
									var info=eval('('+data.responseText+')');
									$.messager.confirm('错误提示',info.message,function(r){});
		                        });
		                    }
	                	});
            		}
				}
			},'-',{
				iconCls:'icon-group_go',
				text:'权限设置',
				handler:function(){
					var authGroupRow=$('#authGroupDatagrid').datagrid('getSelected');
					if (authGroupRow&&authGroupRow.id!=1){
						$('#authAccessSetTree').tree({
					    	url:authAccessListUrl,
					    	checkbox:true,
					    	checkOnSelect:true,
					    	lines:true,
					    	queryParams:{gid:authGroupRow.id},
					    	//cascadeCheck:true,
					    	onlyLeafCheck:true,
					    	onLoadError:function(data){
					    		var info=eval('('+data.responseText+')');
								$.messager.confirm('错误提示',info.message,function(r){
									$('#authAccessSetDialog').dialog('close');
								});
					    	}
					    });	
					    $('#authAccessSetDialog').dialog({
							title:'权限设置&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当前用户组：<font color="red">'+authGroupRow.title+"</font>",
							resizable:true,
							onClose:function(){
								$('#authAccessSetTree').tree('collapseAll');
							}
						}).dialog('open');		               
		            }
				}
			}
		]
	});
});

authGroupObj={
	add:function(url){
		$('#authGroupAddForm').form('submit',{
			url:url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
            	var result=eval('('+result+')');
                if (!result.status){
                    $.messager.confirm('错误提示',result.message,function(r){
                    	$('#authGroupAddDialog').dialog('close');  
                    });
                } else {
                    $('#authGroupAddDialog').dialog('close');       
                    $('#authGroupDatagrid').datagrid('reload'); 
                }
            }
		});
	},
	save:function(url){
		$('#authGroupEditForm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
            	 var result=eval('('+result+')');
                if (!result.status){
                     $.messager.confirm('错误提示',result.message,function(r){
                    	$('#authGroupEditDialog').dialog('close');  
                    });
                }else{
                    $('#authGroupEditDialog').dialog('close');      
                    $('#authGroupDatagrid').datagrid('reload');    
                }
            }
        });
	},
	set:function(url){	
		var authGroupRow=$('#authGroupDatagrid').datagrid('getSelected');
		var id=authGroupRow.id;
		var node=$('#authAccessSetTree').tree('getChecked');
		var rule=[];
		for(var i=0;i<node.length;i++){
    		rule.push(node[i].id);
    	}
    	var rules=rule.join(',');
    	$.post(url,{id:id,rules:rules},function(result){
            if (result.status){
                $('#authAccessSetDialog').dialog('close');
            }else{
                $.messager.alert('错误提示',result.message,'error');
            }
        },'json').error(function(data){
        	var info=eval('('+data.responseText+')');
			$.messager.confirm('错误提示',info.message,function(r){
				$('#authAccessSetDialog').dialog('close');
			});
        });
	}
	
}