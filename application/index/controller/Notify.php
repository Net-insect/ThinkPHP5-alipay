<?php

namespace app\index\controller;

use think\Db;
use app\common\controller\NotifyHandler;

/**
* 通知处理控制器
*
* 完善getOrder, 获取订单信息, 注意必须数组必须包含out_trade_no与total_amount
* 完善checkOrderStatus, 返回订单状态, 按要求返回布尔值
* 完善handle, 进行业务处理, 按要求返回布尔值
*
* 请求地址为index, NotifyHandler会自动调用以上三个方法
*/
class Notify extends NotifyHandler
{
    protected $params; // 订单信息

    public function index()
    {
        parent::init();
    }

    /**
     * 获取订单信息, 必须包含订单号和订单金额
     *
     * @return string $params['out_trade_no'] 商户订单
     * @return float  $params['total_amount'] 订单金额
     */
    public function getOrder()
    {
        // 以下仅示例
//        $out_trade_no = $_POST['out_trade_no'];
//        $order = Db::name('order')->where('out_trade_no', $out_trade_no)->find();//根据订单查询单条订单信息


        //假如查出来的订单数据为一个数组
        $out_trade_no = time().rand(1000,9999);
        $order = array(
              'out_trade_no' => $out_trade_no,
             'total_amount'=>0.01,
            'status' =>0,
            'id'=>1,
            'subject'=>'抱抱',
        );



        $params = [
            'out_trade_no' => $order['out_trade_no'], //商户订单号
            'total_amount' => $order['total_amount'],//订单金额
            'status'       => $order['status'], //当前订单状态
            'id'           => $order['id'], //当前订单id
            'subject'     => $order['subject'] //订单标题
        ];

        $this->params = $params;
    }

    /**
     * 检查订单状态
     *
     * @return Boolean true表示已经处理过 false表示未处理过
     */
    public function checkOrderStatus()
    {
        // 以下仅示例
        if($this->params['status'] == 0) {
            // 表示未处理
            return false;
        } else {
            return true;
        }
    }

    /**
     * 业务处理
     * @return Boolean true表示业务处理成功 false表示处理失败
     */
    public function handle()
    {
        // 以下仅示例
        $result = Db::name('order')->where('id', $this->params['id'])->update(['status'=>1]);
        if($result) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 准备支付
     */
    public function zhifu(){
        $this->getOrder();

        $zhifu = \alipay\Pagepay::pay($this->params); //调用支付宝支付
    }


    
}