<?php
include_once ''.ROOT_PATH.'/include/weixin/WXBizMsgCrypt.php'; class weixin_diaoClassModel extends weixinModel { private $wxcpt = array(); public function getxml() { $sReqMsgSig = $sVerifyMsgSig = $this->rock->get('msg_signature'); $sReqTimeStamp = $sVerifyTimeStamp = $this->rock->get('timestamp'); $sReqNonce = $sVerifyNonce = $this->rock->get('nonce'); $sVerifyEchoStr = $this->rock->get('echostr'); $garr = m('weixin:index')->gethuidiao(); $corpid = $garr['corpid']; $Tokenwx = $garr['huitoken']; $encodingAesKey = $garr['aeskey']; $wxcpt = new WXBizMsgCrypt($Tokenwx, $encodingAesKey, $corpid); $this->wxcpt = $wxcpt; if($sVerifyEchoStr){ $sEchoStr = ''; $errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr); if ($errCode == 0) { print($sEchoStr); }else{ print($errCode . "\n\n"); } exit; } if(!isset($GLOBALS['HTTP_RAW_POST_DATA']))exit('sorry!'); $sReqData = $GLOBALS['HTTP_RAW_POST_DATA']; $sMsg = ''; $errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg); if($errCode == 0){ $this->rock->debugs($sMsg."\n\n".$_SERVER['REQUEST_URI'],'bbbb'); $xml = new xmlDecodes($sMsg); return $xml; }else{ return false; } } public function EncryptMsg($sMsg) { $sNonce = $this->rock->get('nonce'); $sTimeStamp = $this->rock->get('timestamp'); $sMsgb = ''; $errCode = $this->wxcpt->EncryptMsg($sMsg, $sTimeStamp, $sNonce, $sMsgb); if($errCode == 0){ return $sMsgb; }else{ return false; } } } class xmlDecodes { public $xml; public function __construct($sMsg) { $this->xml = new DOMDocument(); $this->xml->loadXML($sMsg); } public function getval($key, $xu=0) { @$val = $this->xml->getElementsByTagName($key)->item($xu)->nodeValue; return $val; } public function getobj($key, $xu=0) { $this->_temparr = array(); $obj = $this->xml->getElementsByTagName($key)->item($xu); $this->getvaluess($obj); return $this->_temparr; } private function getvaluess($Item) { foreach($Item->childNodes as $nodename) { $tag = $nodename->tagName; if($tag=='Receiver' || $tag=='ChatInfo'){ $this->getvaluess($nodename); }else{ $this->_temparr[$tag] = $nodename->nodeValue; } } } }