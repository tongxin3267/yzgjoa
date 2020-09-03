<?php
class wxgzh_oauthClassModel extends wxgzhModel
{
	public function oauthto()
	{
		$this->readwxset();
		if($this->appid==''){
			$url = '?d=we#notappid';
			$this->rock->location($url);
			return false;
		}
		$state			= $this->rock->get('state','bang');
		$redurl			= ''.URL.'?d=we&a=oauthback&m=login';
		$redirect_uri	= urlencode($redurl);
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect ';
		$this->rock->location($url);
		return true;
	}
	
	public function oauthback()
	{
		$code	= $this->rock->get('code');
		$state	= $this->rock->get('state');
		if($code=='')return;
		$this->readwxset();
		$url 	= 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->secret.'&code='.$code.'&grant_type=authorization_code';
		$result = c('curl')->getcurl($url);
		$openid = '';
		$access_token = '';
		if($result != ''){
			$arr	= json_decode($result);
			if(isset($arr->openid))$openid = $arr->openid;
			if(isset($arr->access_token))$access_token = $arr->access_token;
		}
		if($openid!=''){
			
		}else{
			
		}
		//拉取用户信息
		$url = '?d=we';
		$this->rock->location($url);
	}
}