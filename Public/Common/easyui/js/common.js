/** 
*  时间戳转化为日期 如 2014-07-07 23:02:05
*  @author 黄药师 <46914685@qq.com> [20141207]
*  @return string 
*/
function timestampToDateTime(value,row,index){
    if (value==undefined||value=='0') return "";
        var Y,M,D,h,m,s;
        var date=new Date(value*1000);
        Y=date.getFullYear() + '-';
        M=(date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        D=(date.getDate()< 10 ? '0'+date.getDate() : date.getDate()) + ' ';
        h=(date.getHours()< 10 ? '0'+date.getHours() : date.getHours()) + ':';
        m=(date.getMinutes()< 10 ? '0'+date.getMinutes() : date.getMinutes()) + ':';
        s=(date.getSeconds()< 10 ? '0'+date.getSeconds() : date.getSeconds()); 

        return Y+M+D+h+m+s;
}

/** 
*  时间戳转化为日期 如 2014-07-07
*  @author 黄药师 <46914685@qq.com> [20141207]
*  @return string 
*/
function timestampToDate(value){
    if (value==undefined||value=='0') return "";
        var Y,M,D;
        var date=new Date(value*1000);
        Y=date.getFullYear() + '-';
        M=(date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        D=(date.getDate()< 10 ? '0'+date.getDate() : date.getDate()) + ' ';
        return Y+M+D;
}

/** 
*   检测是否启用 返回格式化字符串
*   @author 黄药师 <46914685@qq.com> [20141207]
*   @return string
*/
function checkNoYes(value,row,index){
    if(value=='0'){
        return '<font color="green">√</font>';
    }else{
        return '<font color="red">×</font>';
    }
}

/** 
*   检测状态 返回格式化字符串
*   @author 黄药师 <46914685@qq.com> [20141207]
*   @return string
*/
function checkYesNo(value,row,index){
    if(value=='1'){
        return '<font color="green">√</font>';
    }else{
        return '<font color="red">×</font>';
    }
}

