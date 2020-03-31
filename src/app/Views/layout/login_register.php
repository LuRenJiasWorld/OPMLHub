<?= $this->extend("layout/main_layout") ?>

<?= $this->section("header-style") ?>
<style>
    body {
        background-color: #e9f2f9;
        background-image: url(/assets/img/login-background-image.png), url(/assets/img/login-background-pattern.png);
        background-size: 100%, 520px;
        background-repeat: no-repeat, repeat;
    }

    #app {
        max-width: 400px;
        left: 0;
        right: 0;
        margin: auto;
    }

    #app #login-panel {
        width: 400px;
        margin: 100px 0 50px;
        border-radius: 10px;
        box-shadow: 0 2px 30px 0 hsla(0, 0%, 0%, 0.28);
        -webkit-box-reflect: below 4px linear-gradient(transparent, transparent 90%, #000);
    }

    #app #login-panel .layui-card-header {
        font-size: 25px;
        height: 80px;
        line-height: 80px;
        text-align: center;
        font-weight: 200;
    }

    #app #login-panel .layui-card-body {
        padding: 45px 20px 16px;
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
        color: #666666;
        margin-top: 18px;
        user-select: none;
    }

    #app #footer svg {
        width: 16px;
        display: inline-block;
        vertical-align: middle;
        opacity: 0.8;
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
<div id="app">
    <div id="login-panel" class="layui-card">
        <div class="layui-card-header">
            <img id="opmlhub-logo" src="/assets/icon/icon.svg" alt="opmlhub" />
            <?= $PageTitle ?>
        </div>
        <div class="layui-card-body">
            <?php if (isset($error)) { echo "<p style='color: red; text-align: center; font-size: 16px; margin-bottom: 30px;'>" . $error . "</p>"; } ?>
            <?= $this->renderSection("login_register_form") ?>
            <div id="footer">
                <span>OPMLHub © 2020
                    |
                    <a target="_blank" href="https://github.com/LuRenJiasWorld/OPMLHub">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        GitHub
                    </a>
                </span>
            </div>
        </div>
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

