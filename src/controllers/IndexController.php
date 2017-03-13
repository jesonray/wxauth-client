<?php
/**
 * Created by PhpStorm.
 * User: Ray
 * Date: 2017/3/10
 * Time: 下午1:49
 */

namespace raysoft\WxAuthClient\controllers;

use raysoft\WxAuthClient\WxAuth;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use Arcanedev\QrCode\QrCode;

class IndexController extends Controller
{
    private $sessionKey;

    public function init()
    {
        parent::init();

        $this->sessionKey = ArrayHelper::getValue(Yii::$app->params, 'WXAUTH.client.session_key', 'WXAUTH_DATA');

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function($event){
            $response = $event->sender;
            if( $response->statusCode==200 ) {
                $response->data = [
                    'code' => 200,
                    'data' => $response->data,
                ];
            } else {
                $data = [
                    'name' => $response->data['name'],
                    'code' => $response->data['code'],
                    'message' => $response->data['message']
                ];
                $response->data = [
                    'success' => $response->statusCode,
                    'data' => $data,
                ];
                $response->statusCode=200;
            }
        });
    }

    /**
     * 获取新二维码
     */
    public function actionQrcode()
    {
        // 删除旧的数据
        Yii::$app->session->remove($this->sessionKey);

        // 请求登陆接口
        $data = WxAuth::requstLogin();
        if( $data ) {
            // 创建二维码
            $data['qrcode'] = $this->createQrcode($data['url']);
            return $data;
        }

        throw new Exception('登陆失败, 请刷新页面重试');
    }

    /**
     * 获取登陆状态
     */
    public function actionStatus($token)
    {
        // 请求接口,获取登陆状态
        $data = WxAuth::requstStatus($token);
        if( $data ) {
            Yii::$app->session->set($this->sessionKey, $data);
            return [
                'status' => $data['status'],
                'text' => $data['text']
            ];
        }

        throw new Exception('登陆失败, 请刷新页面重试');
    }

    /**
     * 创建二维码
     * @param $url
     * @return mixed
     */
    private function createQrcode($url)
    {
        $qrCode = new QrCode;
        $qrCode->setText($url);
        $qrCode->setSize(115);
        $qrCode->setPadding(0);
        return $qrCode->getDataUri();
    }
}