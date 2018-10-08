

# 1.接口说明

地址：地址/Home/Api/接口名

方式：POST，GET

返回：json数据,格式如下

```
response: 0:失败，1：成功
content：错误信息或数据
{
    "response":1,
    "content":{
        ......
    }
}
```

说明:

- response为0表示失败;response为1表示调用成功
- content调用成功时,返回需要的数据,失败时，返回的是错误信息.参见各接口详细说明。


# 2.接口详细说明


##一.课堂训练相关接口
### （1）获取分类以及第一次进页面需要展示的书

接口名：getClassCategory

URL: 地址/home/api/getClassCategory

方式：POST，GET

请求参数：

返回结果示例：

```
{
    "response":1,
    "content":{
        "category":[
            {
                "id":"4",
                "name":"1000字以下/分钟",
                "createtime":"1537156240"
            },
        ],
        "books":[
            {
                "id":"10",
                "img_url":"/yunnao/Public/attached/2018/09/18/5ba0bab178132.gif"
            },
        ]
    }
}
```

返回结果说明:

|参数名称|说明|
|---|---|
|category|分类数组|
|id|分类ID|
|name|分类名|
|createtime|分类添加时间|
|id|书ID|
|img_url|书图片地址|


### （2）获取分类对应的书

接口名：getClassBooks
        
URL: 地址/home/api/getClassBooks
        
方式：POST，GET
        
请求参数：

参数名称|说明|是否必须
|---|---|---|
|cid|分类ID|是
        
返回结果示例：

```
{
    "response":1,
    "content":[
        {
            "id":"10",
            "img_url":"/yunnao/Public/attached/2018/09/18/5ba0bab178132.gif"
        },
        {
            "id":"11",
            "img_url":"/yunnao/Public/attached/2018/09/18/5ba0bdba7f717.gif"
        }
    ]
}
                
```
返回结果说明

|参数名称|说明|
|---|---|
|id|书ID|
|img_url|书图片地址|

        
        

        
### （3）获取书的内容
接口名： getBooksContent

URL: 地址/home/api/getBooksContent

方式： POST， GET

请求参数：

参数名称|说明|是否必须
|---|---|---|
|id|书ID|是
        
返回结果示例： 
```
{
    "response":1,
    "content":{
        "word_num":"2121",
        "content":"http://pf70k08pi.bkt.clouddn.com/read_books2018-09-18_5ba0bab906cfd.docx"
    }
}
```
返回结果说明

|参数名称|说明|
|---|---|
|word_num|书字数|
|content|书内容|

        
### （4）获取阅读速度
接口名： getClassReadSpeed

URL: 地址/home/api/getClassReadSpeed

方式： POST， GET

请求参数：

返回结果示例： 
        
```
{
    "response":1,
    "content":[
        {
            "id":"1",
            "name":"200字/分"
        }
    ]
}
```
返回结果说明

|参数名称|说明|
|---|---|
|id|ID|
|name|名称|

### （5）获取其他设置
接口名： getClassSetting

URL: 地址/home/api/getClassSetting

方式： POST， GET

请求参数： 

返回结果示例：
```
{
    "response":1,
    "content":[
        {
            "id":"1",
            "name":"Arial(默认)",
            "type":"1"
        },
        {
            "id":"5",
            "name":"文字绿色",
            "type":"2"
        }
    ]
}
```
|参数名称|说明|
|---|---|
|id|ID|
|name|名称|
|type|类型（1：字体 2：文字颜色）|

##二.阅读测评相关接口
### （1）获取所有书图片
接口名： getReadBooks

URL: 地址/home/api/getReadBooks

方式： POST， GET

请求参数： 无

返回结果示例：
```
{
    "response":1,
    "content":[
        {
            "id":"4",
            "img_url":"/yunnao/Public/attached/2018/09/19/5ba1c57756d2f.png"
        },
        {
            "id":"3",
            "img_url":"/yunnao/Public/attached/2018/09/18/5ba0c1639ef12.png"
        }
    ]
}
```
|参数名称|说明|
|---|---|
|id|ID|
|img_url|书图片|

### （2）获取对应的书内容及题
接口名： getReadTi

URL: 地址/home/api/getReadTi

方式： POST， GET

请求参数：

参数名称|说明|是否必须
|---|---|---|
|id|书ID|是

返回结果示例：
```
{
    "response":1,
    "content":{
        "book":{
            "id":"4",
            "word_num":"5455",
            "content":"http://pf70k08pi.bkt.clouddn.com/read_books2018-09-19_5ba1c5cf7188c.docx"
        },
        "ti":[
            {
                "id":"10",
                "bid":"4",
                "question":"问题四",
                "a":"1个",
                "b":"2个",
                "c":"3个",
                "d":"4个",
                "right":"d",
                "createtime":"1537330287"
            },
            {
                "id":"9",
                "bid":"4",
                "question":"问题三",
                "a":"1个",
                "b":"2个",
                "c":"3个",
                "d":"4个",
                "right":"c",
                "createtime":"1537330270"
            }
        ]
    }
}
```
|参数名称|说明|
|---|---|
|book|书详情数组|
|word_num|书字数|
|content|书内容|
|ti|书对应的题数组|
|id|题ID|
|bid|书ID|
|question|题干|
|a|选项A|
|b|选项B|
|c|选项C|
|d|选项D|
|right|正确答案|
|createtime|添加时间|

##三.视幅扩展训练相关接口
### （1）获取所有分类
接口名： getExtendCategory

URL: 地址/home/api/getExtendCategory

方式： POST， GET

请求参数：无

返回结果示例： 
```angularjs
{
    "response":1,
    "content":[
        {
            "id":"1",
            "name":"视点移动训练",
            "pid":"0"
        },
        {
            "id":"2",
            "name":"横向“之”字形运动",
            "pid":"1"
        },
        {
            "id":"4",
            "name":"视幅扩展训练",
            "pid":"0"
        },
        {
            "id":"5",
            "name":"请跟我走",
            "pid":"4"
        }
    ]
}
```
|参数名称|说明|
|---|---|
|id|ID|
|name|名称|
|pid|上一级的ID，根为0|

### （2）获取参数设置
接口名： getExtendSetting

URL: 地址/home/api/getExtendSetting

方式： POST

请求参数：

返回结果示例： 

```angularjs
{
    "response":1,
    "content":[
        {
            "id":"1",
            "name":"1/1秒 （慢读）",
            "type":"1"
        }
    ]
}
```
|参数名称|说明|
|---|---|
|id|ID|
|name|名称|
|type|分类（1：速度 2：时长）|

### （3）获取长度设置
接口名： getExtendLength

URL: 地址/home/api/getExtendLength

方式： POST,GET

请求参数：

返回结果示例： 
```
{
    "response":1,
    "content":[
        {
            "id":"1",
            "name":"4位/2个单词"
        }
    ]
}
```
|参数名称|说明|
|---|---|
|id|ID|
|name|名称|

### （4）获取图片资源
接口名： getExtendImg

URL: 地址/home/api/getExtendImg

方式： POST,GET

请求参数：

返回结果示例： 
```
{
    "response":1,
    "content":[
        {
            "id":"1",
            "img_url":"/yunnao/Public/attached/2018/09/20/5ba34eec1109d.gif"
        }
    ]
}
```
|参数名称|说明|
|---|---|
|id|ID|
|img_url|图片|

### （5）获取文字资源
接口名： getExtendText

URL: 地址/home/api/getExtendText

方式： POST,GET

请求参数：

参数名称|说明|是否必须
|---|---|---|
|lid|长度设置ID|是
|type|类型（1：中文，2：英文）|是


返回结果示例： 
```
{
    "response":1,
    "content":[
        {
            "id":"1",
            "text":"zzzzaaa"
        }
    ]
}
```
|参数名称|说明|
|---|---|
|id|ID|
|text|内容|

### （6）获取短文资源
接口名： getExtendEssay

URL: 地址/home/api/getExtendEssay

方式： POST,GET

请求参数：

返回结果示例： 
```
{
    "response":1,
    "content":{
        "id":"1",
        "essay_url":"http://pf70k08pi.bkt.clouddn.com/extend_essay2018-09-20_5ba3574593057.docx"
    }
}
```
|参数名称|说明|
|---|---|
|id|ID|
|essay_url|短文内容|
