<?= $this->extend("layout/login_register") ?>

<?= $this->section("login_register_form") ?>

<form class="layui-form" action="/user/register" method="post">
    <div class="layui-form-item form-input">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-block">
            <input type="email" name="email" required lay-verify="required" placeholder="请输入电子邮箱地址" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item form-input">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-block">
            <input type="password" name="password" minlength="6" maxlength="16" required lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item form-input">
        <label class="layui-form-label">确认密码</label>
        <div class="layui-input-block">
            <input type="password" name="password_again" minlength="6" maxlength="16" required lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block button-center">
            <button type="submit" lay-submit class="layui-btn layui-btn-fluid">注册</button>
        </div>
    </div>

    <a class="form-link" href="/user/login">已有账号？点击登录</a>
</form>

<?= $this->endSection() ?>
