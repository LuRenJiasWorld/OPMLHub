<?php namespace App\Controllers;

class User extends BaseController {
    public function Login() {
        if (parent::loginChecker()) return redirect()->to("/user/home");

        $request = \Config\Services::request();

        $renderData = [
            "PageTitle"     =>      "OPMLHub - 登录"
        ];

        if ($request->getMethod() == "get") {
            return view("user/login", $renderData);
        } else {
            $postData = $request->getPost();

            if (isset($postData["email"]) && isset($postData["password"]) && $this->_authorizeUser($postData["email"], $postData["password"])) {
                $session = \Config\Services::session();
                $userInfo = $this->_getUserInfo("email", $postData["email"]);

                $session->set("user", $userInfo);

                // 更新登录信息
                $loginInfo = [
                    "login_time"    =>      date("Y-m-d H:i:s", time()),
                    "login_ip"      =>      $request->getIPAddress()
                ];

                $this->_updateCurrentUserLoginHistory($loginInfo);

                return redirect()->to("/user/home");
            } else {
                $renderData["error"] = "请输入正确的邮箱和密码！";
                return view("user/login", $renderData);
            }
        }
    }

    public function Logout() {
        if (!parent::loginChecker()) return redirect()->to("/user/login");

        $session = \Config\Services::session();
        $session->remove("user");

        return redirect()->to("/");
    }

    public function Register() {
        $renderData = [
            "PageTitle"     =>      "OPMLHub - 注册"
        ];
        return view("user/register", $renderData);
    }

    public function Reset() {
        $renderData = [
            "PageTitle"     =>      "OPMLHub - 重置密码"
        ];
        return view("user/reset", $renderData);
    }

    public function Home() {
        if (!parent::loginChecker()) return redirect()->to("/user/login");

        $renderData = [
            "PageTitle"     =>      "OPMLHub - 首页"
        ];

        $request = \Config\Services::request();
        $getData = $request->getGet();

        if (isset($getData["module"]) && isset($getData["page"])) {
            // 读取当前用户的所有OPML和RSS
            $allOPML = Opml::_getAllOPML(self::_getCurrentUserInfo()["id"]);

            $renderData["opml"] = $allOPML;

            // 根据参数判断子路由
            // module：模块
            switch ($getData["module"]) {
                case "index":
                    // page：订阅配置的子页面
                    switch ($getData["page"]) {
                        case "index":
                            return view("user/home", $renderData);
                            break;
                        case "opml":
                            if (isset($getData["uuid"])) {
                                $renderData["currentData"] = Opml::_getOPMLInfo($getData["uuid"]);

                                if ($renderData["currentData"]["enabled"] == false) goto FAIL;
                                else return view("user/opml_config", $renderData);
                            } else {
                                // 新建OPML页面
                                return view("/user/opml_config", $renderData);
                            }
                            break;
                        case "rss":
                            if (isset($getData["uuid"])) {
                                $renderData["currentData"] = Opml::_getRSSInfo($getData["uuid"]);
                                return view("user/rss_config", $renderData);
                            } else {
                                // 新建RSS页面
                                if (isset($getData["opml"])) {
                                    $renderData["OpmlUuid"] = $getData["opml"];
                                }
                                return view("/user/rss_config", $renderData);
                            }
                            break;
                        default:
                            goto FAIL;
                    }
                    break;
                case "settings":
                    return view("user/settings", $renderData);
                    break;
                case "password":
                    return view("user/change_password", $renderData);
                    break;
                default:
                    goto FAIL;
            }
        } else {
            FAIL:
            return redirect()->to("/user/home?module=index&page=index");
        }
    }

    private function _authorizeUser($email, $password) {
        $db = \Config\Database::connect();

        $builder = $db->table("user");
        $query = $builder->where([
            "email" => $email
        ])->limit(1)->get();

        $data = $query->getResult("array");

        if (count($data) == 1 && $data[0]["password"] == parent::passwordGenerator($password)) {
            return true;
        } else {
            return false;
        }
    }

    public static function _getUserInfo($type, $value) {
        $db = \Config\Database::connect();

        $builder = $db->table("user");
        $query = $builder->where([
            $type => $value
        ])->limit(1)->get();

        $data = $query->getResult("array");

        if (count($data) == 1) {
            return $data[0];
        } else {
            return false;
        }
    }

    public static function _getCurrentUserInfo() {
        $session = \Config\Services::session();
        $user = $session->get("user");

        return $user;
    }

    private function _registerUser($username, $password) {

    }

    private function _updateCurrentUserLoginHistory($currentHistory) {
        $db = \Config\Database::connect();

        $builder = $db->table("user");

        $userInfo = self::_getCurrentUserInfo();

        $loginHistory = json_decode($userInfo["login_history"], true);

        if ($loginHistory) {
            $loginHistory = array_merge($loginHistory, [$currentHistory]);
        } else {
            $loginHistory = [$currentHistory];
        }

        $builder->where([
            "id"            =>      $userInfo["id"]
        ])->update([
            "login_history" =>      json_encode($loginHistory, JSON_UNESCAPED_UNICODE)
        ]);
    }
}