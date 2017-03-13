<?php
use yii\helpers\Url;
?>
<form action="<?php echo $formAction?>" class="qrcode-login" id="QRCodeLogin" data-src="<?php echo $statusUrl;?>">
    <input type="hidden" name="token"/>
    <div class="qrcode-mod">
        <div class="qrcode-desc" style="font-weight: bold;font-size: 14px;">扫码登录</div>
        <div class="qrcode-main">
            <div class="qrcode-img" id="QRCodeImg" style="opacity: 1;">
                <div style="position:relative;height:100%;text-align: center">
                    <div class="qrcode-loading" style="position:relative;top:50%;transform:translateY(-50%);"><i class="weui-loading"></i></div>
                    <img src="" data-src="<?php echo $qrcodeUrl;?>" alt="" style="display:none;"/>
                </div>
            </div>
        </div>
        <div class="qrcode-desc">
            <p>微信扫一扫登录</p>
        </div>
    </div>
    <div class="qrcode-msg">
        <div class="msg-err">
            <i class="weui-icon-warn"></i>
            <h6 class="error-expired" style="display:none;">二维码已过期</h6>
            <h6 class="error-canceled">登录失败</h6>
            <p>请刷新二维码后重新扫码</p>
            <p><a href="javascript:;" class="fm-button refresh QRCodeRefresh">刷新二维码</a>
            </p>
        </div>
        <div class="msg-ok">
            <i class="weui-icon-success"></i>
            <h6>扫描成功！</h6>
            <p>请在手机上根据提示确认登录</p>
        </div>
    </div>
</form>