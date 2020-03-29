<?= $this->extend("layout/user_home") ?>

<?= $this->section("user_home_page") ?>
    <h1><?= isset($currentData["title"]) ? $currentData["title"] . " - OPML编辑" : "新建OPML" ?></h1>
    <hr />
    <form class="layui-form layui-form-pane" style="margin-top: 45px;" action="/opml/update?type=opml<?= isset($currentData['uuid']) ? "&uuid=" . $currentData['uuid'] : "" ?>" method="post">
        <div class="layui-form-item" pane>
            <label class="layui-form-label">UUID<i class="required">*</i></label>
            <div class="layui-input-block">
                <input type="text" name="uuid" disabled="disabled" required value="<?= isset($currentData['uuid']) ? $currentData['uuid'] : "自动生成" ?>" lay-verify="required" placeholder="请输入UUID" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item" pane>
            <label class="layui-form-label">名称<i class="required">*</i></label>
            <div class="layui-input-block">
                <input type="text" name="title" required value="<?= isset($currentData['title']) ? $currentData['title'] : "" ?>" lay-verify="required" placeholder="请输入名称" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn">提交修改</button>
                <?php
                    if (isset($currentData["uuid"])) {
                        echo '<button class="layui-btn layui-btn-danger" id="button-delete-opml" data-opml-uuid="' . $currentData['uuid'] . '">删除此OPML</button>';
                    }
                ?>
            </div>
        </div>
    </form>
<?= $this->endSection() ?>
