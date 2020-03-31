<?= $this->extend("layout/user_home") ?>


<?= $this->section("user_home_page") ?>
<h1>高级设置</h1>
<hr />
<form class="layui-form" style="margin-top: 45px;" action="/user/password" method="post">

    <h2>拼音排序</h2>

    <div class="layui-form-item">
        <label class="layui-form-label">OPML列表</label>
        <div class="layui-input-block">
            <input type="checkbox" name="switch" lay-skin="switch">
        </div>
        <div class="layui-form-mid layui-word-aux">拼音A-Z排序</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">RSS列表</label>
        <div class="layui-input-block">
            <input type="checkbox" name="switch" lay-skin="switch">
        </div>
        <div class="layui-form-mid layui-word-aux">拼音A-Z排序</div>
    </div>

    <h2>OPML设置</h2>

    <div class="layui-form-item">
        <label class="layui-form-label">保留XML头</label>
        <div class="layui-input-block">
            <input type="checkbox" name="switch" lay-skin="switch">
        </div>
        <div class="layui-form-mid layui-word-aux">兼容性需要，不确定请保持默认</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">包含Email</label>
        <div class="layui-input-block">
            <input type="checkbox" name="switch" lay-skin="switch">
        </div>
        <div class="layui-form-mid layui-word-aux">如果需要在OPML里隐藏Email地址，可以关闭此选项</div>
    </div>


    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">提交修改</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
