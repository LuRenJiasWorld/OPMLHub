<?php namespace App\Controllers;

use Ramsey\Uuid\Uuid;
use Overtrue\Pinyin\Pinyin;

class Opml extends BaseController
{
    // 获取OPML数据
    public function DisplayOPML($uuid) {
        if (isset($uuid) && self::_getOPMLInfo($uuid)) {
            // OPML格式为XML
            $this->response->setHeader("Content-Type", "application/xml; charset=utf-8");

            $opmlInfo = self::_getOPMLInfo($uuid);
            $userInfo = User::_getUserInfo("id", $opmlInfo["uid"]);

            $rssInfo = [];

            $userOptions = Options::getOptionsFromUID($userInfo["id"]);

            $allOPMLinfo = self::_getAllOPML($userInfo["id"], $userOptions["opml_order_pinyin"], $userOptions["rss_order_pinyin"]);

            foreach ($allOPMLinfo as $eachOPML) {
                if ($eachOPML["opml"]["uuid"] == $uuid) {
                    $rssInfo = $eachOPML["rss"];
                    break;
                }
            }

            $enabledRssInfo = [];

            foreach ($rssInfo as $eachRSS) {
                if ($eachRSS["enabled"] == true) {
                    $enabledRssInfo = array_merge($enabledRssInfo, [$eachRSS]);
                }
            }

            $renderData = [
                "OpmlTitle"     =>       $opmlInfo["title"],
                "OpmlData"      =>       $enabledRssInfo
            ];

            // 检查用户配置
            if ($userOptions["email_in_opml"]) $renderData["UserEmail"] = $userInfo["email"];
            if ($userOptions["xml_header"])    $renderData["XMLHeader"] = true;


            // 记录日志
            $request = \Config\Services::request();
            self::_logAccessInfo($uuid, $request->getIPAddress());

            return view("opml/opml", $renderData);
        } else {
            return redirect()->to("/user/login");
        }
    }

    // 更新(新增)OPML/RSS记录
    public function Update() {
        if (!parent::loginChecker()) return redirect()->to("/user/login");

        $request = \Config\Services::request();
        $getData = $request->getGet();
        $postData = $request->getPost();

        if (isset($getData["type"]) && $request->getMethod() == "post") {
            switch($getData["type"]) {
                case "opml":
                    if (isset($getData["uuid"])) {
                        // 检查UUID是否属于当前用户
                        if (self::_getOPMLInfo($getData["uuid"])["uid"] != User::_getCurrentUserInfo()["id"]) goto FAIL;

                        // 更新数据
                        if (!(isset($postData["title"]))) goto FAIL;
                        self::_updateOPMLInfo($getData["uuid"], User::_getCurrentUserInfo()["id"], esc($postData["title"]), true);
                    } else {
                        // 插入数据
                        if (!(isset($postData["title"]))) goto FAIL;

                        $uuid = Uuid::uuid4();
                        self::_insertOPMLInfo($uuid->toString(), User::_getCurrentUserInfo()["id"], esc($postData["title"]));
                    }

                    return redirect()->back()->withInput();
                case "rss":
                    if(isset($getData["uuid"])) {
                        // 检查UUID是否属于当前用户
                        if (self::_getOPMLInfo(self::_getRSSInfo($getData["uuid"])["opml_uuid"])["uid"] != User::_getCurrentUserInfo()["id"]) goto FAIL;

                        if (!(isset($postData["opml_uuid"]) && isset($postData["feed_name"]) && isset($postData["feed_comment"])
                            && isset($postData["feed_url"]) && isset($postData["website_url"]))) {
                            goto FAIL;
                        }
                        // 更新数据
                        self::_updateRSSInfo($getData["uuid"], esc($postData["opml_uuid"]), esc($postData["feed_name"]),
                            esc($postData["feed_comment"]), esc($postData["feed_url"]), esc($postData["website_url"]), true);
                    } else {
                        // 插入数据
                        if (!(isset($postData["opml_uuid"]) && isset($postData["feed_name"]) && isset($postData["feed_comment"])
                            && isset($postData["feed_url"]) && isset($postData["website_url"]))) goto FAIL;

                        $uuid = Uuid::uuid4();
                        self::_insertRSSInfo($uuid->toString(), $postData["opml_uuid"], esc($postData["feed_name"]), esc($postData["feed_comment"]),
                            esc($postData["feed_url"]), esc($postData["website_url"]));
                    }

                    return redirect()->back()->withInput();
                default:
                    goto FAIL;
            }
        } else {
            FAIL: return redirect()->to("/user/login");
        }
    }

    // 删除OPML/RSS记录
    public function Delete() {
        if (!parent::loginChecker()) return redirect()->to("/user/login");

        $request = \Config\Services::request();
        $getData = $request->getGet();

        if (isset($getData["type"]) && $getData["uuid"] && $request->getMethod() == "get") {
            switch($getData["type"]) {
                case "opml":
                    $opmlInfo = self::_getOPMLInfo($getData["uuid"]);
                    if ($opmlInfo && $opmlInfo["uid"] == User::_getCurrentUserInfo()["id"]) {
                        self::_updateOPMLInfo($getData["uuid"], User::_getCurrentUserInfo()["id"], $opmlInfo["title"], false);
                    } else {
                        goto FAIL;
                    }

                    // 删除之后应该跳转到首页
                    return redirect()->to("/user/home");

                case "rss":
                    $rssInfo = self::_getRSSInfo($getData["uuid"]);
                    if ($rssInfo && self::_getOPMLInfo($rssInfo["opml_uuid"])["uid"] == User::_getCurrentUserInfo()["id"]) {
                        self::_updateRSSInfo($rssInfo["uuid"], $rssInfo["opml_uuid"], $rssInfo["feed_name"], $rssInfo["feed_comment"],
                            $rssInfo["feed_url"], $rssInfo["website_url"], false);
                    } else {
                        goto FAIL;
                    }

                    // 删除之后应该跳转到首页
                    return redirect()->to("/user/home");
                default:
                    goto FAIL;
            }
        } else {
            FAIL: return redirect()->to("/user/login");
        }
    }

    // 根据UID获取用户所有（已启用）的OPML和RSS信息
    // 按照拼音排序
    public static function _getAllOPML($uid, $orderOPMLByPinyin = false, $orderRSSByPinyin = false) {
        $db = \Config\Database::connect();

        $builder = $db->table("opml");
        $query = $builder->where([
            "uid"       =>      $uid,
            "enabled"   =>      true
        ])->get();

        $opmlList = $query->getResult("array");

        $resultList = [];

        foreach ($opmlList as $eachOPML) {
            $tempList = [];
            $tempList["opml"] = $eachOPML;

            $builder = $db->table("rss");
            $query = $builder->where([
                "opml_uuid"     =>      $eachOPML["uuid"],
                "enabled"       =>      true
            ])->orderBy("update_time", "desc")->get();


            $rssList = $query->getResult("array");

            $tempList["rss"] = $rssList;
            $resultList = array_merge($resultList, [$tempList]);
        }

        if ($orderOPMLByPinyin || $orderRSSByPinyin) {
            // ------开始排序------
            $pinyin = new Pinyin();

            // 1. 获取OPML名称的拼音
            foreach ($resultList as &$eachResult) {
                $eachResult["opml"]["pinyin"] = implode($pinyin->convert($eachResult["opml"]["title"], PINYIN_KEEP_NUMBER | PINYIN_KEEP_ENGLISH | PINYIN_KEEP_PUNCTUATION));
                // 2. 获取RSS名称的拼音
                foreach ($eachResult["rss"] as &$eachRssResult) {
                    $eachRssResult["pinyin"] = implode($pinyin->convert($eachRssResult["feed_name"], PINYIN_KEEP_NUMBER | PINYIN_KEEP_ENGLISH | PINYIN_KEEP_PUNCTUATION));
                }
            }
            unset($eachResult);
            unset($eachRssResult);

            // 3. 对OPML进行排序
            if ($orderOPMLByPinyin) {
                $tempList = [];

                foreach ($resultList as $eachResult) {
                    $tempList = array_merge($tempList, [strtolower($eachResult["opml"]["pinyin"])]);
                }
                array_multisort($tempList, SORT_ASC, $resultList);
            }

            // 4. 对RSS进行排序
            if ($orderRSSByPinyin) {
                foreach ($resultList as &$eachResult) {
                    $tempList = [];
                    foreach ($eachResult["rss"] as $eachRssResult) {
                        $tempList = array_merge($tempList, [strtolower($eachRssResult["pinyin"])]);
                    }
                    array_multisort($tempList, SORT_ASC, $eachResult["rss"]);
                }
            }

            // ------结束排序------
        }

        return $resultList;
    }

    public static function _getOPMLInfo($uuid) {
        $db = \Config\Database::connect();

        $builder = $db->table("opml");
        $query = $builder->where([
            "uuid"      =>      $uuid
        ])->limit(1)->get();

        $opmlInfo = $query->getResult("array");

        if (count($opmlInfo) == 1) {
            return $opmlInfo[0];
        } else {
            return false;
        }
    }

    public static function _updateOPMLInfo($uuid, $uid, $title, $enabled) {
        $db = \Config\Database::connect();

        $builder = $db->table("opml");

        $query = $builder->where([
            "uuid"      =>      $uuid,
            "uid"       =>      $uid
        ])->update([
            "title"     =>      $title,
            "enabled"   =>      $enabled
        ]);
    }

    public static function _insertOPMLInfo($uuid, $uid, $title) {
        $db = \Config\Database::connect();

        $builder = $db->table("opml");

        $query = $builder->insert([
            "uuid"      =>      $uuid,
            "uid"       =>      $uid,
            "title"     =>      $title,
        ]);
    }

    public static function _getRSSInfo($uuid) {
        $db = \Config\Database::connect();

        $builder = $db->table("rss");

        $query = $builder->where([
            "uuid"          =>      $uuid
        ])->limit(1)->get();

        $rssInfo = $query->getResult("array");

        if (count($rssInfo) == 1) {
            return $rssInfo[0];
        } else {
            return false;
        }
    }

    public static function _updateRSSInfo($uuid, $opml_uuid, $feed_name, $feed_comment, $feed_url, $website_url, $enabled) {
        $db = \Config\Database::connect();

        $builder = $db->table("rss");

        $query = $builder->where([
            "uuid"      =>      $uuid
        ])->update([
            "opml_uuid"     =>      $opml_uuid,
            "feed_name"     =>      $feed_name,
            "feed_comment"  =>      $feed_comment,
            "feed_url"      =>      $feed_url,
            "website_url"   =>      $website_url,
            "enabled"       =>      $enabled
        ]);
    }

    public static function _insertRSSInfo($uuid, $opml_uuid, $feed_name, $feed_comment, $feed_url, $website_url) {
        $db = \Config\Database::connect();

        $builder = $db->table("rss");

        $query = $builder->insert([
            "uuid"          =>      $uuid,
            "opml_uuid"     =>      $opml_uuid,
            "feed_name"     =>      $feed_name,
            "feed_comment"  =>      $feed_comment,
            "feed_url"      =>      $feed_url,
            "website_url"   =>      $website_url
        ]);
    }

    private static function _logAccessInfo($opml_uuid, $access_ip) {
        $db = \Config\Database::connect();

        $builder = $db->table("opml_access_history");

        $builder->insert([
            "opml_uuid"     =>      $opml_uuid,
            "access_ip"     =>      $access_ip
        ]);
    }

    public static function _getOPMLRSSStatistic($uid) {
        $data = Opml::_getAllOPML($uid);

        $returnData["opml_count"] = count($data);
        $returnData["rss_count"]  = 0;

        foreach ($data as $each) {
            $returnData["rss_count"] += count($each["rss"]);
        }

        return $returnData;
    }

    public static function _getOPMLAccessHistory($uid) {
        $allOPML = self::_getAllOPML($uid);

        $uuidList = [];

        foreach ($allOPML as $each) {
            $uuidList = array_merge($uuidList, [$each["opml"]["uuid"]]);
        }

        $returnData["access_count"] = 0;
        $returnData["access_history"] = [0, 0, 0, 0, 0, 0, 0];

        $db = \Config\Database::connect();
        $builder = $db->table("opml_access_history");

        if (count($uuidList) !== 0) {
            $result = $builder->whereIn("opml_uuid", $uuidList)->orderBy("access_time", "asc")->get()->getResult("array");
        } else {
            $result = [];
        }

        // 计数
        if (count($result) !== 0) {
            $returnData["access_count"] = count($result);
        }

        // 统计每日历史
        $last7Days = [];
        for ($i = 0; $i < 7; $i++) {
            $day = "-" . (6 - $i) . "days";
            $last7Days = array_merge($last7Days, [date('Y-m-d', strtotime($day))]);
        }

        foreach ($result as $each) {
            foreach ($last7Days as $id => $eachDay) {
                if (strstr($each["access_time"], $eachDay)) {
                    $returnData["access_history"][$id] += 1;
                }
            }
        }

        return $returnData;
    }

}
