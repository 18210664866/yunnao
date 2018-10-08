<?php

namespace Qwadmin\Controller;

header('content-type:text/html;charset=utf-8');

use Vendor\Tree;
use Think\Upload;

class ClassController extends ComController {
    /*
     * 课堂训练-分类管理
     */

    //课堂训练分类
    public function category() {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $class_category = M('class_category');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $class_category->field("id,name,createtime")->count();
        $list = $class_category->field("id,name,createtime")
                ->limit($offset . ',' . $pagesize)
                ->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    //添加页面
    public function addCategory() {

        $this->display('category_form');
    }

    //修改页面
    public function editCategory($id = null) {
        $res = M('class_category')->where("id=" . $id)->find();
        if (!$res) {
            $this->error('参数错误！');
        }
        $this->assign('res', $res);
        $this->display('category_form');
    }

    //删除分类
    public function delCategory() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('class_category')->where($map)->delete()) {
                addlog('删除分类，ID：' . $ids);
                $this->success('恭喜，分类删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    //添加，修改分类
    public function updateCategory($id = 0) {
        $id = intval($id);
        $data['name'] = I('post.name', '', 'strip_tags');
        if (!$data['name']) {
            $this->error('请填写分类名称！');
        }
        $data['createtime'] = time();
        if ($id) {
            M('class_category')->data($data)->where('id=' . $id)->save();
            addlog('修改课堂训练分类，ID：' . $id);
        } else {
            $add_id = M('class_category')->data($data)->add();
            addlog('新增课堂训练分类，ID：' . $add_id);
        }
        $this->success('恭喜，操作成功！', U('category'));
    }

    /*
     * 课堂训练-书管理
     */

    //书列表
    public function books($cid = 0, $p = 1) {
        $p = intval($p) > 0 ? $p : 1;
        $books = M('class_books');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量

        $prefix = C('DB_PREFIX');
        $cid = isset($_GET['cid']) ? $_GET['cid'] : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '1 = 1 ';
        if ($cid) {
            $where .= "and {$prefix}class_books.cid in ($cid) ";
        }
        //默认按照时间降序
        $orderby = "cid desc";
        if ($order == "asc") {

            $orderby = "cid asc";
        }

        //获取栏目分类
        $category = M('class_category')->field('id,name')->select();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $cid);
        $this->assign('category', $category); //导航
        $count = $books->where($where)->count();
        $list = $books->field("{$prefix}class_books.*,{$prefix}class_category.name")->where($where)->order($orderby)->join("{$prefix}class_category ON {$prefix}class_category.id = {$prefix}class_books.cid")->limit($offset . ',' . $pagesize)->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    //添加书页面
    public function addBooks() {
        $category = M('class_category')->field('id,name')->select();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, 0);
        $this->assign('category', $category); //导航
        $this->display('books_form');
    }

    //编辑书页面
    public function editBooks($id) {
        $id = intval($id);
        $books = M('class_books')->where('id=' . $id)->find();
        if ($books) {
            $category = M('class_category')->field('id,name')->select();
            $tree = new Tree($category);
            $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
            $category = $tree->get_tree(0, $str, $books['cid']);
            $this->assign('category', $category); //导航
            $this->assign('books', $books);
        } else {
            $this->error('参数错误！');
        }
        $this->display('books_form');
    }

    //添加，修改书
    public function updateBooks($id = 0) {
        $id = intval($id);
        $data['cid'] = isset($_POST['cid']) ? intval($_POST['cid']) : 0;
        $data['img_url'] = isset($_POST['imgurl']) ? $_POST['imgurl'] : false;
        if ($_FILES['content_url']['error'] == 0) {
            $rootPath = './class/';
            $savePath = 'class_books'; // 文件上传的保存路径
            $info = uploadText($rootPath,$savePath);
            if($info){
                $data['content_url'] = $info['content_url']['url'];
            }else{
                $this->error('抱歉，未知错误！');
            }
        }
        $data['word_num'] = isset($_POST['word_num']) ? $_POST['word_num'] : false;
        $data['createtime'] = time();
        if (!$data['cid'] or ! $data['img_url'] or ! $data['word_num']) {
            $this->error('警告！书分类、书图片及书字数为必填项目。');
        }
        if ($id) {
            M('class_books')->data($data)->where('id=' . $id)->save();
            addlog('编辑课堂训练书，ID：' . $id);
            $this->success('恭喜！课堂训练书编辑成功！', U('books'));
        } else {
            $id = M('class_books')->data($data)->add();
            if ($id) {
                addlog('新增课堂训练书，ID：' . $id);
                $this->success('恭喜！课堂训练书新增成功！', U('books'));
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
            if (M('class_books')->where($map)->delete()) {
                addlog('删除课堂训练书，ID：' . $ids);
                $this->success('恭喜，课堂训练书删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    /*
     * 文字设置管理
     */

    public function setting() {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $class_setting = M('class_setting');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $class_setting->count();
        $list = $class_setting->order("type asc")
                ->limit($offset . ',' . $pagesize)
                ->select();
        foreach ($list as $k => $v) {
            if ($v['type'] == 1) {
                $list[$k]['type'] = "字体";
            } else {
                $list[$k]['type'] = "文字颜色";
            }
        }
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    //添加文字设置
    public function addSetting() {
        $this->display("setting_form");
    }

    //编辑文字设置
    public function editSetting($id = null) {
        $res = M('class_setting')->where("id=" . $id)->find();
        if (!$res) {
            $this->error('参数错误！');
        }
        $this->assign('res', $res);
        $this->display('setting_form');
    }

    //添加，修改文字设置
    public function updateSetting($id = 0) {
        $id = intval($id);
        $data['name'] = I('post.name', '', 'strip_tags');
        $data['type'] = I('post.type', '', 'strip_tags');
        if (!$data['name']) {
            $this->error('请填写设置名称！');
        }
        if (!$data['type']) {
            $this->error('请选择设置类型！');
        }
        $data['createtime'] = time();
        if ($id) {
            M('class_setting')->data($data)->where('id=' . $id)->save();
            addlog('修改课堂训练文字设置，ID：' . $id);
        } else {
            $add_id = M('class_setting')->data($data)->add();
            addlog('新增课堂训练文字设置，ID：' . $add_id);
        }
        $this->success('恭喜，操作成功！', U('setting'));
    }

    //删除设置
    public function delSetting() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('class_setting')->where($map)->delete()) {
                addlog('删除课堂训练文字设置，ID：' . $ids);
                $this->success('恭喜，课堂训练文字设置删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
    
    /*
     * 阅读速度
     */
    //阅读速度
    public function speed() {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $class_read_speed = M('class_read_speed');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $class_read_speed->field("id,name,createtime")->count();
        $list = $class_read_speed->field("id,name,createtime")
                ->limit($offset . ',' . $pagesize)
                ->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    //添加页面
    public function addSpeed() {

        $this->display('speed_form');
    }

    //修改页面
    public function editSpeed($id = null) {
        $res = M('class_read_speed')->where("id=" . $id)->find();
        if (!$res) {
            $this->error('参数错误！');
        }
        $this->assign('res', $res);
        $this->display('speed_form');
    }

    //删除分类
    public function delSpeed() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('class_read_speed')->where($map)->delete()) {
                addlog('删除阅读速度设置，ID：' . $ids);
                $this->success('恭喜，阅读速度设置删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    //添加，修改分类
    public function updateSpeed($id = 0) {
        $id = intval($id);
        $data['name'] = I('post.name', '', 'strip_tags');
        if (!$data['name']) {
            $this->error('请填写阅读速度设置名称！');
        }
        $data['createtime'] = time();
        if ($id) {
            M('class_read_speed')->data($data)->where('id=' . $id)->save();
            addlog('修改课堂训练阅读速度设置，ID：' . $id);
        } else {
            $add_id = M('class_read_speed')->data($data)->add();
            addlog('新增课堂训练阅读速度设置，ID：' . $add_id);
        }
        $this->success('恭喜，操作成功！', U('speed'));
    }

}
