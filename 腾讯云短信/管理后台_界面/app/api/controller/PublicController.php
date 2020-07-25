<?php

namespace app\api\controller;

use app\admin\model\sms\SmsRecord;
use app\admin\model\system\SystemAttachment;
use app\models\store\StoreCategory;
use app\models\store\StoreCouponIssue;
use app\models\store\StorePink;
use app\models\store\StoreProduct;
use app\models\store\StoreService;
use app\models\system\Express;
use app\models\system\SystemCity;
use app\models\system\SystemStore;
use app\models\system\SystemStoreStaff;
use app\models\user\User;
use app\models\user\UserBill;
use app\models\user\WechatUser;
use app\Request;
use crmeb\services\CacheService;
use crmeb\services\sms\Sms;
use crmeb\services\sms\storage\Tencent;
use crmeb\services\UtilService;
use crmeb\services\workerman\ChannelService;
use think\facade\Cache;
use crmeb\services\upload\Upload;
use think\facade\Log;

/**
 * 公共类
 * Class PublicController
 * @package app\api\controller
 */
class PublicController
{


    /**
     * 腾讯云短信回调接口
     * @param Request $request
     */
    public function tencent_sms_notify(Request $request){

        SmsRecord::updateRecordCallback($request->getContent());

    }

}