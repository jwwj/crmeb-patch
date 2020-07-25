<?php

namespace app\admin\model\sms;

use app\admin\model\system\SystemConfig;
use crmeb\basic\BaseModel;
use crmeb\services\sms\Sms;

/**
 * @mixin think\Model
 */
class SmsRecord extends BaseModel
{

    /**
     * 短信状态
     * @var array
     */
    protected static $resultcode = ['100' => '成功', '130' => '失败', '131' => '空号', '132' => '停机', '133' => '关机', '134' => '暂无'];

    protected function getAddTimeAttr($value)
    {
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    public static function vaildWhere($where)
    {
        $model = new static();
        if ($where['type']) $model = $model->where('resultcode', $where['type']);
        if ($where['phone']) $model = $model->where('phone', $where['phone']);
        if ($where['status']) $model = $model->where('resultcode', $where['status']);
        if ($where['template']) $model = $model->where('template', $where['template']);
        return $model;
    }

    /**
     * 获取短信记录列表
     * @param $where
     * @return array
     */
    public static function getRecordList($where)
    {
        $data = self::vaildWhere($where)->page((int)$where['page'], (int)$where['limit'])->select();
        $recordIds = [];
        foreach ($data as $k => $item) {
//            if (!$item['resultcode']) {
//                $recordIds[] = $item['record_id'];
//            } else {
//                $data[$k]['_resultcode'] = self::$resultcode[$item['resultcode']] ?? '无状态';
//            }
            if(!$item['resultcode']){
                $data[$k]['_resultcode'] = self::$resultcode['134'];
                $data[$k]['note'] ='服务器暂未收到回调';
            }else{
                $rc_arr = json_decode($item['resultcode'],true)[0];
                if($rc_arr['report_status'] == 'SUCCESS'){
                    $data[$k]['_resultcode'] = self::$resultcode['100'];
                    $data[$k]['note'] = $rc_arr['user_receive_time'];
                }else{
                    $data[$k]['_resultcode'] = self::$resultcode['130'];
                    $data[$k]['note'] = $rc_arr['description'];
                }

            }

            $c_arr = json_decode($item['content'],true);
            $data[$k]['_content'] = implode($c_arr['TemplateParamSet'],',');
            unset($data[$k]['content']);
            unset($data[$k]['resultcode']);
            unset($data[$k]['record_id']);
            unset($data[$k]['add_ip']);
            unset($data[$k]['uid']);
        }

        unset($item);


//        没状态的去申请拿一次状态
//        if (count($recordIds)) {
//            $smsHandle = new Sms('tencent');
//            dd($recordIds);
//            $codeLists = $smsHandle->getStatus($recordIds);
//            if ($codeLists && isset($codeLists['status']) && $codeLists['status'] == 200 && isset($codeLists['data']) && is_array($codeLists['data'])) {
//                foreach ($codeLists['data'] as $item) {
//                    if (isset($item['id']) && isset($item['resultcode'])) {
//                        self::where('record_id', $item['id'])->update(['resultcode' => $item['resultcode']]);
//                        foreach ($data as $key => $value) {
//                            if ($item['id'] == $value['record_id']) {
//                                $data[$key]['_resultcode'] = $item['_resultcode'];
//                            }
//                        }
//                    }
//                }
//            }
//        }
        $count = self::vaildWhere($where)->count();
        return compact('count', 'data');
    }

    /**
     * 发送记录
     * @param $phone
     * @param $content
     * @param $template
     * @param $record_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function sendRecord($phone, $content, $template, $record_id)
    {
        $map = [
            'uid' => sys_config('sms_account'),
            'phone' => $phone,
            'content' => $content,
            'add_time' => time(),
            'template' => $template,
            'record_id' => $record_id,

            'add_ip' => app()->request->ip(),
        ];
        $msg = SmsRecord::create($map);
        if ($msg)
            return true;
        else
            return false;
    }

    /**
     * 获取腾讯云短信下发用户手机回调并保存到数据库
     * @param string $request
     */
    public static function updateRecordCallback(string $request){
        $request_array = json_decode($request,true);
        self::where('record_id', $request_array[0]['sid'])->update(['resultcode' => $request]);
    }
}
