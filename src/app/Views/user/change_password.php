<?= $this->extend("layout/user_home") ?>


<?= $this->section("user_home_page") ?>
<h1>修改密码</h1>
<hr />
<form class="layui-form" style="margin-top: 45px;" action="/user/update?type=password" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">旧密码<i class="required">*</i></label>
        <div class="layui-input-block">
            <input type="password" name="old_password" required lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">新密码<i class="required">*</i></label>
        <div class="layui-input-block">
            <input type="password" name="new_password" minlength="6" maxlength="16" required lay-verify="required" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">确认密码<i class="required">*</i></label>
        <div class="layui-input-block">
            <input type="password" name="new_password_again" minlength="6" maxlength="16" required lay-verify="required" autocomplete="off" class="layui-input">
            <div class="layui-form-mid layui-word-aux">新密码长度需在6~16字符间，且至少包含字母和数字</div>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">提交修改</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
