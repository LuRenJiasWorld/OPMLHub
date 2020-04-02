<html>
<head>
    <title><?= $PageTitle ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">

    <script src="/layui/layui.js"></script>
    <link rel="stylesheet" href="/layui/css/layui.css" />

    <style>
        html, body {
            margin: 0;
            font-family: Helvetica, Tahoma, Arial, "PingFang SC", "Hiragino Sans GB", "Heiti SC", "Microsoft YaHei", "WenQuanYi Micro Hei" !important;
        }
    </style>

    <script>
        // 定义全局函数
        function inUrlParameters(key, value) {
            return window.location.search.substr(1).split("&").includes(key + "=" + value);
        }

        function getUrlParameter(key) {
            var urlParamaters = window.location.search.substr(1).split("&");
            for (var i = 0; i < key.length; i++) {
                if (urlParamaters[i].startsWith(key + "=")) {
                    return urlParamaters[i].split("=")[1];
                }
            }
        }
    </script>

    <?= $this->renderSection("header-style") ?>
</head>
<body>
    <?= $this->renderSection("content") ?>
</body>

<?= $this->renderSection("footer-script") ?>
</html>