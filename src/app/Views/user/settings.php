<?= $this->extend("layout/user_home") ?>


<?= $this->section("user_home_page") ?>
<h1>高级设置</h1>
<hr />
<form class="layui-form" style="margin-top: 45px;" action="/user/update?type=settings" method="post">

    <h2 style="margin: 10px; margin-top: 0;">拼音排序</h2>

    <div class="layui-form-item">
        <label class="layui-form-label">OPML列表</label>
        <div class="layui-input-inline" style="width: 60px;">
            <input type="checkbox" value="true" name="opml_order_pinyin" <?php if (isset($UserOptions["opml_order_pinyin"]) && $UserOptions["opml_order_pinyin"]) echo 'checked="checked"'; ?> lay-skin="switch">
        </div>
        <div class="layui-form-mid layui-word-aux">拼音A-Z排序，关闭后按新->旧排序</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">RSS列表</label>
        <div class="layui-input-inline" style="width: 60px;">
            <input type="checkbox" value="true" name="rss_order_pinyin" <?php if (isset($UserOptions["rss_order_pinyin"]) && $UserOptions["rss_order_pinyin"]) echo 'checked="checked"'; ?> lay-skin="switch">
        </div>
        <div class="layui-form-mid layui-word-aux">拼音A-Z排序，关闭后按新->旧排序</div>
    </div>


    <h2 style="margin: 10px; margin-top: 40px;">OPML设置</h2>

    <div class="layui-form-item">
        <label class="layui-form-label">保留XML头</label>
        <div class="layui-input-inline" style="width: 60px;">
            <input type="checkbox" value="true" name="xml_header" <?php if (isset($UserOptions["xml_header"]) && $UserOptions["xml_header"]) echo 'checked="checked"'; ?> lay-skin="switch">
        </div>
        <div class="layui-form-mid layui-word-aux">兼容性需要，不确定请保持默认</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">包含Email</label>
        <div class="layui-input-inline" style="width: 60px;">
            <input type="checkbox" value="true" name="email_in_opml" <?php if (isset($UserOptions["email_in_opml"]) && $UserOptions["email_in_opml"]) echo 'checked="checked"'; ?> lay-skin="switch">
        </div>
        <div class="layui-form-mid layui-word-aux">关闭此选项以在OPML里隐藏Email地址</div>
    </div>


    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">提交修改</button>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
