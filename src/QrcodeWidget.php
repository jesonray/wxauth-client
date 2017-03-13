<?php
/**
 * Created by PhpStorm.
 * User: Ray
 * Date: 2017/3/9
 * Time: 下午5:45
 */

namespace raysoft\WxAuthClient;

use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use raysoft\weui\WeuiAsset;
use yii\base\Widget;

class QrcodeWidget extends Widget
{
    public function run()
    {
        $formAction = ArrayHelper::getValue(Yii::$app->params, 'WXAUTH.client.form_action', ['site/auth', 'type'=>'wxlogin']);
        $modulePath = ArrayHelper::getValue(Yii::$app->params, 'WXAUTH.client.module_path', 'wxlogin');

        $this->registerAssets();
        return $this->render('qrlogin', [
            'qrcodeUrl' => Url::to([$modulePath.'/index/qrcode']),
            'statusUrl' => Url::to([$modulePath.'/index/status']),
            'formAction' => Url::to($formAction),
        ]);
    }

    /**
     * 注册客户端脚本
     */
    protected function registerAssets()
    {
        WeuiAsset::register($this->view);
        WxAuthAsset::register($this->view);
    }
}