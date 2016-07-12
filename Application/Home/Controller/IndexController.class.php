<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
        $this->display();
    }

    function getToken() {
        $userInfo = I('post.');
        
        if(empty($userInfo)) {
            $data['status'] = "error";
            $data['info'] = "get token error, user info empty";

            $this->ajaxReturn($data);
        }

//        $p = new ServerController('0vnjpoadn9kkz','LNyNSj7HZs0Hj');
        $p = new ServerController('x18ywvqf873kc','YIqsj1MJDi9o4');
        $r = json_decode($p->getToken($userInfo['id'],$userInfo['name'], $userInfo['avatar']));

        if($r->code == 200) {
            $data['status'] = "success";
            $data['token'] = $r->token;
            
            $this->ajaxReturn($data);
        } else {
            $data['status'] = "error";
            $data['info'] = "get token error";

            $this->ajaxReturn($data);
        }
    }

    function login() {
        $userInfo = json_decode(file_get_contents('php://input'), true);

        if(!empty($userInfo)) {
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
            $data['status'] = "error";
            $data['info'] = "user info empty";

            $this->ajaxReturn($data);
        }
    }

    function logout() {
//        $userInfo = json_decode(file_get_contents('php://input'), true);
        $userInfo = I('post.');

        if(empty($userInfo)) {
            $data['status'] = "error";
            $data['info'] = "user logout error, user id empty";

            $this->ajaxReturn($data);
        }

        $user = S('user');
        unset($user[$userInfo['id']]);
        $result = S('user', $user);

        if($result) {
            $data['status'] = "success";
            $data['info'] = "user logout success";

            $this->ajaxReturn($data);
        } else {
            $data['status'] = "error";
            $data['info'] = "user logout error";

            $this->ajaxReturn($data);
        }
    }

    function update() {
        $userInfo = json_decode(file_get_contents('php://input'), true);
        // 判断是否有该用户，若没有返回错误信息
        
        if(!empty($userInfo)) {
            $user = S('user');
            $user[$userInfo['id']] = $userInfo;
            $result = S('user', $user);

            if($result) {
                $data['status'] = "success";
                $data['info'] = "update user info success";

                $this->ajaxReturn($data);
            } else {
                $data['status'] = "error";
                $data['info'] = "save user info error";

                $this->ajaxReturn($data);
            }
        } else {
            $data['status'] = "error";
            $data['info'] = "user info empty";

            $this->ajaxReturn($data);
        }
    }

    // 根据经纬度信息计算出附近的人，返回用户列表
    function getNearbyUsers() {
        $userInfo = I('post.');

        if(!empty($userInfo)) {
            $user = S('user');

            // 删除自己的信息
            unset($user[$userInfo['id']]);

            foreach ($user as $key=>$item) {
                $user[$key]['distance'] = $this->getDistance($userInfo['latitude'], $userInfo['longitude'], $item['latitude'], $item['longitude']);
            }

            sort($user); // 改变数组键值
            $nearFriendList = $this->sortFirendList($user);

            $data = $nearFriendList;
            $this->ajaxReturn($data);
        } else {
            $data = array();
            $this->ajaxReturn($data);
        }
    }

    function getDistance($lat1, $lng1, $lat2, $lng2) {
        //$lat1 = 31.775184; $lng1 = 117.196044; $lat2 = 31.77081145;$lng2 =  117.1951314;
        $earthRadius = 6367000; //approximate radius of earth in meters

        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return intval($calculatedDistance);
    }

    function sortFirendList($arr) {
        $len = count($arr);
        for($i = 1; $i < $len; $i++) {
            $tmp = $arr[$i];

            for($j = $i - 1; $j >= 0; $j--) {
                if($tmp['distance'] < $arr[$j]['distance']) {
                    $arr[$j + 1] = $arr[$j];
                    $arr[$j] = $tmp;
                } else {
                    break;
                }
            }
        }
        return $arr;
    }

    function getCount() {
        $data = I('get.');
        if(!empty($data)) {
            $user = S('user');
            $user[$data['uid']] = $data;
            S('user', $user);
        }

        dump(S('user'));
    }
    
    function clearAllUser() {
        S('user', null);
        dump('clear user success');
    }

//     获取全部用户信息（调试使用）
    function getUserInfo() {
        $userInfo = S('user');
        dump($userInfo);
    }

    function getUserById() {
        $userInfo = I('post.');

        if(empty($userInfo)) {
            $data['status'] = "error";
            $data['info'] = "get userinfo fail, user id empty";

            $this->ajaxReturn($data);
        }

        $user = S('user');
        $data = $user[$userInfo['id']];

        $this->ajaxReturn($data);
    }

    function getUsersByIds() {
//        $ids = I('post.');
        $ids = json_decode(file_get_contents('php://input'), true);

        if(empty($ids)) {
            $data['status'] = "error";
            $data['info'] = "get userinfo fail, user ids empty";

            $this->ajaxReturn($data);
        }

        $user = S('user');

        $data = array();
        foreach ($ids as $item) {
            array_push($data, $user[$item]);
        }

        $this->ajaxReturn($data);
    }

    function getTime() {
        $data['status'] = 'success';
        $data['time'] = time();

        $this->ajaxReturn($data);
    }
}