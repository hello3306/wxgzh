<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-05
 * Time: 16:15
 */

namespace app\api\service;


use think\Log;

class VerToken extends BaseService
{
    public function valid()
    {
        return $this->checkSignature();
    }

//    public function responseMsg()
//    {
//        //get post data, May be due to the different environments
//        $postStr = file_get_contents("php://input");
//
//        //extract post data
////        if (!empty($postStr)) {
//        $postObj = simplexml_load_string($postStr,'SimpleXMLElement', LIBXML_NOCDATA);
//        if (strtolower($postObj->MsgType) == 'event') {
//            if (strtolower($postObj->Evevt == 'subscribe')) {
//                $fromUsername = $postObj->FromUserName;
//                $toUsername = $postObj->ToUserName;
//                $time = time();
//                $msgType = "text";
//                $contentStr = "Welcome to wechat world!";
//
//                $textTpl = "<xml>
//							<ToUserName><![CDATA[%s]]></ToUserName>
//							<FromUserName><![CDATA[%s]]></FromUserName>
//							<CreateTime>%s</CreateTime>
//							<MsgType><![CDATA[%s]]></MsgType>
//							<Content><![CDATA[%s]]></Content>
//							</xml>";
//                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//                return $resultStr;
//            }
//
//        }
//
//
//    }
    // 接收事件推送并回复
//    public function responseMsg()
//    {
//        //1.获取到微信推送过来post数据（xml格式）
//        //$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];//php7以上不能用
////        $postArr = file_get_contents("php://input");
//        $postArr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
//        //2.处理消息类型，并设置回复类型和内容
//        $postObj = simplexml_load_string($postArr);
//        //判断该数据包是否是订阅的事件推送
//        if (strtolower($postObj->MsgType) == 'event') {
//            //如果是关注 subscribe 事件
//            if (strtolower($postObj->Event == 'subscribe')) {
//                //回复用户消息(纯文本格式)
//                $toUser = $postObj->FromUserName;
//                $fromUser = $postObj->ToUserName;
//                $time = time();
//                $msgType = 'text';
//                //$content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-'.$postObj->ToUserName;
//                $content = '欢迎关注放哥的微信公众账号';
//                $template = "<xml>
//                            <ToUserName><![CDATA[%s]]></ToUserName>
//                            <FromUserName><![CDATA[%s]]></FromUserName>
//                            <CreateTime>%s</CreateTime>
//                            <MsgType><![CDATA[%s]]></MsgType>
//                            <Content><![CDATA[%s]]></Content>
//                            </xml>";
//                $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
//                return $info;
//            }
//        }
//    }//reponseMsg end
    public function responseMsg()
    {
        //get post data, May be due to the different environments
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postStr = file_get_contents("php://input");
        //extract post data
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
//            if (!empty($keyword)) {
            $msgType = "text";
            $contentStr = "<a href='www.baidu.com'>" . "Welcome to wechat world!" . "</a>";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            return $resultStr;
//            } else {
//                return "Input something...";
//            }

        } else {
            return "";
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = config('secure.token_salt');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
//        $echostr = $_GET['echostr'];

        $getData = input('get.');
        if (array_key_exists('echostr', $getData)) {
            $echostr = $getData['echostr'];
        } else {
            $echostr = '';
        }
        if ($tmpStr == $signature && $echostr) {
            return $echostr;
        } else {
            return $this->responseMsg();
        }
    }
}