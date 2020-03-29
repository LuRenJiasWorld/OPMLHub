<?= $this->extend("layout/main_layout") ?>

<?= $this->section("header-style") ?>
<style>
    body {
        background-color: #e5e5e5;
    }

    #app {
        max-width: 400px;
        left: 0;
        right: 0;
        margin: auto;
    }

    #app #login-panel {
        width: 400px;
        margin: 100px 0 30px;
        border-radius: 10px;
    }

    #app #login-panel .layui-card-header {
        font-size: 25px;
        height: 80px;
        line-height: 80px;
        text-align: center;
    }

    #app #login-panel .layui-card-body {
        padding: 30px 20px;
    }

    #app #login-panel .layui-form-item.form-input {
        margin-bottom: 30px;
    }

    #app #login-panel .layui-form-label {
        width: 60px;
    }

    #app #login-panel .layui-form-label.login {
        width: 40px;
    }

    #app #login-panel .layui-input-block {
        margin-left: 100px;
        margin-right: 40px;
    }
    #app #login-panel .layui-input-block.login {
        margin-left: 70px;
    }

    #app #login-panel .layui-input-block.button-center {
        margin: 0;
        padding: 0 40px;
    }
    
    #app #login-panel .form-link {
        text-align: center;
        display: block;
    }

    #app #footer {
        text-align: center;
        color: #444444;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div id="app">
    <div id="login-panel" class="layui-card">
        <div class="layui-card-header">
            <?= $PageTitle ?>
        </div>
        <div class="layui-card-body">
            <?php if (isset($error)) { echo "<p style='color: red; text-align: center; font-size: 16px; margin-bottom: 30px;'>" . $error . "</p>"; } ?>
            <?= $this->renderSection("login_register_form") ?>
        </div>
    </div>
    <div id="footer">
        <span>OPMLHub © 2020 | GitHub</span>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section("footer-script") ?>
<script>
    layui.use('form', function() {
        var form = layui.form;

    });
</script>
<?= $this->endSection() ?>

