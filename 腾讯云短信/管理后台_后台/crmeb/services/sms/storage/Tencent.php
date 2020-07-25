<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace crmeb\services\sms\storage;
// 导入 SMS 的 client
use crmeb\basic\BaseSms;
use TencentCloud\Sms\V20190711\SmsClient;

// 导入要请求接口对应的 Request 类
use TencentCloud\Sms\V20190711\Models\SendSmsRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;

// 导入可选配置类
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use think\facade\Config;


class Tencent extends BaseSms
{

    protected $secretId = "";//账户的appid 非短信的appid
    protected $secretKey = "";//账户的appkey 非短信的appkey
    protected $endpoint = ""; //短信发送的服务器网址
    protected $region = ""; //短信发送的服务器地点
    protected $smsSdkAppid = "";//短信的appid
    protected $sign = "";  //短信签名 中文
    protected $templates = "";  //短信模板id


    /**
     * 初始化Tencent短信配置
     * @param array $config
     */
    protected function initialize(array $config)
    {
        parent::initialize($config);
        $conf = Config::get('sms.stores.tencent', []);

        $this->secretId = $conf['secret_id'];
        $this->secretKey = $conf['secret_key'];
        $this->endpoint = $conf['endpoint'];
        $this->region = $conf['region'];
        $this->smsSdkAppid = $conf['sms_sdk_appid'];
        $this->sign = $conf['sign'];

        $this->templates = $conf['template_id'];
    }

    /**
     * 发送短信前，判断数据是否规范
     * @param string $phone
     * @param string $templateId 这里为字符串，通过$this->templates[]转化为数字
     * @param array $data 模板中{0}，{1}的数据
     */
    public function send(string $phone, string $templateId, array $data = [])
    {
        if (empty($phone)) {
            return $this->setError('Mobile number cannot be empty');
        }

        if (empty($this->templates[$templateId])) {
            return $this->setError('Missing template number');
        }

        return $this->sendCode($phone, $data, $templateId);
    }

    /**
     * 发送短信
     * @param $phone
     * @param $code //数组形式，
     * @param String $templateId //模板ID
     * @return array[]|bool
     */
    protected function sendCode($phone, array $code, string $templateId)
    {
        try {

            $cred = new Credential($this->secretId, $this->secretKey);

            $httpProfile = new HttpProfile();
            $httpProfile->setReqMethod("GET");//默认为POST
            $httpProfile->setReqTimeout(30);
            $httpProfile->setEndpoint($this->endpoint);

            $clientProfile = new ClientProfile();
            $clientProfile->setSignMethod("TC3-HMAC-SHA256");
            $clientProfile->setHttpProfile($httpProfile);

            $client = new SmsClient($cred, $this->region, $clientProfile);

            $req = new SendSmsRequest();

            $req->SmsSdkAppid = $this->smsSdkAppid;
            $req->Sign = $this->sign;
            $req->ExtendCode = "0";//短信码号扩展号，默认未开通

            $req->PhoneNumberSet = array("+86" . $phone);
            $req->SenderId = "";//国际/港澳台短信 senderid，国内短信填空，默认未开通

            $req->TemplateID = $this->templates[$templateId];
            $req->TemplateParamSet = array();
            foreach ($code as $item) {
                //dump($item);
                array_push($req->TemplateParamSet, $item);
            }

            $resp = $client->SendSms($req);

            if ($resp->SendStatusSet[0]->Code != "Ok") {
                return $this->setError($resp->SendStatusSet[0]->Code);
            }

            $req_array = $this->object_to_array($req);
            $resq_array = $this->object_to_array($resp->SendStatusSet[0]);
            $content = json_encode(array_merge_recursive($req_array, $resq_array));//合并发送前后两个数组
            return [
                'data' => [
                    'id' => $resq_array['SerialNo'],
                    'content' => $content,
                    'template' => $templateId,//这里输出文字，方便查询
                ]
            ];

        } catch (TencentCloudSDKException $e) {
            return $this->setError($e);
        }
    }

    private function object_to_array($stdclassobject)
    {
        $_array = is_object($stdclassobject) ? get_object_vars($stdclassobject) : $stdclassobject;
        foreach ($_array as $key => $value) {
            $value = (is_array($value) || is_object($value)) ? $this->object_to_array($value) : $value;
            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * 短信回调，保存用户接受状态
     * @param $record_id
     * @return mixed
     */
    public function getStatus(array $req)
    {
//        $data['record_id'] = json_encode($record_id);
//        return json_decode(HttpService::postRequest($this->smsUrl . 'sms/status', $data), true);
//        return true;
        $model = new static();
        where('record_id', $item['id'])->update(['resultcode' => $item['resultcode']]);
        dd($req);


    }
}
