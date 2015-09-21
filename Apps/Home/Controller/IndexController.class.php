<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	dump("Home/index");
    	// $this->redirect('Admin/Index/index',3,'页面跳转中....');
    }
}