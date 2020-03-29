<?= $this->extend("layout/login_register") ?>

<?= $this->section("login_register_form") ?>

    <form class="layui-form" action="/user/login" method="post">
        <div class="layui-form-item form-input">
            <label class="layui-form-label login">邮箱</label>
            <div class="layui-input-block login">
                <input type="email" name="email" required  lay-verify="required" placeholder="请输入电子邮箱地址" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item form-input">
            <label class="layui-form-label login">密码</label>
            <div class="layui-input-block login">
                <input type="password" name="password" required  lay-verify="required" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block button-center">
                <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="formDemo">登录</button>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block button-center">
                <button type="reset" class="layui-btn layui-btn-primary layui-btn-fluid" onclick="location.href='/user/register'">注册</button>
            </div>
        </div>

        <a class="form-link" href="/user/reset">登录遇到问题？</a>
    </form>

<?= $this->endSection() ?>
