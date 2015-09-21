<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class IndexController extends CommonController {
    public function index(){
    	$this->assign('webName','xxx');
        $this->display();
    }

    //退出
	public function logout(){
		unset($_SESSION['username']);
		unset($_SESSION['uid']);
		unset($_SESSION['gid']);
		unset($_SESSION['gname']);
		$return=array();
		$return['message']='退出成功！';
		$return['status']=true;
		$this->ajaxReturn($return);
	}

	public function error(){
		$this->display();
	}

}