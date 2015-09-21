<?php 
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
	//登录页面
	public function index(){
		if(!empty($_SESSION['username'])) $this->redirect('Index/index');
		$this->display();
	}

	//验证码
	public function verify(){
		ob_end_clean();
		$verify=new \Think\Verify();
		$verify->length=4;
		$verify->entry();
	}

	//登录验证
	public function checkLogin(){
		$result=array();
    	$ck=A('CheckInput');

    	$code=$ck->in('验证码','verify','cnennumstr','',4,7);
    	$verify=new \Think\Verify();
    	if(!$verify->check($code)){
    		$result['message']='验证码错误！请刷新再次输入。';
    		$result['status']=false;
    		$this->ajaxReturn($result);
    	}

		$map['username']=$ck->in('用户名','name','cnennumstr','',5,15);	
		$map['password']=$ck->in('密码','pwd','password','',5,20);	
		$data['lastlogintime']=$_SERVER['REQUEST_TIME'];
		$data['lastloginip']=get_client_ip();

		$admin=M('auth_user');
		$status=$admin->where($map)->find();
		if(!$status){
			$result['message']='登陆失败！用户名或密码错误。';
			$result['status']=false;
			$this->ajaxReturn($result);
		}

		$info=$admin->where($map)->save($data);
		if(false===$info){
			$result['message']='更新时间和ip出错';
			$result['status']=false;
			$this->ajaxReturn($result);
		}

		session('username',$status['username']);
		session('uid',$status['uid']);
		session('logintime',$data['lastlogintime']);

		$result['message']='登陆成功！';
		$result['status']=true;
		$this->ajaxReturn($result);
	}

	public function _empty(){
        $this->error('你请求的页面不存在!!');
    }

}

?>