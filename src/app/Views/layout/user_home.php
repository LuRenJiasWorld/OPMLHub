<?= $this->extend("layout/main_layout") ?>

<?= $this->section("header-style") ?>
<style>
    #app {
        height: 100%;
        padding: 0;
        /*background-color: green;*/
        left: 0;
        right: 0;
        margin: auto;
    }

    #header {
        width: 100%;
    }

    #header .layui-logo a {
        font-size: 24px;
        color: rgba(255,255,255,.7);
        transition: color 0.2s ease-in-out;
    }

    #header .layui-logo a:hover {
        color: white;
    }

    #sidebar dl.layui-nav-child dd {
        display: flex;
    }

    #sidebar dl.layui-nav-child dd .rss-list-item {
        flex: 1;
    }

    #sidebar dl.layui-nav-child dd .rss-list-item i, #sidebar dl.layui-nav-child dd .add-rss i {
        margin-right: 8px;
    }

    #sidebar dl.layui-nav-child dd .delete-rss {
        cursor: pointer;
    }

    #sidebar dl.layui-nav-child dd .add-rss {
        width: 100%;
    }

    #sidebar .layui-nav .opml-title {
        display: flex;
        padding-right: 10px;
        padding-left: 5px;
    }

    #sidebar .layui-nav .opml-title span {
        flex: 1;
    }

    #sidebar .layui-nav .opml-title i {
        display: inline-block;
        font-size: 18px;
        float: right;
        transform: translateY(2px);
        padding: 0 10px;
    }

    #sidebar .layui-nav .opml-title .layui-nav-more {
        display: none;
    }

    #sidebar #add-opml-category i {
        font-size: 20px;
        margin-right: 10px;
        transform: translateY(2px);
        display: inline-block;
    }

    #footer {
        text-align: right;
    }

    /* 左侧边栏展宽 */
    .layui-layout-admin .layui-side,
    .layui-layout-admin .layui-side-scroll,
    .layui-layout-admin .layui-side-scroll ul {
        width: 260px !important;
    }

    .layui-body {
        left: 260px !important;
    }

    /* 鼠标悬浮样式美化 */
    .layui-nav-tree .layui-nav-item > a:hover, .layui-nav-tree .layui-nav-item dd:hover {
        background-color: #159688 !important;
    }

    span.layui-nav-bar {
        display: none !important;
    }

    .required {
        color: red;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div id="app" class="layui-layout layui-layout-admin">
    <div id="header" class="layui-header">
        <div class="layui-logo"><a href="/">OPMLHub</a></div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item"><a href=""><i class="layui-icon layui-icon-rss"></i>订阅配置</a></li>
            <li class="layui-nav-item"><a href=""><i class="layui-icon layui-icon-slider"></i>高级设置</a></li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item"><a href="/user/logout">修改密码</a></li>
            <li class="layui-nav-item"><a href="/user/logout">退出</a></li>
        </ul>
    </div>
    <div id="sidebar" class="layui-side layui-bg-black">
        <div class="layui-side-scroll">
            <ul class="layui-nav layui-nav-tree">
                <?php foreach ($opml as $eachOPML):?>
                    <li class="layui-nav-item layui-nav-itemed">
                        <a class="opml-title <?php if(isset($currentData) && $currentData["uuid"] == $eachOPML["opml"]["uuid"]) echo "layui-this"; ?>" title="<?= $eachOPML["opml"]["title"] ?>" href="javascript:;">
                            <i class="layui-icon layui-icon-template-1" style="margin-right: 10px; transform: translateY(-1px);"></i>
                            <span data-opml-uuid="<?= $eachOPML["opml"]["uuid"] ?>">
                                <?= $eachOPML["opml"]["title"] ?>
                            </span>
                            <i class="layui-icon layui-icon-share" data-opml-uuid="<?= $eachOPML["opml"]["uuid"] ?>"></i>
                        </a>
                        <dl class="layui-nav-child">
                            <?php foreach ($eachOPML["rss"] as $eachRSS):?>
                            <dd class="<?php if(isset($currentData) && $currentData["uuid"] == $eachRSS["uuid"]) echo "layui-this"; ?>">
                                <a class="rss-list-item" title="<?= $eachRSS["feed_name"] ?>" data-rss-uuid="<?= $eachRSS["uuid"] ?>" href="javascript:;">
                                    <i class="layui-icon layui-icon-rss"></i>
                                    <?= $eachRSS["feed_name"] ?>
                                </a>
                                <a class="delete-rss" data-rss-uuid="<?= $eachRSS["uuid"] ?>"><i class="layui-icon layui-icon-close"></i></a>
                            </dd>
                            <?php endforeach;?>
                            <dd>
                                <a class="add-rss" data-opml-uuid="<?= $eachOPML["opml"]["uuid"] ?>" href="javascript:;">
                                    <i class="layui-icon layui-icon-add-circle"></i>
                                    新增订阅
                                </a>
                            </dd>
                        </dl>
                    </li>
                <?php endforeach;?>
                <li class="layui-nav-item" id="add-opml-category"><a href=""><i class="layui-icon layui-icon-add-circle" style="font-size: 20px; margin-bottom: -5px; margin-right: 10px;"></i>新增分类</a></li>
            </ul>
        </div>
    </div>

    <div id="body" class="layui-body">
        <!-- 内容主体区域 -->
        <div style="padding: 30px 20px; max-width: 600px; left: 0; right: 0; margin: auto;">
            <?= $this->renderSection("user_home_page") ?>
        </div>
    </div>

    <div id="footer" class="layui-footer">
        OPMLHub © 2020 | GitHub
    </div>

    <div id="opml-share" style="display: none;">
        <span style="display: block; text-align: center; margin-bottom: 10px; color: #1170b7;">点击确定复制到剪贴板</span>
        <form class="layui-form">
            <div class="layui-form-item">
                <label class="layui-form-label">OPML链接</label>
                <div class="layui-input-block">
                    <input type="text" disabled="disabled" value="" autocomplete="off" class="layui-input">
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section("footer-script") ?>
<script>
    window.Clipboard = (function(window, document, navigator) {
        var textArea,
            copy;

        // 判断是不是ios端
        function isOS() {
            return navigator.userAgent.match(/ipad|iphone/i);
        }
        //创建文本元素
        function createTextArea(text) {
            textArea = document.createElement('textArea');
            textArea.value = text;
            document.body.appendChild(textArea);
        }
        //选择内容
        function selectText() {
            var range,
                selection;

            if (isOS()) {
                range = document.createRange();
                range.selectNodeContents(textArea);
                selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
                textArea.setSelectionRange(0, 999999);
            } else {
                textArea.select();
            }
        }

//复制到剪贴板
        function copyToClipboard() {
            try{
                if(document.execCommand("Copy")){
                    alert("复制成功！");
                }else{
                    alert("复制失败！请手动复制！");
                }
            }catch(err){
                alert("复制错误！请手动复制！")
            }
            document.body.removeChild(textArea);
        }

        copy = function(text) {
            createTextArea(text);
            selectText();
            copyToClipboard();
        };

        return {
            copy: copy
        };
    })(window, document, navigator);

    layui.use(["element", "jquery", "form", "layer"], function(){
        var element = layui.element;
        var layer = layui.layer;
        var $ = layui.jquery;

        $(".opml-title span").click(function (event) {
            event.stopPropagation();
            console.log("open settings panel for " + event.currentTarget.dataset.opmlUuid);
            location.href = "/user/home?module=index&page=opml&uuid=" + event.currentTarget.dataset.opmlUuid;
        });

        $(".opml-title i").click(function (event) {
            event.stopPropagation();
            console.log("display opml link for " + event.currentTarget.dataset.opmlUuid);

            layer.open({
                type: 0,
                offset: 'auto',
                area: '500px',
                content: $("#opml-share").html(),
                success: function() {
                    $(".layui-layer-content input").val(location.href.match(/^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/i)[0] + "opml?uuid=" + event.currentTarget.dataset.opmlUuid);
                },
                yes: function() {
                    Clipboard.copy($(".layui-layer-content input").val());
                }
            });
        });

        $(".rss-list-item").click(function (event) {
            event.stopPropagation();
            console.log("open settings panel for " + event.currentTarget.dataset.rssUuid);
            location.href = "/user/home?module=index&page=rss&uuid=" + event.currentTarget.dataset.rssUuid;
        });

        $(".delete-rss").click(function (event) {
            event.stopPropagation();
            console.log("delete rss for " + event.currentTarget.dataset.rssUuid);
            layer.confirm("确定要删除吗？", {btn: ["确定", "取消"]}, function () {
                location.href = "/opml/delete?type=rss&uuid=" + event.currentTarget.dataset.rssUuid;
            }, function () {

            })
        });

        $("#button-delete-opml").click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            layer.confirm("确定要删除吗？", {btn: ["确定", "取消"]}, function () {
                location.href = "/opml/delete?type=opml&uuid=" + event.currentTarget.dataset.opmlUuid;
            }, function () {

            });
        });

        $("#add-opml-category").click(function (event) {
            event.preventDefault();
            location.href = "/user/home?module=index&page=opml";
        });

        $(".add-rss").click(function (event) {
            event.preventDefault();
            location.href = "/user/home?module=index&page=rss&opml=" + event.currentTarget.dataset.opmlUuid;
        })
    });
</script>
<?= $this->endSection() ?>
