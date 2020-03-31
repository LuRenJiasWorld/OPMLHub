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
        font-weight: 200;
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
        padding-right: 12px;
        padding-left: 8px;
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
    .layui-layout-admin .layui-side-scroll ul,
    .layui-layout-admin .layui-logo {
        width: 260px !important;
    }

    .layui-body,
    .layui-layout-left,
    .layui-layout-admin .layui-footer {
        left: 260px !important;
    }

    /* 鼠标悬浮样式美化 */
    .layui-nav-tree .layui-nav-item > a:hover, .layui-nav-tree .layui-nav-item dd:hover {
        background-color: #159688 !important;
    }

    .layui-nav-item i {
        margin-right: 4px;
    }

    span.layui-nav-bar {
        display: none !important;
    }

    .required {
        color: red;
    }

    #footer svg {
        width: 16px;
        display: inline-block;
        vertical-align: middle;
        opacity: 0.8;
    }

    #footer > span {
        display: flex;
        justify-content: space-between;
        padding: 0 10px;
    }

    img#opmlhub-logo {
        width: 30px;
        display: inline-block;
        vertical-align: sub;
        margin-right: 8px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div id="app" class="layui-layout layui-layout-admin">
    <div id="header" class="layui-header">
        <div class="layui-logo"><a href="/"><img id="opmlhub-logo" src="/assets/icon/icon.svg" alt="opmlhub" />OPMLHub</a></div>
        <!-- 头部区域（可配合layui已有的水平导航） -->
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item" id="tab-index"><a href="/user/home?module=index&page=index"><i class="layui-icon layui-icon-rss"></i>订阅配置</a></li>
            <li class="layui-nav-item" id="tab-settings"><a href="/user/home?module=settings&page=index"><i class="layui-icon layui-icon-slider"></i>高级设置</a></li>
        </ul>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item" id="tab-password"><a href="/user/home?module=password&page=index"><i class="layui-icon layui-icon-password"></i>修改密码</a></li>
            <li class="layui-nav-item"><a href="/user/logout"><i class="layui-icon layui-icon-logout"></i>退出</a></li>
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
        <span>
            <span class="left">OPMLHub © 2020</span>
            <a class="right" target="_blank" href="https://github.com/LuRenJiasWorld/OPMLHub">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                GitHub
            </a>
        </span>
    </div>

    <div id="opml-share" style="display: none;">
        <form class="layui-form" style="margin: 20px 10px 0;">
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
    // Clipboard
    window.Clipboard=(function(window,document,navigator){var textArea,copy;function isOS(){return navigator.userAgent.match(/ipad|iphone/i)}function createTextArea(text){textArea=document.createElement('textArea');textArea.value=text;document.body.appendChild(textArea)}function selectText(){var range,selection;if(isOS()){range=document.createRange();range.selectNodeContents(textArea);selection=window.getSelection();selection.removeAllRanges();selection.addRange(range);textArea.setSelectionRange(0,999999)}else{textArea.select()}}function copyToClipboard(){try{if(document.execCommand("Copy")){}else{alert("复制失败！请手动复制！")}}catch(err){alert("复制错误！请手动复制！")}document.body.removeChild(textArea)}copy=function(text){createTextArea(text);selectText();copyToClipboard()};return{copy:copy}})(window,document,navigator);

    layui.use(["element", "jquery", "form", "layer"], function(){
        var element = layui.element;
        var layer = layui.layer;
        var $ = layui.jquery;

        function inUrlParameters(key, value) {
            return window.location.search.substr(1).split("&").includes(key + "=" + value);
        }

        $(window).ready(function () {
            if (inUrlParameters("module", "index"))    $("#tab-index").addClass("layui-this");
            if (inUrlParameters("module", "settings")) $("#tab-settings").addClass("layui-this");
            if (inUrlParameters("module", "password")) $("#tab-password").addClass("layui-this");
        });

        $(".opml-title span").click(function (event) {
            event.stopPropagation();
            location.href = "/user/home?module=index&page=opml&uuid=" + event.currentTarget.dataset.opmlUuid;
        });

        $(".opml-title i").click(function (event) {
            event.stopPropagation();

            layer.open({
                type: 0,
                offset: 'auto',
                area: '660px',
                content: $("#opml-share").html(),
                btn: ["复制到剪贴板", "在新标签页打开"],
                success: function() {
                    $(".layui-layer-content input").val(location.href.match(/^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/i)[0] + "opml/" + event.currentTarget.dataset.opmlUuid);
                },
                yes: function() {
                    Clipboard.copy($(".layui-layer-content input").val());
                    layer.msg("复制成功！");
                },
                btn2: function() {
                    window.open($(".layui-layer-content input").val());
                    return false
                }
            });
        });

        $(".rss-list-item").click(function (event) {
            event.stopPropagation();
            location.href = "/user/home?module=index&page=rss&uuid=" + event.currentTarget.dataset.rssUuid;
        });

        $(".delete-rss").click(function (event) {
            event.stopPropagation();
            layer.confirm("确定要删除吗？", {btn: ["确定", "取消"]}, function () {
                location.href = "/opml/delete?type=rss&uuid=" + event.currentTarget.dataset.rssUuid;
            });
        });

        $("#button-delete-opml").click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            layer.confirm("确定要删除吗？", {btn: ["确定", "取消"]}, function () {
                location.href = "/opml/delete?type=opml&uuid=" + event.currentTarget.dataset.opmlUuid;
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
