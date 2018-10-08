<?php

namespace Home\Controller;
header('content-type:text/html;charset=utf-8');
use Vendor\Page;

class ApiController extends ComController {

    public function _initialize() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');
    }

    /*
     * 课堂训练相关接口
     */

    //获取分类以及第一次进页面需要展示的书
    public function getClassCategory() {
        $back = D("Class")->getClassCategoryInfo();
        echo json_encode($back);
        exit();
    }

    /*
     * 获取分类对应的书
     * $param   string  $cid   分类ID
     */

    public function getClassBooks() {
        $back = D("Class")->getClassBooksInfo();
        echo json_encode($back);
        exit();
    }

    /*
     * 获取书的内容
     * $param   string  $id   书ID
     */
    public function getBooksContent() {
        $back = D("Class")->getBooksContentInfo();
        echo json_encode($back);
        exit();
    }
    
    //获取阅读速度
    public function getClassReadSpeed() {
        $back = D("Class")->getClassReadSpeedInfo();
        echo json_encode($back);
        exit();
    }
    
    //获取其他设置
    public function getClassSetting() {
        $back = D("Class")->getClassSettingInfo();
        echo json_encode($back);
        exit();
    }
    
    /*
     * 阅读测评
     */
    //获取所有书图片
    public function getReadBooks() {
        $back = D("Read")->getReadBooksInfo();
        echo json_encode($back);
        exit();
    }

    /*
     * 获取对应的书及题
     * @param   string    $id   书ID
     */
    public function getReadTi() {
        $back = D("Read")->getReadTiInfo();
        echo json_encode($back);
        exit();
    }
    
    /*
     * 视幅扩展训练
     */
    //获取所有分类
    public function getExtendCategory() {
        $back = D("Extend")->getExtendCategoryInfo();
        echo json_encode($back);
        exit();
    }
    
    //获取参数设置
    public function getExtendSetting() {
        $back = D("Extend")->getExtendSettingInfo();
        echo json_encode($back);
        exit();
    }
    
    //获取长度设置
    public function getExtendLength() {
        $back = D("Extend")->getExtendLengthInfo();
        echo json_encode($back);
        exit();
    }
    
    //获取图片资源
    public function getExtendImg() {
        $back = D("Extend")->getExtendImgInfo();
        echo json_encode($back);
        exit();
    }

    /*
     * 获取文字资源
     * @param   string    $lid   长度设置ID
     * @param   string    $type  类型（1：中文，2：英文）
     */
    public function getExtendText() {
        $back = D("Extend")->getExtendTextInfo();
        echo json_encode($back);
        exit();
    }
    
    //获取短文资源
    public function getExtendEssay() {
        $back = D("Extend")->getExtendEssayInfo();
        echo json_encode($back);
        exit();
    }
    

}
