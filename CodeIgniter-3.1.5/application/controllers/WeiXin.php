<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/19
 * Time: 13:24
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class WeiXin extends CI_Controller
{
    private $appId='wx98b714d35cfacc0d';
    private $securet='630decf329857c0f14fe5a91dc25d9b7';


    /*
     * 获取access_token 和 jsapi_ticket
     * */
    public function getAccessToken(){
        if(file_exists('access_token.json')){
            $res = file_get_contents('access_token.json');
            $result = json_decode($res,true);
            $expires_time = $result["expires_time"];
            $access_token = $result["access_token"];
            $jsapi_ticket=$result["access_token"];
        }else{
            $expires_time =0;
            $access_token ='';
            $jsapi_ticket='';
        }
        $refresh_time=$this->config->item('refresh_time');
        if (time()>($expires_time + $refresh_time) || !$access_token || !$jsapi_ticket){
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appId."&secret=".$this->securet;
            $res = $this->http_request($url);
            $result = json_decode($res, true);
            $access_token = $result["access_token"];
            //获取jsapi_ticket
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=' . $access_token;
            $res =$this->http_request($url);
            if (!empty($res)){
                $result = json_decode($res, true);
                $jsApiTicke =$result['ticket'];
                if (!empty($jsApiTicke)) {
                    $jsapi_ticket=$jsApiTicke;
                }
            }
            $expires_time = time();
            file_put_contents('access_token.json', json_encode(array('access_token'=>$access_token,'jsapi_ticket'=>$jsapi_ticket,'expires_time'=>$expires_time)));
            //对指定域名进行推送
            $push_url=$this->config->item('push_url');
            if($push_url){
                foreach ($push_url as $v){
                    echo $this->http_request($v,json_encode(array('access_token'=>$access_token,'jsapi_ticket'=>$jsapi_ticket)));
                }
            }
        }
        return json_encode(array('access_token'=>$access_token,'jsapi_ticket'=>$jsapi_ticket));
    }

    /*
     * 自定义菜单
     * */
    public function createMenu($menu)
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
        if (empty($menu)) {
            return false;
        }
        $menu = urldecode(json_encode($this->url_encode($menu)));
        $res = getHtml($url, array(
            'method' => 'POST',
            'content' => $menu
        ));
        
        print_r($res);
    }


    public function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}