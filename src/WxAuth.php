<?php
/**
 * Created by PhpStorm.
 * User: Ray
 * Date: 2017/3/9
 * Time: 下午4:09
 */

namespace raysoft\WxAuthClient;

use Yii;
use yii\helpers\ArrayHelper;

class WxAuth
{
    /**
     * 登陆接口
     * @return bool
     */
    public static function requstLogin()
    {
        $response = self::query('login',['action'=>'login']);
        if( $response['code']==200 ) {
            return $response['data'];
        }
        return false;
    }

    /**
     * 登陆登陆状态
     * @return bool
     */
    public static function requstStatus($token)
    {
        $response = self::query('status', ['task'=>$token]);
        if( $response['code']==200 ) {
            return $response['data'];
        }
        return false;
    }

    /**
     * 访问接口
     * @param string $api
     * @param array $params
     * @return mixed
     */
    private static function query($api, $params=[])
    {
        $appkey = ArrayHelper::getValue(Yii::$app->params, 'WXAUTH.client.key');
        $appSecret = ArrayHelper::getValue(Yii::$app->params, 'WXAUTH.client.secret');

        $Server = ArrayHelper::getValue(Yii::$app->params, 'WXAUTH.client.server');
        $ServerIp = ArrayHelper::getValue(Yii::$app->params, 'WXAUTH.client.server_ip');
        $ServerScheme = ArrayHelper::getValue(Yii::$app->params, 'WXAUTH.client.server_scheme', 'http');
        $ServerPath = ArrayHelper::getValue(Yii::$app->params, 'WXAUTH.client.server_path');

        $params['key'] = $appkey;
        $params['timestamp'] = time();
        $params['token'] = self::sign($params, $appSecret);

        $url = $ServerScheme.'://'.($ServerIp ? $ServerIp : $Server).'/'.trim($ServerPath, '/').'/'.$api;
        $url .= '?'.http_build_query($params);

        $options = [];
        if( $ServerIp ) {
            $options['headers'] = ['Host'=>$Server];
        }

        $http = new \GuzzleHttp\Client();
        $res = $http->request('GET', $url, $options);
        return json_decode($res->getBody(), 1);
    }

    /**
     * @param string $params
     * @param string $secret
     * @return mixed
     */
    public static function sign($params, $secret = '')
    {
        return md5(strtoupper(md5(static::assemble($params))) . $secret);
    }

    /**
     * @param $params
     * @return null|string
     */
    private static function assemble($params)
    {
        if( !is_array($params) ) {
            return null;
        }

        ksort($params, SORT_STRING);
        $sign = '';
        foreach ($params AS $key => $val) {
            $sign .= $key . (is_array($val) ? self::assemble($val) : $val);
        }

        return $sign;
    }
}