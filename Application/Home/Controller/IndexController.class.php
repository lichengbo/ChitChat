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

        /*dump($arr);

        unset($arr[1]);
        dump($arr);

        foreach ($arr as $item) {
            if($item == "b") {
                continue;
            }

            echo $item;
        }*/

        /*$age=array("Bill"=>"35","Steve"=>"37","Peter"=>"43");
        dump($age);
        sort($age);
        dump($age);*/

    }

    function getToken() {

        //id(long)、name(string)、sex(int，0代表保密，1代表男，2代表女)、tag(string)、
        //location(double，格式为经度,纬度)、createTime(long)，以json方式传输，方法为post，
        //要求返回true(成功)或false(失败)
    }

    function login() {
        $userInfo = json_decode(file_get_contents('php://input'), true);

        // 用户信息校验
        /*if($userInfo['id'] == null) {
            $data['status'] = "error";
            $data['info'] = ""
        }*/

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
        $userInfo = json_decode(file_get_contents('php://input'), true);
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


    // 根据经纬度计算出附近的人，返回用户列表
    function getNearbyUsers() {
        $userInfo = I('post.');
        /*
        // 测试数据
        $userInfo = array(
        "id" => "4bb24252eb86b433",
        "name" => "张启",
        "createTime" => 1464100749,
        "latitude" => 31.77265982,
        "longitude" => 117.19616868,
        "sex" => 0);*/
        if(empty($userInfo)) {
            $user = S('user');

            // 删除自己的信息
            unset($user[$userInfo['id']]);

            foreach ($user as $key=>$item) {
                $user[$key]['distance'] = $this->getDistance($userInfo['latitude'], $userInfo['longitude'], $item['latitude'], $item['longitude']);
            }

            sort($user); // 改变数组键值
            $nearFriendList = $this->sortFirendList($user);

            // 获取post 中的第几页的值，完成分页显示

            $data = $nearFriendList;

            $this->ajaxReturn($data);
        } else {
            $data['status'] = "error";
            $data['info'] = "user id empty, cannot get near firends list";

            $this->ajaxReturn($data);
        }
    }

    function getDistance($lat1, $lng1, $lat2, $lng2) {
        $earthRadius = 6367000; //approximate radius of earth in meters

        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo / 1000;

        return round($calculatedDistance, 3);
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
//        S('user', null);
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