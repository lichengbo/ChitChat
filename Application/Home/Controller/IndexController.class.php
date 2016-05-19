<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }

    function test() {
        $arr = array("a", "b", "c", "d");

        dump($arr);

        unset($arr[1]);
        dump($arr);
    }

    function getToken() {

        //id(long)、name(string)、sex(int，0代表保密，1代表男，2代表女)、tag(string)、
        //location(double，格式为经度,纬度)、createTime(long)，以json方式传输，方法为post，
        //要求返回true(成功)或false(失败)
    }

    function login() {
        $userInfo = file_get_contents('php://input');
        dump($userInfo);
        /*$userInfo = I('request.');

        dump($userInfo);*/

        // 用户信息校验
        /*if($userInfo['id'] == null) {
            $data['status'] = "error";
            $data['info'] = ""
        }*/

        /*if(!empty($userInfo)) {
            $user = S('user');
            $user[$userInfo['id']] = $userInfo;
            $result = S('user', $user);

            if($result) {
                $data['status'] = "success";
                $data['info'] = "login and save user info success";

                $this->ajaxReturn($data);
            } else {
                $data['status'] = "error";
                $data['info'] = "save user info error";

                $this->ajaxReturn($data);
            }
        } else {
            $data = $userInfo;
            $data['status'] = "error";
            $data['info'] = "user info empty";

            $this->ajaxReturn($data);
        }*/
    }

    function logout() {

    }


    // 根据经纬度计算出附近的人，返回用户列表
    function nearFirends() {

    }

    function getCount() {
        $data = I('get.');
//        S('user', null);
        if(!empty($data)) {
            $user = S('user');
            $user[$data['uid']] = $data;
            S('user', $user);
        }


        dump(S('user'));
    }

    function getUserInfo() {
        $userInfo = S('user');
        dump($userInfo);
//        S('user', null);
    }

    function getTime() {
        $data['status'] = 'success';
        $data['time'] = time();

        $this->ajaxReturn($data);
    }
}