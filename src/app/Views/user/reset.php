<?= $this->extend("layout/login_register") ?>

<?= $this->section("login_register_form") ?>

<form class="layui-form" action="">
    <div class="layui-form-item form-input">
        <label class="layui-form-label login">邮箱</label>
        <div class="layui-input-block login">
            <input type="email" name="email" required  lay-verify="required" placeholder="请输入电子邮箱地址" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block button-center">
            <button type="reset" class="layui-btn layui-btn-fluid">重置</button>
        </div>
    </div>

    <a class="form-link" href="/user/login">想起密码了吗？点击登录</a>
</form>

<?= $this->endSection() ?>
