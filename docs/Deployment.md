# OPMLHub部署文档

## 手动部署

> 支持最低PHP版本为7.2，需安装Composer

1. 执行`git clone https://github.com/LuRenJiasWorld/OPMLHub.git`

2. `cd src`

3. `composer install` 安装所需依赖

4. 修改`src/app/Controllers/Config.php`中的各项配置

   | 配置项            | 含义                                                         | 必填 |
   | ----------------- | ------------------------------------------------------------ | ---- |
   | $APP_ContactEmail | 联系邮箱，可让你的用户在遇到问题后使用该邮箱联系你           | 否   |
   | $SMTP_Host        | 发送邮件的SMTP服务器主机                                     | 是   |
   | $SMTP_Port        | 发送邮件的SMTP服务器端口                                     | 是   |
   | $SMTP_Encryption  | SMTP服务器加密模式（tls/ssl）                                | 是   |
   | $SMTP_User        | 发送邮件的用户名                                             | 是   |
   | $SMTP_Pass        | 发送邮件用户密码                                             | 是   |
   | $SMTP_Name        | 显示在收件人邮件中的发件人                                   | 是   |
   | $DB_Host          | 数据库主机                                                   | 是   |
   | $DB_Port          | 数据库端口                                                   | 是   |
   | $DB_User          | 数据库用户                                                   | 是   |
   | $DB_Pass          | 数据库密码                                                   | 是   |
   | $DB_Database      | 数据库名称                                                   | 是   |
   | $SiteURL          | 站点URL，格式需要为`http://example.com`，如果端口非80或443需要额外指定，不配置无法正常启动！ | 是   |
   | $GatewayIP        | 网关服务器IP，用于在反向代理情况下正常识别用户IP             | 是   |

5. 上传`sql/init.sql`到你的数据库中

6. 将`src`目录中的文件拷贝到你的Web服务器根目录，并配置相关权限

## Docker部署

> 目前版本需要自备数据库，要求MySQL5.6版本及以上

1. 执行`docker pull opmlhub/opmlhub:latest`
2. 撰写如下配置文件，保存为`opmlhub.env`（各配置含义参考手动部署部分）：

```
# 联系邮箱（选填）
APP_ContactEmail=""

# 发信配置（必填）
SMTP_Host=""
SMTP_Port=""
SMTP_Encryption=""
SMTP_User=""
SMTP_Pass=""
SMTP_Name=""

# 数据库配置（必填）
DB_Host=""
DB_Port=""
DB_User=""
DB_Pass=""
DB_Database=""

# 系统关键配置（必填）
SiteURL=""

# 系统辅助配置（选填）
GatewayIP=""
```

3. 上传`sql/init.sql`到你的数据库中
4. 执行`docker run -d -p 任意端口:80 --env-file opmlhub.env opmlhub:latest`启动OPMLHub
5. 在第四步所指定的端口访问OPMLHub