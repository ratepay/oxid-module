<?php
/**
 * Eventhandler for module activation and deactivation.
 */
class pi_ratepay_events
{
    public static $sQueryTableSettings = "
        CREATE TABLE IF NOT EXISTS `pi_ratepay_settings` (
          `OXID` INT(11) NOT NULL AUTO_INCREMENT,
          `SHOPID` INT(11) NOT NULL DEFAULT '1',
          `ACTIVE` TINYINT(1) NOT NULL DEFAULT '0',
          `COUNTRY` VARCHAR(2) NOT NULL,
          `PROFILE_ID` VARCHAR(255) DEFAULT NULL,
          `SECURITY_CODE` VARCHAR(255) DEFAULT NULL,
          `URL` VARCHAR(255) DEFAULT NULL,
          `SANDBOX` TINYINT(1) NOT NULL DEFAULT '1',
          `TYPE` VARCHAR(11) NOT NULL,
          `LIMIT_MIN` INT(4) NOT NULL DEFAULT '0',
          `LIMIT_MAX` INT(6) NOT NULL DEFAULT '0',
          `LIMIT_MAX_B2B` INT(6) NOT NULL DEFAULT '0',
          `MONTH_ALLOWED` VARCHAR(100) NOT NULL,
          `MIN_RATE` INT(5) NOT NULL DEFAULT '0',
          `INTEREST_RATE` FLOAT(5) NOT NULL DEFAULT '0',
          `PAYMENT_FIRSTDAY` VARCHAR(5) NOT NULL DEFAULT '0',
          `SAVEBANKDATA` TINYINT(1) NOT NULL DEFAULT '0',
          `ACTIVATE_ELV` TINYINT(1) NOT NULL DEFAULT '0',
          `B2B` TINYINT(1) NOT NULL DEFAULT '0',
          `ALA` TINYINT(1) NOT NULL DEFAULT '0',
          `IBAN_ONLY` TINYINT(1) NOT NULL DEFAULT '0',
          `DFP` TINYINT(1) NOT NULL DEFAULT '0',
          `DFP_SNIPPET_ID` VARCHAR(128) DEFAULT NULL,
          `CURRENCIES` varchar(50),
          `DELIVERY_COUNTRIES` varchar(50),
          PRIMARY KEY (`OXID`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

    public static $sQueryTableGlobalSettings = "
        CREATE TABLE `pi_ratepay_global_settings` (
         `SHOPID` INT(11) NOT NULL DEFAULT '1',
         `LOGGING` TINYINT(1) NOT NULL DEFAULT '1',
         `AUTOCONFIRM` TINYINT(1) NOT NULL DEFAULT '0',
         PRIMARY KEY (`SHOPID`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

    public static $sQueryTableOrders = "
        CREATE TABLE `pi_ratepay_orders` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `ORDER_NUMBER` varchar(32) character set latin1 collate latin1_general_ci NOT NULL,
          `TRANSACTION_ID` varchar(64) NOT NULL,
          `DESCRIPTOR` varchar(128) NOT NULL,
          `USERBIRTHDATE` DATE NOT NULL DEFAULT '0000-00-00',
          `RP_API` varchar(10) NULL,
          PRIMARY KEY  (`OXID`)
        ) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";

    public static $sQueryTableOrderDetails = "
        CREATE TABLE `pi_ratepay_order_details` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `ORDER_NUMBER` VARCHAR( 255 ) NOT NULL ,
          `ARTICLE_NUMBER` VARCHAR( 255 ) NOT NULL ,
          `UNIQUE_ARTICLE_NUMBER` VARCHAR( 255 ) NOT NULL ,
          `PRICE` DOUBLE NOT NULL DEFAULT '0',
          `VAT` DOUBLE NOT NULL DEFAULT '0',
          `ORDERED` INT NOT NULL DEFAULT '1',
          `SHIPPED` INT NOT NULL DEFAULT '0',
          `CANCELLED` INT NOT NULL DEFAULT '0',
          `RETURNED` INT NOT NULL DEFAULT '0',
           PRIMARY KEY  (`OXID`)
        ) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";

    public static $sQueryTableLogs = "
        CREATE TABLE IF NOT EXISTS `pi_ratepay_logs` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `ORDER_NUMBER` varchar(255) CHARACTER SET utf8 NOT NULL,
          `TRANSACTION_ID` varchar(255) CHARACTER SET utf8 NOT NULL,
          `PAYMENT_METHOD` varchar(40) CHARACTER SET utf8 NOT NULL,
          `PAYMENT_TYPE` varchar(40) CHARACTER SET utf8 NOT NULL,
          `PAYMENT_SUBTYPE` varchar(40) CHARACTER SET utf8 NOT NULL,
          `RESULT` varchar(40) CHARACTER SET utf8 NOT NULL,
          `REQUEST` mediumtext CHARACTER SET utf8 NOT NULL,
          `RESPONSE` mediumtext CHARACTER SET utf8 NOT NULL,
          `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `RESULT_CODE` varchar(5) CHARACTER SET utf8 NOT NULL,
          `FIRST_NAME` varchar(40) CHARACTER SET utf8 NOT NULL,
          `LAST_NAME` varchar(40) CHARACTER SET utf8 NOT NULL,
          `REASON` varchar(255) CHARACTER SET utf8 NOT NULL,
          PRIMARY KEY (`OXID`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

    public static $sQueryTableHistory = "
        CREATE TABLE `pi_ratepay_history` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `ORDER_NUMBER` VARCHAR( 255 ) NOT NULL ,
          `ARTICLE_NUMBER` VARCHAR (255) NOT NULL,
          `QUANTITY` INT NOT NULL,
          `METHOD` VARCHAR( 40 ) NOT NULL,
          `SUBMETHOD` VARCHAR( 40 ) DEFAULT '',
          `DATE` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
           PRIMARY KEY  (`OXID`)
        ) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";

    public static $sQueryTableRateDetails = "
        CREATE TABLE `pi_ratepay_rate_details` (
          `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
          `ORDERID` VARCHAR(255) NOT NULL ,
          `TOTALAMOUNT` DOUBLE NOT NULL ,
          `AMOUNT` DOUBLE NOT NULL ,
          `INTERESTAMOUNT` DOUBLE NOT NULL ,
          `SERVICECHARGE` DOUBLE NOT NULL ,
          `ANNUALPERCENTAGERATE` DOUBLE NOT NULL ,
          `MONTHLYDEBITINTEREST` DOUBLE NOT NULL ,
          `NUMBEROFRATES` DOUBLE NOT NULL ,
          `RATE` DOUBLE NOT NULL ,
          `LASTRATE` DOUBLE NOT NULL,
          `CHECKOUTTYPE` VARCHAR(255) DEFAULT '',
          `OWNER` VARCHAR(255) DEFAULT '',
          `BANKACCOUNTNUMBER` VARCHAR(255) DEFAULT '',
          `BANKCODE` VARCHAR(255) DEFAULT '',
          `BANKNAME` VARCHAR(255) DEFAULT '',
          `IBAN` VARCHAR(255) DEFAULT '',
          `BICSWIFT` VARCHAR(255) DEFAULT '',
          PRIMARY KEY  (`OXID`)
        ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";

    public static $sQueryTableDebitDetails = "
        CREATE TABLE `pi_ratepay_debit_details` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `userid` varchar(256) NOT NULL,
          `owner` blob NOT NULL,
          `accountnumber` blob NOT NULL,
          `bankcode` blob NOT NULL,
          `bankname` blob NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";

    public static $aPaymentMethods = array(
        'pi_ratepay_rechnung' => 'RatePAY Rechnung',
        'pi_ratepay_rate' => 'RatePAY Rate',
        'pi_ratepay_elv' => 'RatePAY SEPA-Lastschrift',
    );

    /**
     * Execute action on activate event.
     *
     * @return void
     */
    public static function onActivate()
    {
        self::addDatabaseStructure();
        self::addPayments();
        self::regenerateViews();
        self::clearTmp();
    }

    /**
     * Execute action on deactivate event.
     *
     * @return void
     */
    public static function onDeactivate()
    {
        self::deactivePaymethods();
        self::clearTmp();
    }

    /**
     * Regenerates database view-tables.
     *
     * @return void
     */
    public static function regenerateViews()
    {
        $oShop = oxNew('oxShop');
        $oShop->generateViews();
    }

    /**
     * Clear tmp dir and smarty cache.
     *
     * @return void
     */
    public static function clearTmp()
    {
        $sTmpDir = getShopBasePath() . "/tmp/";
        $sSmartyDir = $sTmpDir . "smarty/";

        foreach (glob($sTmpDir . "*.txt") as $sFileName) {
            unlink($sFileName);
        }
        foreach (glob($sSmartyDir . "*.php") as $sFileName) {
            unlink($sFileName);
        }
    }

    /**
     * Adding payments.
     *
     * @return void
     */
    public static function addPayments()
    {
        $oDb = oxDb::getDb();
        $oConfig = oxRegistry::getConfig();
        $sShopId = $oConfig->getShopId();

        foreach (self::$aPaymentMethods as $sPaymentOxid => $sPaymentName) {
            //INSERT PAYMENT METHOD
            self::insertRowIfNotExists('oxpayments', array('OXID' => $sPaymentOxid), "INSERT INTO oxpayments (OXID, OXACTIVE, OXDESC, OXADDSUM, OXADDSUMTYPE, OXFROMBONI, OXFROMAMOUNT, OXTOAMOUNT, OXVALDESC, OXCHECKED, OXDESC_1, OXVALDESC_1, OXDESC_2, OXVALDESC_2, OXDESC_3, OXVALDESC_3, OXLONGDESC, OXLONGDESC_1, OXLONGDESC_2, OXLONGDESC_3, OXSORT) VALUES ('{$sPaymentOxid}', 1, '{$sPaymentName}', 0, 'abs', 0, 0, 999999, '', 1, '{$sPaymentName}', '', '', '', '', '', '', '', '', '', 0)");
            self::insertRowIfNotExists('oxobject2payment', array('OXPAYMENTID' => $sPaymentOxid, 'OXTYPE' => 'oxdelset'), "INSERT INTO oxobject2payment(OXID,OXPAYMENTID,OXOBJECTID,OXTYPE) values (MD5(CONCAT(NOW(),RAND())), '{$sPaymentOxid}', 'oxidstandard', 'oxdelset');");
        }
    }

    /**
     * Creating database structure changes.
     *
     * @return void
     */
    public static function addDatabaseStructure()
    {
        self::addTableIfNotExists('pi_ratepay_settings', self::$sQueryTableSettings);
        self::addTableIfNotExists('pi_ratepay_global_settings', self::$sQueryTableGlobalSettings);
        self::addTableIfNotExists('pi_ratepay_orders', self::$sQueryTableOrders);
        self::addTableIfNotExists('pi_ratepay_order_details', self::$sQueryTableOrderDetails);
        self::addTableIfNotExists('pi_ratepay_logs', self::$sQueryTableLogs);
        self::addTableIfNotExists('pi_ratepay_history', self::$sQueryTableHistory);
        self::addTableIfNotExists('pi_ratepay_rate_details', self::$sQueryTableRateDetails);
        self::addTableIfNotExists('pi_ratepay_debit_details', self::$sQueryTableDebitDetails);
    }

    /**
     * Add a database table.
     *
     * @param string $sTableName table to add
     * @param string $sQuery     sql-query to add table
     *
     * @return boolean true or false
     */
    public static function addTableIfNotExists($sTableName, $sQuery)
    {
        $aTables = oxDb::getDb()->getAll("SHOW TABLES LIKE '{$sTableName}'");
        if (!$aTables || count($aTables) == 0) {
            oxDb::getDb()->Execute($sQuery);
            return true;
        }
        return false;
    }

    /**
     * Insert a database row to an existing table.
     *
     * @param string $sTableName database table name
     * @param array  $aKeyValue  keys of rows to add for existance check
     * @param string $sQuery     sql-query to insert data
     *
     * @return boolean true or false
     */
    public static function insertRowIfNotExists($sTableName, $aKeyValue, $sQuery)
    {
        $oDb = oxDb::getDb();

        $sWhere = '';
        foreach ($aKeyValue as $key => $value) {
            $sWhere .= " AND $key = '$value'";
        }

        $sCheckQuery = "SELECT * FROM {$sTableName} WHERE 1" . $sWhere;
        $mResult = $oDb->getOne($sCheckQuery);

        if ($mResult !== false) return false;
        $oDb->execute($sQuery);

        return true;
    }

    /**
     * Deactivates payone paymethods on module deactivation.
     *
     * @return void
     */
    public static function deactivePaymethods()
    {
        $sPaymenthodIds = "'" . implode("','", array_keys(self::$aPaymentMethods)) . "'";
        $sQ = "update oxpayments set oxactive = 0 where oxid in ($sPaymenthodIds)";
        oxDB::getDB()->Execute($sQ);
    }
}
