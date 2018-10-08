<?php

namespace Qwadmin\Controller;

header('content-type:text/html;charset=utf-8');

use Vendor\Tree;
use Think\Upload;

class ExtendController extends ComController {
    /*
     * 视幅扩展训练-分类管理
     */

    //视幅扩展训练分类
    public function category() {
        $category = M('extend_category')->field('id,pid,name,o')->order('o asc')->select();
        $category = $this->getMenu($category);
        $this->assign('category', $category);
        $this->display();
    }

    //添加页面
    public function addCategory() {
        $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
        $category = M('extend_category')->field('id,pid,name')->order('o asc')->select();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $pid);
        $this->assign('category', $category);
        $this->display('category_form');
    }

    //修改页面
    public function editCategory() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : false;
        $currentcategory = M('extend_category')->where('id=' . $id)->find();
        $this->assign('currentcategory', $currentcategory);
        $category = M('extend_category')->field('id,pid,name')->where("id <> {$id}")->order('o asc')->select();
        $tree = new Tree($category);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $category = $tree->get_tree(0, $str, $currentcategory['pid']);
        $this->assign('category', $category);
        $this->display('category_form');
    }

    //删除分类
    public function delCategory() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : false;
        if ($id) {
            $data['id'] = $id;
            $category = M('extend_category');
            if ($category->where('pid=' . $id)->count()) {
                die('2'); //存在子类，严禁删除。
            } else {
                $category->where('id=' . $id)->delete();
                addlog('删除分类，ID：' . $id);
            }
            die('1');
        } else {
            die('0');
        }
    }

    //添加，修改分类
    public function updateCategory($act = null) {
        if ($act == 'order') {
            $id = I('post.id', 0, 'intval');
            if (!$id) {
                die('0');
            }
            $o = I('post.o', 0, 'intval');
            M('extend_category')->data(array('o' => $o))->where("id=" . $id)->save();
            addlog('视幅扩展训练分类修改排序，ID：' . $id);
            die('1');
        }

        $id = I('post.id', false, 'intval');
        $data['pid'] = I('post.pid', 0, 'intval');
        $data['name'] = I('post.name');
        $data['o'] = I('post.o', 0, 'intval');
        if ($data['name'] == '') {
            $this->error('分类名称不能为空！');
        }
        if ($id) {
            if (M('extend_category')->data($data)->where('id=' . $id)->save()) {
                addlog('视幅扩展训练分类修改，ID：' . $id . '，名称：' . $data['name']);
                $this->success('恭喜，分类修改成功！', 'category');
                die(0);
            }
        } else {
            $dats['createtime'] = time();
            $id = M('extend_category')->data($data)->add();
            if ($id) {
                addlog('视幅扩展训练新增分类，ID：' . $id . '，名称：' . $data['name']);
                $this->success('恭喜，新增分类成功！', 'category');
                die(0);
            }
        }
        $this->success('恭喜，操作成功！');
    }

    /*
     * 参数设置管理
     */

    public function setting() {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $class_setting = M('extend_setting');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $class_setting->count();
        $list = $class_setting->order("type asc")
                ->limit($offset . ',' . $pagesize)
                ->select();
        foreach ($list as $k => $v) {
            if ($v['type'] == 1) {
                $list[$k]['type'] = "速度";
            } else {
                $list[$k]['type'] = "时长";
            }
        }
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    //添加参数设置
    public function addSetting() {
        $this->display("setting_form");
    }

    //编辑参数设置
    public function editSetting($id = null) {
        $res = M('extend_setting')->where("id=" . $id)->find();
        if (!$res) {
            $this->error('参数错误！');
        }
        $this->assign('res', $res);
        $this->display('setting_form');
    }

    //添加，修改参数设置
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
            M('extend_setting')->data($data)->where('id=' . $id)->save();
            addlog('修改视幅扩展训练设置，ID：' . $id);
        } else {
            $add_id = M('extend_setting')->data($data)->add();
            addlog('新增视幅扩展训练设置，ID：' . $add_id);
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
            if (M('extend_setting')->where($map)->delete()) {
                addlog('删除视幅扩展训练设置，ID：' . $ids);
                $this->success('恭喜，视幅扩展训练设置删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    /*
     * 设定长度
     */

    //设定长度
    public function length() {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $class_read_speed = M('extend_length');
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
    public function addLength() {

        $this->display('length_form');
    }

    //修改页面
    public function editLength($id = null) {
        $res = M('extend_length')->where("id=" . $id)->find();
        if (!$res) {
            $this->error('参数错误！');
        }
        $this->assign('res', $res);
        $this->display('length_form');
    }

    //删除分类
    public function delLength() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('extend_length')->where($map)->delete()) {
                addlog('删除视幅扩展训练长度，ID：' . $ids);
                $this->success('恭喜，视幅扩展训练长度删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    //添加，修改分类
    public function updateLength($id = 0) {
        $id = intval($id);
        $data['name'] = I('post.name', '', 'strip_tags');
        if (!$data['name']) {
            $this->error('请填写视幅扩展训练长度名称！');
        }
        $data['createtime'] = time();
        if ($id) {
            M('extend_length')->data($data)->where('id=' . $id)->save();
            addlog('修改视幅扩展训练长度，ID：' . $id);
        } else {
            $add_id = M('extend_length')->data($data)->add();
            addlog('新增视幅扩展训练长度，ID：' . $add_id);
        }
        $this->success('恭喜，操作成功！', U('length'));
    }

    /*
     * 图片资源
     */

    //图片资源列表
    public function img() {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $img_resource = M('extend_img_resource');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $img_resource->count();
        $list = $img_resource->limit($offset . ',' . $pagesize)->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    //添加图片资源
    public function addImg() {
        $this->display("img_form");
    }

    //编辑图片资源
    public function editImg($id = null) {
        $list = M('extend_img_resource')->where("id=" . $id)->find();
        $this->assign('list', $list);
        $this->display("img_form");
    }

    //删除图片资源
    public function delImg() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('extend_img_resource')->where($map)->delete()) {
                addlog('删除视幅扩展训练图片资源，ID：' . $ids);
                $this->success('恭喜，视幅扩展训练图片资源删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }

    //添加，编辑图片资源
    public function updateImg($id = 0) {
        $id = intval($id);
        $data['img_url'] = isset($_POST['imgurl']) ? $_POST['imgurl'] : false;
        $data['createtime'] = time();
        if ($id) {
            M('extend_img_resource')->data($data)->where('id=' . $id)->save();
            addlog('修改视幅扩展训练图片资源，ID：' . $id);
        } else {
            $add_id = M('extend_img_resource')->data($data)->add();
            addlog('新增视幅扩展训练图片资源，ID：' . $add_id);
        }
        $this->success('恭喜，操作成功！', U('img'));
    }

    /*
     * 短文资源
     */

    //短文资源列表
    public function essay() {
        $p = isset($_GET['p']) ? intval($_GET['p']) : '1';
        $essay_resource = M('extend_essay_resource');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量
        $count = $essay_resource->count();
        $list = $essay_resource->limit($offset . ',' . $pagesize)->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }

    //添加短文资源
    public function addEssay() {
        $this->display("essay_form");
    }

    //编辑图片资源
    public function editEssay($id = null) {
        $list = M('extend_essay_resource')->where("id=" . $id)->find();
        $this->assign('list', $list);
        $this->display("essay_form");
    }

    //添加，修改短文资源
    public function updateEssay($id = 0) {
        $id = intval($id);
        if ($_FILES['essay_url']['error'] == 0) {
            $rootPath = './extend/';
            $savePath = 'extend_essay'; // 文件上传的保存路径
            $info = uploadText($rootPath, $savePath);
            if ($info) {
                $data['essay_url'] = $info['essay_url']['url'];
            } else {
                $this->error('抱歉，未知错误！');
            }
        }
        $data['createtime'] = time();
        if ($id) {
            M('extend_essay_resource')->data($data)->where('id=' . $id)->save();
            addlog('编辑视幅扩展训练短文资源，ID：' . $id);
            $this->success('恭喜！视幅扩展训练短文资源编辑成功！', U('essay'));
        } else {
            $id = M('extend_essay_resource')->data($data)->add();
            if ($id) {
                addlog('新增视幅扩展训练短文资源，ID：' . $id);
                $this->success('恭喜！视幅扩展训练短文资源新增成功！', U('essay'));
            } else {
                $this->error('抱歉，未知错误！');
            }
        }
    }
    
    //删除短文资源
    public function delEssay() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('extend_essay_resource')->where($map)->delete()) {
                addlog('删除视幅扩展训练短文资源，ID：' . $ids);
                $this->success('恭喜，视幅扩展训练短文资源删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
    
    /*
     * 文字资源
     */
    //文字资源
    public function text($lid = 0, $p = 1) {
        $p = intval($p) > 0 ? $p : 1;
        $text_resource = M('extend_text_resource');
        $pagesize = 10; #每页数量
        $offset = $pagesize * ($p - 1); //计算记录偏移量

        $prefix = C('DB_PREFIX');
        $lid = isset($_GET['lid']) ? $_GET['lid'] : '';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $where = '1 = 1 ';
        if ($lid) {
            $where .= "and {$prefix}extend_text_resource.lid in ($lid) ";
        }
        //默认按照时间降序
        $orderby = "lid desc";
        if ($order == "asc") {

            $orderby = "lid asc";
        }

        //获取栏目分类
        $length = M('extend_length')->field('id,name')->select();
        $tree = new Tree($length);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $length = $tree->get_tree(0, $str, $lid);
        $this->assign('length', $length); //导航
        $count = $text_resource->where($where)->count();
        $list = $text_resource->field("{$prefix}extend_text_resource.*,{$prefix}extend_length.name")->where($where)->order($orderby)->join("{$prefix}extend_length ON {$prefix}extend_length.id = {$prefix}extend_text_resource.lid")->limit($offset . ',' . $pagesize)->select();
        $page = new \Think\Page($count, $pagesize);
        $page = $page->show();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->display();
    }
    
    //添加文字资源页面
    public function addText() {
        $length = M('extend_length')->field('id,name')->select();
        $tree = new Tree($length);
        $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
        $length = $tree->get_tree(0, $str, 0);
        $this->assign('length', $length); //导航
        $this->display('text_form');
    }
    
    //编辑文字资源页面
    public function editText($id) {
        $id = intval($id);
        $text = M('extend_text_resource')->where('id=' . $id)->find();
        if ($text) {
            $length = M('extend_length')->field('id,name')->select();
            $tree = new Tree($length);
            $str = "<option value=\$id \$selected>\$spacer\$name</option>"; //生成的形式
            $length = $tree->get_tree(0, $str, $text['lid']);
            $this->assign('length', $length); //导航
            $this->assign('text', $text);
        } else {
            $this->error('参数错误！');
        }
        $this->display('text_form');
    }
    
    //删除文字资源
    public function delText() {
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('extend_text_resource')->where($map)->delete()) {
                addlog('删除视幅扩展训练文字资源，ID：' . $ids);
                $this->success('恭喜，视幅扩展训练文字资源删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
    
    //添加，修改文字资源
    public function updateText($id = 0) {
        $id = intval($id);
        $data['lid'] = isset($_POST['lid']) ? intval($_POST['lid']) : 0;
        $data['type'] = isset($_POST['type']) ? $_POST['type'] : false;
        $data['text'] = isset($_POST['text']) ? $_POST['text'] : false;
        $data['createtime'] = time();
        if (!$data['lid'] or ! $data['type'] or ! $data['text']) {
            $this->error('警告！文字资源分类、文字资源类型及文字资源文字为必填项目。');
        }
        if ($id) {
            M('extend_text_resource')->data($data)->where('id=' . $id)->save();
            addlog('编辑视幅扩展训练文字资源，ID：' . $id);
            $this->success('恭喜！视幅扩展训练文字资源编辑成功！', U('text'));
        } else {
            $id = M('extend_text_resource')->data($data)->add();
            if ($id) {
                addlog('新增视幅扩展训练文字资源，ID：' . $id);
                $this->success('恭喜！视幅扩展训练文字资源新增成功！', U('text'));
            } else {
                $this->error('抱歉，未知错误！');
            }
        }
    }

}
