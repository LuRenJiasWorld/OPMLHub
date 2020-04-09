<?php namespace App\Controllers;

class Config extends BaseController {
    function __construct() {
        parent::__construct();

        $configList = [
            "APP_ContactEmail",
            "SMTP_Host", "SMTP_Port", "SMTP_User", "SMTP_Pass", "SMTP_Name",
            "DB_Host", "DB_Port", "DB_User", "DB_Pass", "DB_Database",
            "SiteURL", "GatewayIP"
        ];
        foreach ($configList as $each) {
            if (isEmpty(Config::$$each) && !isEmpty(getenv($each))) {
                Config::$$each = getenv($each);
            }
        }
    }

    public static $APP_ContactEmail = "";

    public static $SMTP_Host = "";
    public static $SMTP_Port = "";
    public static $SMTP_Encryption = "";
    public static $SMTP_User = "";
    public static $SMTP_Pass = "";
    public static $SMTP_Name = "";

    public static $DB_Host = "";
    public static $DB_Port = "";
    public static $DB_User = "";
    public static $DB_Pass = "";
    public static $DB_Database = "";

    public static $SiteURL = "";
    public static $GatewayIP = [];
}