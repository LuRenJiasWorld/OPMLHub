<?= $this->extend("layout/user_home") ?>

<?= $this->section("user_home_page") ?>
    <h1><?= isset($currentData["feed_name"]) ? $currentData["feed_name"] . " - RSS编辑" : "新建RSS" ?></h1>
    <hr />
    <form class="layui-form layui-form-pane" style="margin-top: 45px;" action="/opml/update?type=rss<?= isset($currentData["uuid"]) ? "&uuid=" . $currentData["uuid"] : "" ?>" method="post">
        <div class="layui-form-item" pane>
            <label class="layui-form-label">UUID<i class="required">*</i></label>
            <div class="layui-input-block">
                <input type="text" name="uuid" required disabled="disabled" value="<?= isset($currentData["uuid"]) ? $currentData["uuid"] : "自动生成" ?>" lay-verify="required" placeholder="请输入UUID" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item" pane>
            <label class="layui-form-label">归属分类<i class="required">*</i></label>
            <div class="layui-input-block">
                <select name="opml_uuid" lay-verify="required">
                    <?php foreach ($opml as $eachOPML):?>
                        <option <?php if((isset($currentData) && $eachOPML["opml"]["uuid"] == $currentData["opml_uuid"]) || isset($OpmlUuid) && $eachOPML["opml"]["uuid"] == $OpmlUuid) echo "selected='selected'"; ?> value="<?= $eachOPML["opml"]["uuid"] ?>"><?= $eachOPML["opml"]["title"] ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>

        <div class="layui-form-item" pane>
            <label class="layui-form-label">RSS名称<i class="required">*</i></label>
            <div class="layui-input-block">
                <input type="text" name="feed_name" required lay-verify="required" value="<?= isset($currentData["feed_name"]) ? $currentData["feed_name"] : "" ?>" placeholder="请输入RSS名称" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item" pane>
            <label class="layui-form-label">RSS备注</label>
            <div class="layui-input-block">
                <input type="text" name="feed_comment" value="<?= isset($currentData["feed_comment"]) ? $currentData["feed_comment"] : "" ?>" placeholder="请输入RSS备注" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item" pane>
            <label class="layui-form-label">RSS地址<i class="required">*</i></label>
            <div class="layui-input-block">
                <input type="text" name="feed_url" required lay-verify="required" value="<?= isset($currentData["feed_url"]) ? $currentData["feed_url"] : "" ?>" placeholder="请输入RSS订阅地址" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item" pane>
            <label class="layui-form-label">站点地址</label>
            <div class="layui-input-block">
                <input type="text" name="website_url" value="<?= isset($currentData["website_url"]) ? $currentData["website_url"] : "" ?>" placeholder="请输入站点地址" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="formDemo">提交修改</button>
            </div>
        </div>
    </form>
<?= $this->endSection() ?>
