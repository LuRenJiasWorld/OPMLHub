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
                return redirect()->to("/user/login?notify=error&message=请输入正确的邮箱和密码!");
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
        if (parent::loginChecker()) return redirect()->to("/user/home");

        $request = \Config\Services::request();

        $renderData = [
            "PageTitle"     =>      "OPMLHub - 注册"
        ];

        if ($request->getMethod() == "get") {
            return view("user/register", $renderData);
        } else {
            $postData = $request->getPost();

            if (isset($postData["email"]) && isset($postData["password"]) && isset($postData["password_again"])) {
                // 检查电子邮件格式是否合法
                if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $postData["email"])) {
                    return redirect()->to("/user/register?notify=error&message=电子邮件格式不正确！");
                }

                // 检查用户是否重复
                if (self::_getUserInfo("email", $postData["email"])) {
                    return redirect()->to("/user/register?notify=error&message=已存在同名用户，请使用密码重置功能！");
                }

                // 检查两次密码是否一致
                if ($postData["password"] !== $postData["password_again"]) {
                    return redirect()->to("/user/register?notify=error&message=两次密码不一致！");
                }

                // 检查密码强度
                if (!self::_checkPasswordStrength($postData["password"])) {
                    return redirect()->to("/user/register?notify=error&message=密码不满足强度要求！");
                }

                // 新建用户
                $this->_addNewUser($postData["email"], $postData["password"]);

                // 初始化用户配置
                $userDefaultConf = [
                    "opml_order_pinyin"     =>      true,
                    "rss_order_pinyin"      =>      true,
                    "xml_header"            =>      false,
                    "email_in_opml"         =>      false
                ];
                $uid = self::_getUserInfo("email", $postData["email"])["id"];
                foreach ($userDefaultConf as $key => $val) {
                    Options::updateOption($uid, $key, $val);
                }

                return redirect()->to("/user/login?notify=message&message=注册成功！");
            } else {
                return redirect()->to("/user/register?notify=error&message=请输入正确的信息!");
            }
        }
    }

    public function Reset() {
        if (parent::loginChecker()) return redirect()->to("/user/home");

        $request = \Config\Services::request();

        $renderData = [
            "PageTitle"     =>      "OPMLHub - 重置密码"
        ];

        if ($request->getMethod() == "get") {
            return view("user/reset", $renderData);
        } else {
            $postData = $request->getPost();

            if (isset($postData["email"])) {
                // 检查电子邮件格式是否合法
                if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/", $postData["email"])) {
                    return redirect()->to("/user/reset?notify=error&message=电子邮件格式不正确！");
                }

                // 检查用户是否存在
                $userInfo = self::_getUserInfo("email", $postData["email"]);
                if (!$userInfo) {
                    return redirect()->to("/user/reset?notify=error&message=用户不存在！");
                }

                // 重置用户密码
                $newPassword = parent::generateRandomString(8);
                $uid = $userInfo["id"];
                self::_changeUserPassword($uid, $newPassword);

                // 发送电子邮件


            } else {
                return redirect()->to("/user/reset?notify=error&message=请输入正确的信息!");
            }
        }
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
            $userSettings = Options::getOptionsFromUID(self::_getCurrentUserInfo()["id"]);
            $allOPML = Opml::_getAllOPML(self::_getCurrentUserInfo()["id"], $userSettings["opml_order_pinyin"], $userSettings["rss_order_pinyin"]);

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
                    $userOptions = Options::getOptionsFromUID(self::_getCurrentUserInfo()["id"]);
                    $renderData["UserOptions"] = $userOptions;

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

    public function Update() {
        if (!parent::loginChecker()) return redirect()->to("/user/login");

        $request = \Config\Services::request();
        $getData = $request->getGet();
        $postData = $request->getPost();

        if(isset($getData["type"])) {
            switch($getData["type"]) {
                case "settings":
                    $uid = self::_getCurrentUserInfo()["id"];

                    $expectedOptionsList = ["opml_order_pinyin", "rss_order_pinyin", "xml_header", "email_in_opml"];

                    foreach ($expectedOptionsList as $each) {
                        if (isset($postData[$each]) && $postData[$each] == "true") {
                            // 在列表中说明需要启用
                            Options::updateOption($uid, $each, true, true);
                        } else {
                            // 不在列表中说明需要禁用
                            Options::updateOption($uid, $each, false, true);
                        }
                    }

                    return redirect()->back()->withInput();
                case "password":
                    if (isset($postData["old_password"]) && isset($postData["new_password"]) && isset($postData["new_password_again"])) {
                        // 检查两次新密码是否一致
                        if ($postData["new_password"] !== $postData["new_password_again"])  {
                            return redirect()->to("/user/home?module=password&page=index&notify=error&message=" . urlencode("两次新密码不一致！"));
                        }

                        // 检查旧密码
                        if (!self::_authorizeUser(self::_getCurrentUserInfo()["email"], $postData["old_password"])) {
                            return redirect()->to("/user/home?module=password&page=index&notify=error&message=" . urlencode("旧密码错误！"));
                        }

                        // 检查新旧密码是否相同
                        if ($postData["new_password"] == $postData["old_password"]) {
                            return redirect()->to("/user/home?module=password&page=index&notify=error&message=" . urlencode("新旧密码不能一致！"));
                        }

                        // 检查新密码强度
                        if (!self::_checkPasswordStrength($postData["new_password"])) {
                            return redirect()->to("/user/home?module=password&page=index&notify=error&message=" . urlencode("密码不满足强度要求！"));
                        }

                        // 修改密码
                        self::_changeUserPassword(self::_getCurrentUserInfo()["id"], $postData["new_password"]);

                        return redirect()->to("/user/home?module=password&page=index&notify=message&message=" . urlencode("更新成功！"));
                    } else {
                        goto FAIL;
                    }
                default:
                    goto FAIL;
            }
        } else {
            FAIL: return redirect()->to("/user/login");
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

    public static function _checkPasswordStrength($password) {
        return (bool)(preg_match("/^[\S\s]{6,16}$/", $password) && preg_match("/[A-Za-z]+/", $password) && preg_match("/[0-9]+/", $password));
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

    private function _changeUserPassword($uid, $newPassword) {
        $db = \Config\Database::connect();

        $builder = $db->table("user");

        $builder->where([
            "id"        =>      $uid
        ])->update([
            "password"  =>      parent::passwordGenerator($newPassword)
        ]);
    }

    public static function _getCurrentUserInfo() {
        $session = \Config\Services::session();
        $user = $session->get("user");

        return $user;
    }

    private function _addNewUser($email, $password) {
        $db = \Config\Database::connect();

        $builder = $db->table("user");

        $builder->insert([
            "email"     =>      $email,
            "password"  =>      parent::passwordGenerator($password)
        ]);
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