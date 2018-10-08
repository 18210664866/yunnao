<?php

namespace Qwadmin\Controller;

header('content-type:text/html;charset=utf-8');

/*
 * 阅读测评模块
 */
class ReadController extends ComController {
    
    //书列表
    public function books() {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $read_books = M('read_books');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $read_books->count();
        $list = $read_books->limit($offset . ',' . $pagesize)->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }
    
    //添加书页面
    public function addBooks() {
        $this->display("books_form");
    }
    
    //编辑书页面
    public function editBooks($id) {
        $id = intval($id);
        $books = M('read_books')->where('id=' . $id)->find();
        if ($books) {
            $this->assign('books', $books);
        } else {
            $this->error('参数错误！');
        }
        $this->display('books_form');
    }
    
    //添加，修改书
    public function updateBooks($id = 0) {
        $id = intval($id);
        $data['img_url'] = isset($_POST['imgurl']) ? $_POST['imgurl'] : false;
        if ($_FILES['content_url']['error'] == 0) {
            $rootPath = './read/';
            $savePath = 'read_books'; // 文件上传的保存路径
            $info = uploadText($rootPath,$savePath);
            if($info){
                $data['content_url'] = $info['content_url']['url'];
            }else{
                $this->error('抱歉，未知错误！');
            }
        }
        $data['word_num'] = isset($_POST['word_num']) ? $_POST['word_num'] : false;
        $data['createtime'] = time();
        if (! $data['img_url'] or ! $data['word_num']) {
            $this->error('警告！书图片,书内容及书字数为必填项目。');
        }
        if ($id) {
            M('read_books')->data($data)->where('id=' . $id)->save();
            addlog('编辑阅读测评书，ID：' . $id);
            $this->success('恭喜！阅读测评书编辑成功！', U('books'));
        } else {
            $id = M('read_books')->data($data)->add();
            if ($id) {
                addlog('新增阅读测评书，ID：' . $id);
                $this->success('恭喜！阅读测评书新增成功！', U('books'));
            } else {
                $this->error('抱歉，未知错误！');
            }
        }
    }
    
    //删除书
    public function delBooks() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('read_books')->where($map)->delete()) {
                addlog('删除阅读测评书，ID：' . $ids);
                $this->success('恭喜，阅读测评书删除成功！', U('books'));
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
    
    //书对应的题列表
    public function ti($id) {
        $id = intval($id);
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $read_ti = M('read_ti');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $read_ti->where("bid=" . $id)->count();
        $list = $read_ti->where("bid=" . $id)->limit($offset . ',' . $pagesize)->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('id', $id);
        $this->display();
    }
    
    //添加题
    public function addTi($id) {
        $bid = intval($id);
        $this->assign("bid",$bid);
        $this->display("ti_form");
    }
    
    //编辑题
    public function editTi($id) {
        $id = intval($id);
        $ti = M('read_ti')->where('id=' . $id)->find();
        if ($ti) {
            $this->assign('bid', $ti['bid']);
            $this->assign('ti', $ti);
        } else {
            $this->error('参数错误！');
        }
        $this->display('ti_form');
    }
    
    //添加，编辑题
    public function updateTi($id = 0) {
        $id = intval($id);
        $data['bid'] = $_POST['bid'];
        $data['question'] = isset($_POST['question']) ? $_POST['question'] : false;
        $data['a'] = $_POST['a_a'];
        $data['b'] = $_POST['b_b'];
        $data['c'] = $_POST['c_c'];
        $data['d'] = $_POST['d_d'];
        $data['right'] = isset($_POST['right']) ? $_POST['right'] : false;
        $data['createtime'] = time();
        if (! $data['question'] or ! $data['right']) {
            $this->error('警告！题干及正确答案为必填项目。');
        }
        if ($id) {
            M('read_ti')->data($data)->where('id=' . $id)->save();
            addlog('编辑阅读测评书题，ID：' . $id);
            $this->success('恭喜！阅读测评书题编辑成功！', U('ti',array('id'=>$data['bid'])));
        } else {
            $id = M('read_ti')->data($data)->add();
            if ($id) {
                addlog('新增阅读测评书题，ID：' . $id);
                $this->success('恭喜！阅读测评书题新增成功！', U('ti',array('id'=>$data['bid'])));
            } else {
                $this->error('抱歉，未知错误！');
            }
        }
    }
    
     //删除书
    public function delTi() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        $bid = $_REQUEST['bid'];
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('read_ti')->where($map)->delete()) {
                addlog('删除阅读测评书题，ID：' . $ids);
                $this->success('恭喜，阅读测评书题删除成功！',  U('ti',array('id'=>$bid)));
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
}
