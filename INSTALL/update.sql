-- Update Script, updates DB RatePAY OXID Module to >=3.2 DB
DROP TABLE `pi_ratepay_rate_configuration`;
DROP TABLE `pi_ratepay_settings`;

CREATE TABLE IF NOT EXISTS `pi_ratepay_settings` (
  `OXID` INT(11) NOT NULL AUTO_INCREMENT,
  `ACTIVE` TINYINT(1) NOT NULL DEFAULT '0',
  `COUNTRY` VARCHAR(2) NOT NULL,
  `PROFILE_ID` VARCHAR(255) DEFAULT NULL,
  `SECURITY_CODE` VARCHAR(255) DEFAULT NULL,
  `URL` VARCHAR(255) DEFAULT NULL,
  `SANDBOX` TINYINT(1) NOT NULL DEFAULT '1',
  `LOGGING` TINYINT(1) NOT NULL DEFAULT '1',
  `TYPE` VARCHAR(11) NOT NULL,
  `LIMIT_MIN` INT(4) NOT NULL DEFAULT '0',
  `LIMIT_MAX` INT(6) NOT NULL DEFAULT '0',
  `MONTH_ALLOWED` VARCHAR(100) NOT NULL,
  `PAYMENT_FIRSTDAY` TINYINT(1) NOT NULL DEFAULT '0',
  `DUEDATE` INT(11) NOT NULL DEFAULT '14',
  `SAVEBANKDATA` TINYINT(1) NOT NULL DEFAULT '0',
  `ACTIVATE_ELV` TINYINT(1) NOT NULL DEFAULT '0',
  `WHITELABEL` TINYINT(1) NOT NULL DEFAULT '0',
  `B2B` TINYINT(1) NOT NULL DEFAULT '0',
  `ALA` TINYINT(1) NOT NULL DEFAULT '0',
  `IBAN_ONLY` TINYINT(1) NOT NULL DEFAULT '0',
  `DFP` TINYINT(1) NOT NULL DEFAULT '0',
  `DFP_SNIPPET_ID` VARCHAR(128) NULL DEFAULT NULL

  PRIMARY KEY (`OXID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `pi_ratepay_settings` (`country`, `url`, `sandbox`, `logging`, `type`, `duedate`) VALUES ('DE', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', 1, 1, 'invoice', 14);
INSERT INTO `pi_ratepay_settings` (`country`, `url`, `sandbox`, `logging`, `type`, `duedate`) VALUES ('DE', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', 1, 1, 'elv', 14);
INSERT INTO `pi_ratepay_settings` (`country`, `url`, `sandbox`, `logging`, `type`, `duedate`) VALUES ('DE', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', 1, 1, 'installment', 14);
INSERT INTO `pi_ratepay_settings` (`country`, `url`, `sandbox`, `logging`, `type`, `duedate`) VALUES ('AT', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', 1, 1, 'invoice', 14);
INSERT INTO `pi_ratepay_settings` (`country`, `url`, `sandbox`, `logging`, `type`, `duedate`, `iban_only`) VALUES ('AT', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', 1, 1, 'elv', 14, 0);
INSERT INTO `pi_ratepay_settings` (`country`, `url`, `sandbox`, `logging`, `type`, `duedate`) VALUES ('AT', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', 1, 1, 'installment', 14);
INSERT INTO `pi_ratepay_settings` (`country`, `url`, `sandbox`, `logging`, `type`, `duedate`) VALUES ('CH', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', 1, 1, 'invoice', 14);
INSERT INTO `pi_ratepay_settings` (`country`, `url`, `sandbox`, `logging`, `type`, `duedate`, `iban_only`) VALUES ('CH', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', 1, 1, 'elv', 14, 0);
INSERT INTO `pi_ratepay_settings` (`country`, `url`, `sandbox`, `logging`, `type`, `duedate`) VALUES ('CH', 'http://www.ratepay.com/zusaetzliche-geschaeftsbedingungen-und-datenschutzhinweis', 1, 1, 'installment', 14);

UPDATE `oxpayments` SET `OXACTIVE` = 1, `OXFROMAMOUNT` = 0, `OXTOAMOUNT` = 999999999, OXCHECKED = 1 WHERE `oxpayments`.`OXID` IN ('pi_ratepay_rechnung', 'pi_ratepay_rate', 'pi_ratepay_elv');