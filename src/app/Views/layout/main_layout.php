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
        }
    </style>

    <?= $this->renderSection("header-style") ?>
</head>
<body>
    <?= $this->renderSection("content") ?>
</body>

<?= $this->renderSection("footer-script") ?>
</html>