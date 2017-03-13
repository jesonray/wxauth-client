# wxauth-client
通过认证的公众号实现网页扫描登陆(客户端)

此为Yii2模块, 需要运行在Yii2框架下

## 使用场景
1. 有认证过的微信公众号
2. 没有开发者账号(正常使用微信扫描登陆要申请开发者账号, 300认证费用, 每个网站都要提交资料, 审核, 很麻烦)
3. WEB端希望通过扫描登陆

## 安装使用
1、通过composer安装: `composer require raysoft/wxauth-client`

2、配置模块:
```
'modules' => [
    ...
    'wxlogin' => [ // 配置模块
        'class' => 'raysoft\WxAuthClient\Module'
    ]
    ...
  ]
```
3、添加配置信息(params-local.php):
```
'WXAUTH' => [
    'client' => [
        'key' => '服务端wxlogin_app表中的key',
        'secret' => '服务端wxlogin_app表中的secret',
        'server' => '服务端域名',
        'server_ip' => '服务端域名对应的Ip(非必填, 但是填了后每次通讯不会再解析域名, 增加速度)',
        'server_scheme' => 'http', // 协议, http or https
        'server_path' => '/wxauth/api', // 服务端API接口的访问路径, "wxauth"为服务端配置模块名, 根据自己配置的情况修改

        'session_key' => 'WXAUTH_DATA', // SESSION名, 登陆成功后, 用户信息会保存在此session名下
        'module_name' => 'wxlogin',     // 上面配置的模块名
        'form_action' => ['site/wxlogin']   // 扫码登陆后跳转到哪里
    ]
],
'WECHAT' => [
    'app_id'  => '微信公众号的app id',
    'secret'  => '微信公招的secret'
]
```
4、在登陆页面试图适合的位置添加以下代码生成二维码:

```<?php echo \raysoft\WxAuthClient\QrcodeWidget::widget();?>```