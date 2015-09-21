$(function(){
	$("#authRuleDatagrid").datagrid({
		title:'规则列表',
		url:authRuleUrl+"?randnum="+Math.floor(Math.random()*1000000),
		fitColumns:true,
		rownumbers:true,
		pageSize:15,
		pageNumber:1,
		pageList:[2,5,10,15,20,25,30,40,50],
		columns:[[
		{field:'id',title:'规则id',checkbox:true},
		{field:'name',title:'规则标识'},
		{field:'title',title:'规则简述'},
		{field:'condition',title:'附加条件'},
		{field:'moduleName',title:'所属模块'},
		{field:'status',title:'状态',formatter:checkYesNo},
		]],
		pagination:true,
	});
});

authRuleObj={
	add:function(url){
		$('#authRuleAddForm').form('submit',{
			url:url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
            	var result=eval('('+result+')');
                if (!result.status){
                    $.messager.confirm('错误提示',result.message,function(r){
                    	$('#authRuleAddDialog').dialog('close');  
                    });
                } else {
                    $('#authRuleAddDialog').dialog('close');       
                    $('#authRuleDatagrid').datagrid('reload'); 
                }
            }

		});
	},
	save:function(url){
		$('#authRuleEditForm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
            	 var result=eval('('+result+')');
                if (!result.status){
                    $.messager.confirm('错误提示',result.message,function(r){
                    	$('#authRuleEditDialog').dialog('close');  
                    });
                }else{
                    $('#authRuleEditDialog').dialog('close');      
                    $('#authRuleDatagrid').datagrid('reload');    
                }
            }
        });
	},
	showAll:function(){
		$('#authRuleDatagrid').datagrid({ queryParams:''});
	},
	addBar:function(){
		$('#authRuleAddDialog').dialog('open');
		$('#authRuleAddForm').form('clear');
		$('#authRuleStatus').combobox('select',1);
		$('#authRuleMid').combobox('select',18);
	},
	editBar:function(){
		var authRuleRow =$('#authRuleDatagrid').datagrid('getSelections');
        if (authRuleRow.length>1){		            	
            $.messager.alert('提示','一次只能修改一条记录!','info');
        }else if(authRuleRow.length==1){
        	$('#authRuleEditDialog').dialog('open');
            $('#authRuleEditForm').form('load',authRuleRow[0]);
        }
	},
	removeBar:function(){
		var authRuleRow =$('#authRuleDatagrid').datagrid('getSelections');
        if (authRuleRow.length>0){
        	$.messager.confirm('删除提示','真的要删除'+authRuleRow.length+'条规则吗?删除将不能再恢复！',function(r){
                if (r){
                	var ids=[];
                	for(var i=0;i<authRuleRow.length;i++){
                		ids.push(authRuleRow[i].id);
                	}
                	var id=ids.join(',');
                    $.post(authRuleDelUrl,{id:id},function(result){
                        if (result.status){
                            $('#authRuleDatagrid').datagrid('reload');
                        }else{
                            $.messager.alert('错误提示',result.message,'error');
                        }
                    },'json').error(function(data){
                    	var info=eval('('+data.responseText+')');
						$.messager.confirm('错误提示',info.message,function(r){
							
						});
                    });
                }
        	});
		}
	}
}