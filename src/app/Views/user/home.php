<?= $this->extend("layout/user_home") ?>

<?= $this->section("user_home_page") ?>
<div id="home-panel" class="layui-fluid">
    <div class="layui-col-md12">
        <h1 style="margin: 10px;">欢迎使用OPMLHub!</h1>
    </div>
    <hr />
    <div class="layui-col-md4">
        <div class="layui-card">
            <div class="layui-card-header">OPML总数</div>
            <div class="layui-card-body">
                <div class="bignumber">
                    <?= $OPMLCount ?>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-col-md4">
        <div class="layui-card">
            <div class="layui-card-header">RSS总数</div>
            <div class="layui-card-body">
                <div class="bignumber">
                    <?= $RSSCount ?>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-col-md4">
        <div class="layui-card">
            <div class="layui-card-header">OPML请求次数</div>
            <div class="layui-card-body">
                <div class="bignumber">
                    <?= $OPMLAccessCount ?>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-col-md12">
        <div class="layui-card">
            <div class="layui-card-header">登录历史</div>
            <div class="layui-card-body">
                <table class="layui-table" lay-even lay-skin="nob">
                    <thead>
                        <th style="width: 30%;">登录时间</th>
                        <th style="width: 30%;">登录IP</th>
                        <th style="width: 40%;">登录位置</th>
                    </thead>
                    <tbody>
                        <?php foreach ($LoginHistoryTop5 as $EachLoginHistory):?>
                            <tr>
                                <td><?= $EachLoginHistory["login_time"] ?></td>
                                <td><?= $EachLoginHistory["login_ip"] ?></td>
                                <td><?= $EachLoginHistory["login_location"] ?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="layui-col-md12">
        <div class="layui-card">
            <div class="layui-card-header">OPML请求历史</div>
            <div class="layui-card-body">
                <canvas id="opml-access-history" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
    #home-panel .layui-card {
        margin: 10px 10px;
    }

    .bignumber {
        font-size: 46px;
        font-weight: 200;
        margin: 20px;
        text-align: center;
        color: #009688;
    }
</style>

<script src="/chartjs/Chart.min.js"></script>
<script>
    function getLast7Days() {
        var date = new Date();
        var currentTimeStamp = parseInt(date.getTime() / 1000);
        var last7DaysTimeStamp = [
            currentTimeStamp - 86400 * 6,
            currentTimeStamp - 86400 * 5,
            currentTimeStamp - 86400 * 4,
            currentTimeStamp - 86400 * 3,
            currentTimeStamp - 86400 * 2,
            currentTimeStamp - 86400,
            currentTimeStamp
        ];

        var last7DaysDateString = [];

        for (var i = 0; i < last7DaysTimeStamp.length; i++) {
            var targetDate = new Date(last7DaysTimeStamp[i] * 1000);
            var year = targetDate.getFullYear();
            var month = "0" + (targetDate.getMonth() + 1);
            var day = "0" + targetDate.getDate();
            var formattedTime = year + '-' + month.substr(-2) + '-' + day.substr(-2);
            last7DaysDateString = last7DaysDateString.concat(formattedTime);
        }

        return last7DaysDateString;
    }

    var ctx = document.getElementById("opml-access-history");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: getLast7Days(),
            datasets: [
                {
                    data: [<?php foreach($OPMLAccessHistory7Days as $EachDay) echo $EachDay . ","; ?>],
                    label: "123"
                }
            ]
        },
        config: []
    });
</script>
<?= $this->endSection() ?>
