<?php

/**
 *
 * 版权所有：素材火<qwadmin.sucaihuo.com>
 * 作    者：素材水<hanchuan@sucaihuo.com>
 * 日    期：2015-09-15
 * 版    本：1.0.0
 * 功能说明：配置文件。
 *
 * */
return array(
    //网站配置信息
    'URL' => 'http://' . $_SERVER['HTTP_HOST'], //网站根URL
    'COOKIE_SALT' => 'dayu', //设置cookie加密密钥
    //备份配置
    'DB_PATH_NAME' => 'db', //备份目录名称,主要是为了创建备份目录
    'DB_PATH' => './db/', //数据库备份路径必须以 / 结尾；
    'DB_PART' => '20971520', //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
    'DB_COMPRESS' => '1', //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
    'DB_LEVEL' => '9', //压缩级别   1:普通   4:一般   9:最高
    //扩展配置文件
    'LOAD_EXT_CONFIG' => 'db',
    
    
    //七牛云配置
    'UPLOAD_FILE_QINIU' => array(
        'maxSize' => 5 * 1024 * 1024, //文件大小
//        'rootPath' => './class/',
//        'savePath' => 'books', // 文件上传的保存路径
        'saveName' => array('uniqid', ''),
        'exts' => ['zip', 'rar', 'txt', 'doc', 'docx', 'xlsx', 'xls', 'pptx', 'pdf', 'chf'], // 设置附件上传类型
        'driver' => 'Qiniu', //七牛驱动
        'driverConfig' => array(
            'secrectKey' => 'ZNC2awRg8QsPyKPv4zabgotJcUzjbF75nAMrGDo1',
            'accessKey' => 'wuNxU9z8dc-Ye9jklJcVFp-_0S1C_9vZXhdZHcmD',
            'domain' => 'pf70k08pi.bkt.clouddn.com',
            'bucket' => 'books',
        )
    ),
);
