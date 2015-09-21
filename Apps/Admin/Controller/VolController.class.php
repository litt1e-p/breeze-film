<?php 

namespace Admin\Controller;

use Admin\Controller\CommonController;

class VolController extends CommonController
{
	public function index()
	{
		$this->display();
	}

	public function volList()
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
		$volList = M('vol')->where($map)->order("created DESC")->page($page.','.$rows)->select();
		$count   = M('vol')->where($map)->count();
		if(!empty($volList)){
			$data['total'] = $count;
			foreach ($volList as $key => $val) {
				$volList[$key]['publishedStat']  = $val['published'] == 2 ? "已删除" : ($val['published'] == 1 ? "已发布" : "未发布");
			}
			$data['rows']  = $volList;
        }else{
			$data['total'] = 0;
			$data['rows']  = 0;
        }
        $this->ajaxReturn($data);
	}

	public function addVol()
	{
		if (!I('post.')) {
			return false;
		}
		$volDao = M('vol');
		$volDao->startTrans();
		$volData['title']     = I('post.title');	
		$volData['author']    = I('post.author');
		$volData['published'] = I('post.published');
		$volData['created']   = time();
		$volId = M('vol')->add($volData);
		if (!$volId) {
			$volDao->rollback();
			$this->ajaxReturn(['message'=>'添加失败1', 'status'=>false]);
		}

		$films = $this->explodeData(I('post.films'));
		if (is_array($films)) {
			foreach ($films as $fk => $fv) {
				$filmdata[$fk]['film_url'] = $fv;
				$filmdata[$fk]['vol_id'] = $volId;
			}
			$filmRs = M('film')->addAll($filmdata);
		} else {
			$filmRs = M('film')->add(['film_url'=>$films, 'vol_id'=>$volId]);
		}
		if (!$filmRs) {
			$volDao->rollback();
			$this->ajaxReturn(['message'=>'添加失败2', 'status'=>false]);
		}

		$musics = $this->explodeData(I('post.musics'));
		if (is_array($films)) {
			foreach ($musics as $mk => $mv) {
				$musicData[$mk]['music_url'] = $mv;
				$musicData[$mk]['vol_id'] = $volId;
			}
			$musicRs = M('music')->addAll($musicData);
		} else {
			$musicRs = M('music')->add(['music_url'=>$musics, 'vol_id'=>$volId]);
		}
		
		if (!$musicRs) {
			$volDao->rollback();
			$this->ajaxReturn(['message'=>'添加失败3', 'status'=>false]);
		}

		$article = I('post.article');
		$articleData['content'] = $article;
		$articleData['vol_id'] = $volId;
		$articleRs = M('article')->add($articleData);
		if (!$articleRs) {
			$volDao->rollback();
			$this->ajaxReturn(['message'=>'添加失败4', 'status'=>false]);
		}
		$volDao->commit();
    	$result['message']='添加成功!';
    	$result['status']=true;	
    	$this->ajaxReturn($result);
	}

	private function explodeData($str, $der = ';')
	{
		if (stripos($str, $der)) {
			return explode($der, $str);
		}
		return $str;
	}

	public function delVol()
	{
		if (!I('post.')) {
			return false;
		}
		$rs = M('vol')->where(['id' => I('post.id')])->save(['published' => 2]);
		if($rs !== false){
			$return['message'] = '删除成功!';
			$return['status'] = true;
		}else{
			$return['message'] = '删除出错!';
			$return['status'] = false;
		}
		$this->ajaxReturn($return);
	}

	public function editVol()
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
			$status = M('vol')->where($map)->save($data);
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

	public function matchVol(){
        $title = I('post.q');
        $where['title'] = array('LIKE','%'.$title.'%');
        $volInfo = M('vol')->field(array('id','title'))->where($where)->limit(15)->select();
        $this->ajaxReturn($volInfo);
    }

	public function checkVolTitle(){
        if(!IS_POST) exit;  
        $result = $this->checkDuplicateVol(I('post.val'));
        if($result) {
            $return['status']=false;
            $return['id']=$result['id'];
        }else {
            $return['status']=true;
            $return['id']='0';
        }
        $this->ajaxReturn($return);
    }

    private function checkDuplicateVol($valTitle = '')
    {
    	$sql = "SELECT * FROM " . C('DB_PREFIX') . "vol WHERE title = '" . $valTitle . "'";
        return M('vol')->query($sql); 
    }
}