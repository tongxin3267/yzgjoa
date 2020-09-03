<?php
/**
*   来自：Joy办公系统开发团队
*   作者：磐石(rainrock)
*   网址：http://xh829.com/
*   系统的核心文件之一，处理工作流程模块的。
*/
class smsClassModel extends Model
{
    public $apikey = "febabb5cb04510f49a538b7f73db6e6a";                        //为云片分配的apikey
    //public $apikey = "febabb5cb04510f49a538b7f73db6e6a";                        //为云片分配的apikey
    public $mobile = "13661899638";                                                        //为接受短信的手机号
    public $text=" ";                                                           //为短信内容

    /**
    *   系统邮件发送邮件
    *   $to_uid 发送给。。。
    *   $rows   内容
    */
    //根据用户id发送短信
    public function postsms($title, $body, $to_uid, $rows=array(), $zjsend=0){

        $to_em  = $to_mn = $to_id   = '';
        
        $urs    = $this->db->getall("select `mobile`,`name`,`id` from `[Q]admin` where `id` in($to_uid) and `mobile` is not null and `status`=1 order by `sort`");

        $this->text = $body;
        foreach($urs as $k=>$rs){
            $this->mobile = $rs['mobile'];
            $result = $this->send_sms();
            $this->mobile = '';
        }   
        return $result;
    }
    
    //根据用户名发送短信
    public function postsms2($body, $to_uname){

        $to_em  = $to_mn = $to_id   = '';
        $to_uname="'".$to_uname."'";
        $urs    = $this->db->getall("select `mobile`,`name`,`id` from `[Q]admin` where `name` in($to_uname) and `mobile` is not null and `status`=1 order by `sort`");
           // var_dump($urs);
        $this->text = $body;
        foreach($urs as $k=>$rs){
            $this->mobile = $rs['mobile'];
            $result = $this->send_sms();
            $this->mobile = '';
        }   
        return $result;
    }
    //根据手机号发送短信
    public function postsms3($body, $to_uphone){

        $this->text = $body;
        $this->mobile = $to_uphone;
        $result = $this->send_sms();
        return $result;
    }

    public function send_sms(){
        $url="http://yunpian.com/v1/sms/send.json";
        $encoded_text = urlencode("$this->text");
        $mobile = urlencode("$this->mobile");
        $post_string="apikey=$this->apikey&text=$encoded_text&mobile=$mobile";
        return $this->sock_post($url, $post_string);
    }

    /**
     * url 为服务的url地址
     * query 为请求串
     */
    public function sock_post($url,$query){
        $data = "";
        $info=parse_url($url);
        $fp=fsockopen($info["host"],80,$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";
        while ($str = trim(fgets($fp,4096))) {
            $header.=$str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }
        return $data;
    }

    //检测验证码是否正确
    public function checkCode(){
        //接收验证参数
        $code = $this->input->post('code',TRUE);
        $mobile = $this->input->post('mobile',TRUE);

        //取得真实参数
        $trueCode = $this->session->userdata('truemobileCode');
        $trueMoblie = $this->session->userdata('mobile');

        //判断当前接收验证手机号是否等于上一次发送短信验证码的手机号
        if($mobile == $trueMoblie){
            //手机号验证通过时验证 验证码
            if(strtolower($code) == $trueCode){
                die('{"status":200,"msg":"短信验证码正确"}');
            }else{
                die('{"status":-1,"msg":"短信验证码错误"}');
            }
        }else{
            die('{"status":-1,"msg":"不能使用非当前号码【'.$mobile.'】获取的短信验证码"}');
        }
    }
    
    //订单管理员订单提醒通知 请求入口
    public function postMSM2(){
        //手机号 订单管理员
        $this->mobile = '13618060552';
//        $this->mobile = '13728281441';
        $orderID = $this->input->post('orderID',TRUE);
        $username = $this->input->post('username',TRUE);
        $mobile = $this->input->post('mobile',TRUE);
        $paymentType = $this->input->post('paymentType',TRUE);
        $paymentTypeName = $this->input->post('paymentTypeName',TRUE);
        $isUse = $this->input->post('isUse',TRUE);

        //设置短信内容
        $this->text = "【一闪到店】下单提醒:用户名".$username." 订单号:".$orderID."电话:".$mobile."[".$paymentTypeName."]";
        if($isUse==0 && $session_orderID != $orderID){
            $result = $this->send_sms();

            $this->postMSM4();
            //支付方式  1支付宝  2微信  3货到付款 4余额支付
            if($paymentType != 3 && $paymentType != 4 ){
                $this->postMSM3();
            }
            die($result);
        }else{
        	die('{"status":-1}');
        }
       
    }

    //订单财务管理员提醒通知 请求入口
    public function postMSM3(){
        //手机号 财务
        $this->mobile = '15908166919';
//        $this->mobile = '13728281441';
        $orderID = $this->input->post('orderID',TRUE);
        $paymentTypeName = $this->input->post('paymentTypeName',TRUE);
        $totalMoney = $this->input->post('totalMoney',TRUE);

        //设置短信内容
        $this->text = "【一闪到店】支付提醒: 订单号:".$orderID."  金额:".$totalMoney." [".$paymentTypeName."]";

        $this->send_sms();
    }

    //用户订单提醒通知 请求入口
    public function postMSM4(){
        //手机号 用户
        $this->mobile = $this->input->post('userMobile',TRUE);
//        $this->mobile = '13728281441';
        $orderID = $this->input->post('orderID',TRUE);
        $username = $this->input->post('username',TRUE);
        if(time() <= strtotime(date('Y-m-d').' 11:00')){
            //设置短信内容
            $this->text = "【一闪到店】尊敬的 ".$username." ！您好。我们已收到您的订单".$orderID."，您在“一闪到店”选购的商品，预计今天下午6点送达。请耐心等待！祝您生意兴隆！管理员电话：15183867241";
        }else{
            //设置短信内容
            $this->text = "【一闪到店】尊敬的 ".$username." ！您好。我们已收到您的订单".$orderID."，您在“一闪到店”选购的商品，预计明天下午6点送达。请耐心等待！祝您生意兴隆！管理员电话：15183867241";
        }

        if(strlen($this->mobile)==11){
            $this->send_sms();
        }
    }

    //采购商到货通知 请求入口
    public function postMSM5(){
        //手机号 财务
        $orderID = $this->input->post('orderID',TRUE);
        //$username = $this->input->post('username',TRUE);
        $this->mobile = $this->input->post('mobile',TRUE);

        //设置短信内容
        $this->text = "【一闪到店】到货提醒: 订单号:".$orderID."已送达，请确认签收。";

        $this->send_sms();
    }

    //营销短信
    public function postForEveryOne(){

        //手机号 查询
        $data = $this->mysql_model->db_select(ADMIN,array('username'=>'18090357556'),'username');//获取用户购物车所有数据
        //$data = $this->mysql_model->db_select(ADMIN,'','username');//获取用户购物车所有数据
        $msg = $this->input->post('msg',TRUE);
        //设置短信内容
        $this->text = $msg;
        foreach ($data as $key => $value) {
            $search ='/^1[3|4|5|7|8][0-9]\d{4,8}$/';
            //判断是不是手机号
            if(preg_match($search,$value)) { 
                $this->mobile.=$value.",";  
            }
        }                
        $result=$this->send_msgEveryOne();
        die($result);
    }

    //营销短信
    public function send_msgEveryOne(){
        $url="https://sms.yunpian.com/v2/sms/batch_send.json";
        $encoded_text = urlencode("$this->text");
        $mobile = urlencode("$this->mobile");
        $post_string="apikey=$this->apikey&text=$encoded_text&mobile=$mobile";
        return $this->sock_postEveryOne($url, $post_string);
    }
     /**
     * url 为服务的url地址
     * query 为请求串
     */
    public function sock_postEveryOne($url,$query){
        $data = "";
        $info=parse_url($url);
        $fp=fsockopen($info["host"],80,$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";
        while ($str = trim(fgets($fp,4096))) {
            $header.=$str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }
        return $data;
    }

//模板添加
    public function MsgTplAdd(){
        $url="https://sms.yunpian.com/v2/tpl/add.json";
        $encoded_text = urlencode("$this->text");
        $post_string="apikey=$this->apikey&tpl_content=$encoded_text";
        return $this->sock_post($url, $post_string);
    }

//模板添加
    public function sendMsgTplAdd(){
        $msg = $this->input->post('msg',TRUE);
        //设置短信内容
        $this->text = $msg;
        $result=$this->MsgTplAdd();
        //将短信模板存入数据库

        //短信方面的东西，楼下开始还没写，空了写，反正不影响使用
        die($result);
    }

//短信方面的东西，楼下开始还没写，空了写，反正不影响使用
//模板查询
    public function MsgTplGet(){
        $url="https://sms.yunpian.com/v2/tpl/get.json";
        $encoded_text = urlencode("$this->text");
        $post_string="apikey=$this->apikey&tpl_content=$encoded_text";
        return $this->sock_post($url, $post_string);
    }

//模板查询
    public function sendMsgTplGet(){
        $msg = $this->input->post('msg',TRUE);
        //设置短信内容
        $this->text = $msg;
        $result=$this->MsgTplAdd();
        die($result);
    }
}
