<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class SystemController extends CommonController{
	public function index(){

	}
	/**
	* 删除缓存
	* @author 黄药师 <46914685@qq.com> 20150120
	* @access public
	* @return null 
	*/
	public function clearCache(){
		//rulAutoAdd(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,'15','删除缓存');
        $dir=APP_PATH.'/Runtime/';        
        if(is_dir($dir)){
            $this->delDir($dir);
        }
       	$result['message']='删除缓存成功!';
	   	$result['status']=true;
        $this->success('删除缓存成功');
    }

    /**
	* 删除缓存
	* @author 黄药师 <46914685@qq.com> 20150120
	* @access private
	* @return null 
	*/
    private function delDir($dir){
        import("Common.Org.Dir");
        \Dir::delDir($dir);
    }
}
