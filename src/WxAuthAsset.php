<?php
/**
 * Created by PhpStorm.
 * User: Ray
 * Date: 2017/3/10
 * Time: 上午10:09
 */

namespace raysoft\WxAuthClient;


use yii\web\AssetBundle;

class WxAuthAsset extends AssetBundle
{
    public $js = [
        'wxauth.js'
    ];

    public $css = [
        'wxauth.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
    }
}