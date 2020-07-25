<?php
// +----------------------------------------------------------------------
// | 短信配置
// +----------------------------------------------------------------------

return [
    //默认支付模式
    'default' => 'tencent',
    //单个手机每日发送上限
    'maxPhoneCount' => 100,
    //验证码每分钟发送上线
    'maxMinuteCount' => 200,
    //单个IP每日发送上限
    'maxIpCount' => 500,
    //驱动模式
    'stores' => [
        //云信
        'yunxin' => [
            //短信模板id
            'template_id' => [
                //验证码
                'VERIFICATION_CODE' => 518076,
                //支付成功
                'PAY_SUCCESS_CODE' => 520268,
                //发货提醒
                'DELIVER_GOODS_CODE' => 520269,
                //确认收货提醒
                'TAKE_DELIVERY_CODE' => 520271,
                //管理员下单提醒
                'ADMIN_PLACE_ORDER_CODE' => 520272,
                //管理员退货提醒
                'ADMIN_RETURN_GOODS_CODE' => 520274,
                //管理员支付成功提醒
                'ADMIN_PAY_SUCCESS_CODE' => 520273,
                //管理员确认收货
                'ADMIN_TAKE_DELIVERY_CODE' => 520422,
                //改价提醒
                'PRICE_REVISION_CODE' => 528288,
                //订单未支付
                'ORDER_PAY_FALSE' => 528116,
            ],
        ],
        //阿里云
        'aliyun' => [
            'template_id' => [

            ]
        ],
        //腾讯云
        'tencent' => [
            'secret_id'=>'',
            'secret_key'=>'',
            'endpoint'=>'sms.tencentcloudapi.com',//发送服务器
            'region'=>'',   //发送地区 可为空
            'sms_sdk_appid'=>'',    //应用ID https://console.cloud.tencent.com/smsv2/app-manage
            'sign'=>'', //签名  https://console.cloud.tencent.com/smsv2/csms-sign
            'template_id' => [  //模板ID https://console.cloud.tencent.com/smsv2/csms-template
                //验证码
                'VERIFICATION_CODE' => ,
                //支付成功
                'PAY_SUCCESS_CODE' => ,
                //发货提醒
                'DELIVER_GOODS_CODE' => ,
                //确认收货提醒
                'TAKE_DELIVERY_CODE' => ,
                //管理员下单提醒
                'ADMIN_PLACE_ORDER_CODE' => ,
                //管理员退货提醒
                'ADMIN_RETURN_GOODS_CODE' => ,
                //管理员支付成功提醒
                'ADMIN_PAY_SUCCESS_CODE' => ,
                //管理员确认收货
                'ADMIN_TAKE_DELIVERY_CODE' => ,
                //改价提醒
                'PRICE_REVISION_CODE' => ,
                //订单未支付
                'ORDER_PAY_FALSE' => ,
            ]
        ]
    ]
];