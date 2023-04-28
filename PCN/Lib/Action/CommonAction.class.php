<?php
class CommonAction extends Action {
  function _initialize() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')])){  	   
			redirect(__APP__."/Public/login?jumpUrl=".__SELF__);
		} else{
			if (!$this->isAjax()){
					if ($_SESSION['admin']!='admin'){						   
						if(strpos($_SESSION['access_rules'],ACTION_NAME) === false){
						      $this->assign("jumpUrl",__URL__);
							  $this->error(L('permission'));
						 
							}
				  }
		  }
		
		}
	}
  protected function _search($name = '') {
		if (empty ( $name )) {
			$name = $this->getActionName();
		}
		$model = D ( $name );
		$map = array ();
		foreach ( $model->getDbFields () as $key => $val ) {
			if (isset ( $_REQUEST [$val] ) && $_REQUEST [$val] != '') {
				$map [$val] = $_REQUEST [$val];
			}
		}
		return $map;
	}
	
	public function index(){
		$name=$this->getActionName();
		$map = $this->_search ($name);
		if (method_exists ( $this, '_filter' )) {
			$this->_filter ( $map );
		} 
		$model = D ($name);
		if (! empty ( $model )) {
			$this->_list ( $model, $map );
		}
		//pass the keyword to index
		$keyword = $this->_request('keyword');
		$this->assign("keyword",$keyword);
		$this->assign('action',$name);
		$this->display ();
		return;
    }
	
	protected function _list($model, $map, $sortBy = '', $asc = false) {
		if (isset ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		}
		if (!$order) {
			$order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();
		}
		if (isset ( $_REQUEST ['_sort'] )) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		
		$count = $model->where ( $map )->count ( 'id' );
		if ($count > 0) {
			import ( "@.ORG.Page" );
			
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = 20;
			}
			$p = new Page ( $count, $listRows );
			
			
			$voList = $model->where($map)->order( "`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select ( );
			
			foreach($map as $key=>$val) {
				if(is_array($val)) {
					if ($val[0] != 'neq') {
						foreach ($val as $t){
							$p->parameter .= "$key=".urlencode($t)."&";
						}
					}
				}else{
					$p->parameter .= "$key=".urlencode($val)."&";
				}
			}
			$p->setConfig('theme',"<ul class='pagination'><li><a>%totalRow% %header% %nowPage%/%totalPage% ҳ</a></li><li>%upPage%</li><li>%first%</li><li>%prePage%</li><li>%linkPage%</li><li>%nextPage%</li><li>%end%</li><li>%downPage%</li></ul>");
			
			$page = $p->show ();
			$sortImg = 'fa-angle-up'; 
			if ($sort == 'desc') {
				$sortImg = 'fa-angle-down';
			}
			$sortAlt = $sort == 'desc' ? L('ascending_sort') : L('descending_sort'); 
			$sort = $sort == 'desc' ? 1 : 0; 
			
		}
		$this->assign ( 'list', $voList );
		$this->assign ( 'sort', $sort );
		$this->assign ( 'order', $order );
		$this->assign ( 'sortImg', $sortImg );
		$this->assign ( 'sortType', $sortAlt );
		$this->assign ( 'numPerPage', $listRows );
		$this->assign ( "page", $page );
		
		$this->assign('firstRow',$p->firstRow+1);
		$this->assign ( 'count', $count );
		$this->assign ( "prePg", $p->prePg );
		$this->assign ( "nextPg", $p->nextPg );
		Cookie::set ( '_currentUrl_', __SELF__ );
		return;
	}
	
	public function add () {
		$model = D ( $this->getActionName());
		if (IS_POST) {
			if(false === $data = $model->create()) {
				$this->error($model->getError());
			}else{
				if ($this->_post('id')) { 
					if($result	 =	 $model->save($data)) {
						$this->success(L('_OPERATION_SUCCESS_'));
					}else{
						$this->error(L('_OPERATION_FAIL_'));
					}
				} else {
					if($result	 =	 $model->add()) {
						$this->success(L('_OPERATION_SUCCESS_'));
					}else{
						$this->error(L('_OPERATION_FAIL_'));
					}
				}
			}
		} else {
			$id = $_REQUEST['id'];
			if ($id) {
				$vo = $model->where('id='.$id)->find();
				$this->assign('vo',$vo);
			}
			$this->display();
		}
	}
	
	public function foreverdelete() {
		$name=$this->getActionName();
		$model = D ($name);
		if (! empty ( $model )) {
			$pk = $model->getPk ();
			$id = $_REQUEST [$pk];
			if (isset ( $id )) {
				$condition = array ($pk => array ('in', explode ( ',', $id ) ) );
				if (false !== $model->where ( $condition )->delete ()) {
					$this->success (L("_OPERATION_SUCCESS_"));
				} else {
					$this->error (L("_OPERATION_FAIL_"));
				}
			} else {
				$this->error ( L("_OPERATION_FAIL_") );
			}
		}
	}
}

 

?>