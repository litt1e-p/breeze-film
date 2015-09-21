<?php 
namespace Admin\Controller;
use Admin\Controller\CommonController;
class AuthController extends CommonController{
    //用户列表
    public function index(){
        $info=M()->table(C('DB_PREFIX').'auth_group')->field('id,title')->select();
        $this->assign('groupInfo',$info);
        $this->display();
    }

    public function group(){        
        $this->display();
    }

    public function rule(){
        //dump($isAuth);
        $authRuleInfo=M()->table(C('DB_PREFIX').'auth_rule')->where(array('pid'=>'0'))->field('id as pid,title')->cache('moduleNameB',300)->select();//查询模块信息 pid=0为模块,否则为规则标识
        $this->assign('authRuleInfo',$authRuleInfo);
        $this->display();
    }

    public function userList(){
        $m=M('auth_user');
        $ck=A('CheckInput');
        //搜索条件
        $map=array();
        $name='';
        $email='';
        $name=$ck->in('用户名','name','cnennumstr','',0,0);        
        $email=$ck->in('邮箱','email','email','',0,0);
        $title=$ck->in('角色','title','cnennumstr','',0,0);
        !empty($name)?$map['username']=array('like','%'.$name.'%'):'';
        !empty($email)?$map['email']=$email:'';
        !empty($title)?$map['title']=$title:'';

        $page=$ck->in('当前页','page','intval','1',0,0);   
        $rows=$ck->in('每页记录数','rows','intval','',0,0);  

        //排序
        $sort=$ck->in('排序','sort','cnennumstr','uid',0,0);
        $order=$ck->in('规则','order','cnennumstr','desc',0,0);
        
        $count=$m->where($map)->cache('authAserList'.$name.$email.$title.$rows,60)->count();
        if(empty($map)){
            $info=$m->alias('AU')->join(C('DB_PREFIX').'auth_group_access as AGA on AGA.uid=AU.uid','left')->join(C('DB_PREFIX').'auth_group as AG on AG.id=AGA.group_id','left')->field('AU.uid,AU.username as name,AU.email,AU.lastloginip,AU.lastlogintime,AU.realname,AU.score,AG.title,AG.id as groupId')->page($page,$rows)->order($sort.' '.$order)->select();
        }else{
            $info=$m->alias('AU')->join(C('DB_PREFIX').'auth_group_access as AGA on AGA.uid=AU.uid','left')->join(C('DB_PREFIX').'auth_group as AG on AG.id=AGA.group_id','left')->field('AU.uid,AU.username as name,AU.email,AU.lastloginip,AU.lastlogintime,AU.realname,AU.score,AG.title,AG.id as groupId')->where($map)->order($sort.' '.$order)->cache('authAserListB',30)->select();
        }
        if(!empty($info)){
            $data['total']=$count;
            $data['rows']=$info;
        }else{
            $data['total']=0;
            $data['rows']=0;
        }
        
        $this->ajaxReturn($data);
    }

    /**
     * ajax检查用户名是否存在
     * @author 黄药师 <46914685@qq.com> [20141210]
     */
    public function checkUser(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $map['username']=$ck->in('用户名称','val','cnennumstr','',2,10);  
        $result=M()->table(C('DB_PREFIX').'auth_user')->where($map)->cache('authUser'.$map['username'],600)->find(); 
        if($result) {
            $return['status']=false;
            $return['id']=$result['uid'];
        }else {
            $return['status']=true;
            $return['id']='0';
        }
        $this->ajaxReturn($return);        
    }

    /**
     * ajax检查角色组是否存在
     * @author 黄药师 <46914685@qq.com> [20141210]
     */
    public function checkGroup(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $map['title']=$ck->in('角色名称','val','cnennumstr','',2,10);  
        $result=M()->table(C('DB_PREFIX').'auth_group')->where($map)->cache('authGroup'.$map['title'],600)->find(); 
        if($result) {
            $return['status']=false;
            $return['id']=$result['id'];
        }else {
            $return['status']=true;
            $return['id']='0';
        }
        $this->ajaxReturn($return);
        
    }
    /**
     * ajax检查规则是否存在
     * @author 黄药师 <46914685@qq.com> [20141210]
     */
    public function checkRule(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $map['name']=$ck->in('规则标识','val','string','',2,80);  
        $result=M()->table(C('DB_PREFIX').'auth_rule')->where($map)->cache('authRule'.$map['name'],600)->find(); 
        if($result) {
            $return['status']=false;
            $return['id']=$result['id'];
        }else {
            $return['status']=true;
            $return['id']='0';
        }
        $this->ajaxReturn($return);        
    }
    /**
     * ajax检查邮箱是否存在
     * @author 黄药师 <46914685@qq.com> [20150119]
     */
    public function checkEmail(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $map['email']=$ck->in('邮箱','val','string','',5,32);  
        $result=M()->table(C('DB_PREFIX').'auth_user')->where($map)->cache('authUser'.$map['email'],600)->find(); 
        if($result) {
            $return['status']=false;
            $return['id']=$result['uid'];
        }else {
            $return['status']=true;
            $return['id']='0';
        }
        $this->ajaxReturn($return);     
        
    }

    //添加管理员
    public function userAdd(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $data['username']=$ck->in('用户名','name','cnennumstr','',4,10);   
        $data['password']=$ck->in('密码','pwd','password','',6,16); 
        $data['realname']=$ck->in('姓名','realname','cnennumstr','',0,16); 
        $data['email']=$ck->in('邮箱','email','string','',0,32); 
        $score=$ck->in('积分','score','intval','',0,0); 
        !empty($score)?$data['score']=$score:'';
        $g['group_id']=$ck->in('会员组id','group','intval','',1,10); 
        $insertId=M()->table(C('DB_PREFIX').'auth_user')->add($data);
        if(!$insertId){
            $return['message']='添加用户失败';
            $return['status']=false;
            $this->ajaxReturn($return);
        }else{
            $g['uid']=$insertId;
            $gid=M()->table(C('DB_PREFIX').'auth_group_access')->add($g);
            if(!$gid){
               $return['message']='添加用户明细表出错!';
               $return['status']=false; 
               $this->ajaxReturn($return);
            }
        }
        $return['message']='添加用户成功!';
        $return['status']=true; 
        $this->ajaxReturn($return);

    }

    //更新用户
    public function userSave(){
       if(!IS_POST) exit; 
        $ck=A('CheckInput');     
        $pwd=$ck->in('密码','pwd','password','',0,16);
        !empty($pwd)?$data['password']=$pwd:''; 
        $data['realname']=$ck->in('姓名','realname','cnennumstr','',0,16); 
        $data['email']=$ck->in('邮箱','email','string','',0,32); 
        $data['score']=$ck->in('积分','score','intval','',0,0); 
        $map['uid']=$ck->in('用户id','uid','intval','',1,0); 
        $g['group_id']=$ck->in('会员组id','groupId','intval','',1,0); 

        $result=M()->table(C('DB_PREFIX').'auth_user')->where($map)->save($data);
        if(false===$result){
            $return['message']='更新用户失败';
            $return['status']=false;
            $this->ajaxReturn($return);
        }else{
            if($map['uid']!='1'){//非超级管理员
                $gid=M()->table(C('DB_PREFIX').'auth_group_access')->where($map)->save($g);
                if(false===$gid){
                   $return['message']='更新用户明细表出错!';
                   $return['status']=false; 
                   $this->ajaxReturn($return);
                }
            }
        }
        $return['message']='更新用户成功!';
        $return['status']=true; 
        $this->ajaxReturn($return);
    }

    /**
     *批量移动用户
     * @author 黄药师 20150125
     */
    public function userMove(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');     
        $uid=$ck->in('用户id','uid','string','',1,0);
        $data['group_id']=$ck->in('角色id','groupId','intval','',1,0);
        if(empty($uid)||empty($data['group_id'])) exit;//为空
        $arr=explode(',', $uid);
        if(in_array('1', $arr)) exit;//id=1是超级管理员,不能移动
        $map['uid']=array('in',$arr);
        $a=M()->table(C('DB_PREFIX').'auth_group_access')->where($map)->save($data);
        if(false===$a){
            $return['message']='移动用户失败';
            $return['status']=false;
        }else{
            $return['message']='移动用户成功';
            $return['status']=true;
        }
        $this->ajaxReturn($return);
    }

    //删除用户
    public function userDel(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $uid=$ck->in('用户id','id','string','',1,0);
        if(empty($uid)) exit;//为空
        $arr=explode(',', $uid);
        if(in_array('1', $arr)) exit;//id=1是超级管理员,不能删除
        $map['uid']=array('in',$arr);
        $result=M()->table(C('DB_PREFIX').'auth_user')->where($map)->delete();
        if(!$result){
            $return['message']='删除用户失败!';
            $return['status']=false;  
        }else{
            $a=M()->table(C('DB_PREFIX').'auth_group_access')->where($map)->delete();
            if(!$a){
                $return['message']='删除用户明细表失败!';
                $return['status']=false; 
            }else{
                $return['message']='删除用户成功!';
                $return['status']=true; 
            }
        }        
        $this->ajaxReturn($return);
    }

    //规则列表
    public function ruleList(){
        if(!IS_AJAX) exit;
        $ck=A('CheckInput');
        //搜索条件
        $map=array();
        $name='';
        $title='';
        $name=$ck->in('规则标识','name','string','',0,80);        
        $title=$ck->in('规则描述','title','string','',0,20);
        !empty($name)?$map['name']=array('like','%'.$name.'%'):'';
        !empty($title)?$map['title']=$title:'';

        $map['pid']=array('neq','0');

        $page=$ck->in('当前页','page','intval','1',0,0);   
        $rows=$ck->in('每页记录数','rows','intval','',0,0);  

        //排序
        $sort=$ck->in('排序','sort','cnennumstr','id',0,0);
        $order=$ck->in('规则','order','cnennumstr','desc',0,0);
        
        $count=M()->table(C('DB_PREFIX').'auth_rule')->where($map)->cache('ruleList'.$name.$email,60)->count();

        $m=M()->table('__AUTH_RULE__')->where(array('pid'=>'0'))->field('id,name')->cache('moduleNameA',300)->select();//获取模块名
        if(!$module=S('module')){
            foreach ($m as $k=>$v) {
                $module[$v['id']]=$v['name'];
            }
            S('module',$module,300);//缓存模块名
        }
        
        $info=M()->table(C('DB_PREFIX').'auth_rule')->where($map)->field('id,name,title,type,status,condition,pid')->page($page,$rows)->order($sort.' '.$order)->select();
        foreach ($info as $k=>$v) {
            $info[$k]['moduleName']=$module[$v['pid']];
        }

        if(!empty($info)){
            $data['total']=$count;
            $data['rows']=$info;
        }else{
            $data['total']=0;
            $data['rows']=0;
        }
        //dump($data);        
        $this->ajaxReturn($data);
    }

	//添加规则
	public function ruleAdd(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $data['title']=$ck->in('规则描述','title','string','',2,20);   
        $data['name']=$ck->in('规则标识','name','string','',2,80); 
        $data['condition']=$ck->in('附加条件','condition','string','',0,100); 
        $data['status']=$ck->in('状态','status','intval','',0,1); 
        $data['pid']=$ck->in('所属模块','pid','intval','',1,0); 
        //规则标识存在
        if(M()->table(C('DB_PREFIX').'auth_rule')->where(array('name'=>$data['name']))->cache('ruleAdd'.$data['name'],60)->find()) exit;

        $result=M()->table(C('DB_PREFIX').'auth_rule')->add($data);
        if(!$result){
            $return['message']='添加规则失败!';
            $return['status']=false;  
        }else{
            $return['message']='添加规则成功!';
            $return['status']=true; 
        }        
        $this->ajaxReturn($return);
	}

    //更新规则
    public function ruleSave(){
         if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $data['title']=$ck->in('规则描述','title','string','',2,20);   
        $data['name']=$ck->in('规则标识','name','string','',2,80); 
        $data['condition']=$ck->in('附加条件','condition','string','',0,100); 
        $data['status']=$ck->in('状态','status','intval','',0,1); 
        $data['pid']=$ck->in('所属模块','pid','intval','',1,0); 
        $map['id']=$ck->in('规则id','id','intval','',1,0);        

        $result=M()->table(C('DB_PREFIX').'auth_rule')->where($map)->save($data);
        if(false===$result){
            $return['message']='更新规则失败!';
            $return['status']=false;  
        }else{
            $return['message']='更新规则成功!';
            $return['status']=true; 
        }        
        $this->ajaxReturn($return);
    }

    /**
     * 删除规则
     * 
     * @author 有冇有 <46914685@qq.com> [20150122]
     */
    public function ruleDel(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $id=$ck->in('规则id','id','string','',1,0);
        $map['id']=array('in',$id);
        $result=M()->table(C('DB_PREFIX').'auth_rule')->where($map)->delete();
        if(!$result){
            $return['message']='删除规则失败!';
            $return['status']=false;  
        }else{
            $return['message']='删除规则成功!';
            $return['status']=true;
        }        
        $this->ajaxReturn($return);
    }

    //角色列表
    public function groupList(){
        $ck=A('CheckInput');
        $page=$ck->in('当前页','page','intval','1',0,0);   
        $rows=$ck->in('每页记录数','rows','intval','',0,0);

        $count=M()->table(C('DB_PREFIX').'auth_group')->cache('authGroupListA',30)->count();
        $info=M()->table(C('DB_PREFIX').'auth_group')->cache('authGroupListB',600)->order('id desc')->page($page,$rows)->select();
        $data['total']=$count;
        $data['rows']=$info;
        $this->ajaxReturn($data);
    }

    //添加角色
    public function groupAdd(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $data['title']=$ck->in('角色名称','title','cnennumstr','',2,10);   
        $data['describe']=$ck->in('角色描述','describe','cnennumstr','',0,16); 
        $data['status']=$ck->in('状态','status','intval','',0,1); 
        $result=M()->table(C('DB_PREFIX').'auth_group')->add($data);
        if(!$result){
            $return['message']='添加角色失败!';
            $return['status']=false;  
        }else{
            $return['message']='添加角色成功!';
            $return['status']=true;
            S('authGroupListA',null);//清除缓存
            S('authGroupListB',null);//清除缓存

        }        
        $this->ajaxReturn($return);
    }

    //删除角色
    public function groupDel(){
       if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $map['id']=$ck->in('状态','id','intval','',1,0);
        if($map['id']=='1'||$map['id']=='2') exit;//超级管理员和默认组不能删除
        $result=M()->table(C('DB_PREFIX').'auth_group')->where($map)->delete();
        if(!$result){
            $return['message']='删除角色失败!';
            $return['status']=false;  
        }else{
            $a_=M()->table(C('DB_PREFIX').'auth_group_access')->where(array('group_id'=>$map['id']))->save(array('group_id'=>'2'));//切换用户到默认组,防止出错
            if(false===$a_){
                $return['message']='更新用户明细表失败!';
                $return['status']=false;
            }else{
                $return['message']='删除角色成功!';
                $return['status']=true;
                S('authGroupListA',null);//清除缓存
                S('authGroupListB',null);//清除缓存
            }
        }        
        $this->ajaxReturn($return);
    }

    //更新角色组
    public function groupSave(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $data['title']=$ck->in('角色名称','title','cnennumstr','',2,10);   
        $data['describe']=$ck->in('角色描述','describe','cnennumstr','',0,16); 
        $data['status']=$ck->in('状态','status','intval','',0,1); 
        $map['id']=$ck->in('id','id','intval','',0,0); 
        if($map['id']=='1'&&$map['id']=='2') exit;//超级管理员和默认组不能修改
        $result=M()->table(C('DB_PREFIX').'auth_group')->where($map)->save($data);
        if(false===$result){
            $return['message']='更新角色失败!';
            $return['status']=false;  
        }else{
            $return['message']='更新角色成功!';
            $return['status']=true; 
            S('authGroupListA',null);//清除缓存
            S('authGroupListB',null);//清除缓存
        }        
        $this->ajaxReturn($return);        
    }

    /**
     *权限设置列表 
     * 
     */
    public function accessList(){
        $id=(int)I('id');//父级id
        $gid=(int)I('gid');//角色组id
        $map['pid']=$id?$id:0;
        $rules=M()->table('__AUTH_GROUP__')->where(array('id'=>$gid))->getField('rules');//角色组rules
        $data=M()->table('__AUTH_RULE__')->where($map)->field('id,title as text,pid as parentId,state')->select();
        if(!empty($rules)){
            $arr=explode(',', $rules);
            foreach ($data as $k=>$v) {
                if(in_array($v['id'], $arr)){
                    $v['checked']=true;
                    $data[$k]=$v;
                }
            }
        }
        $this->ajaxReturn($data);
    }

    //更新角色拥有的规则
    public function AccessSet(){
        if(!IS_POST) exit; 
        $ck=A('CheckInput');  
        $data['rules']=$ck->in('权限列表','rules','string','',0,0);
        $map['id']=$ck->in('角色组id','id','intval','',1,0);
        if($map['id']=='1'&&empty($map['id'])) exit;//超级管理员或空退出

        $result=M()->table(C('DB_PREFIX').'auth_group')->where($map)->save($data);
        if(false===$result){
            $return['message']='权限设置失败!';
            $return['status']=false;  
        }else{
            $return['message']='权限设置成功!';
            $return['status']=true;
        }        
        $this->ajaxReturn($return);
    } 
}
?>