<?php 

namespace Admin\Controller;

use Admin\Controller\CommonController;

class ArticleController extends CommonController
{
	public function index()
	{
		$this->display();
	}

	public function articleList()
	{
		$page   = I('page');
		$rows   = I('rows');
		$page   = isset($page) ? intval($page) : 1;
		$rows   = isset($rows) ? intval($rows) : 10;
		$title  = I('title');
		$author = I('author');
		$map    = [];
		$data   = [];
		!empty($title) ? $map['title']   = array('like', '%' . $title . '%') : '';
		!empty($author) ? $map['author'] = array('like', '%' . $author . '%') : '';
		$articleList = M('article')->where($map)->order("created DESC")->page($page.','.$rows)->select();
		$count   = M('article')->where($map)->count();
		if(!empty($articleList)){
			$data['total'] = $count;
			foreach ($articleList as $key => $val) {
				$articleList[$key]['statusDesc']  = $val['status'] == 2 ? "已删除" : ($val['status'] == 1 ? "已发表" : "未发表");
			}
			$data['rows']  = $articleList;
        }else{
			$data['total'] = 0;
			$data['rows']  = 0;
        }
        $this->ajaxReturn($data);
	}

	public function addArticle()
	{
		if (IS_POST) {
			$data['title']   = I('post.title');	
			$data['author']  = I('post.author');
			$data['vol_id']  = I('post.vol_id');
			$data['status']  = I('post.status');
			$data['content'] = I('post.content');
			$data['created'] = time();
			$status = M('article')->add($data);
			if($status){
		    	$result['message']='添加成功!';
		    	$result['status']=true;	
			}else{
				$result['message']='添加失败!';
		    	$result['status']=false;	
			}
	    	$this->ajaxReturn($result);
	    }else {
	    	$this->display();
	    }
	}

	public function delArticle()
	{
		if (!I('post.')) {
			return false;
		}
		$rs = M('article')->where(['id' => I('post.id')])->save(['published' => 2]);
		if($rs !== false){
			$return['message'] = '删除成功!';
			$return['status'] = true;
		}else{
			$return['message'] = '删除出错!';
			$return['status'] = false;
		}
		$this->ajaxReturn($return);
	}

	public function editArticle()
	{
		if (!I('post.')) {
			return false;
		}
		$map       = [];
		$map['id'] = I('get.id');
		$return = [];
		if(empty($map['id'])){
			$return['message'] = 'id不能为空!';
			$return['status']  = false;
		}else{
			$data              = [];
			$data['title']     = I('post.title');
			$data['author']    = I('post.author');
			$data['music_num'] = I('post.music_num');
			$data['film_num']  = I('post.film_num');
			$data['published'] = I('post.published');
			$status = M('article')->where($map)->save($data);
			if(false === $status){
				$return['message'] = '更新出错!';
				$return['status']  = false;
			}else{
				$return['message'] = '更新成功!';
				$return['status']  = true;
			}
		}
		$this->ajaxReturn($return);
	}
}