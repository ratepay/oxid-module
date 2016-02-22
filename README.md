# RatePAY GmbH - OXID Payment Module
============================================

|Module | RatePAY Payment Module for OXID
|------|----------
|Author | `Sebastian Neumann`
|Prefix | `pi`
|Shop Version | `CE` `4.7.x-4.9.x`
|Version | `3.3.0`
|Link | `http://www.ratepay.com`
|Mail | `integration@ratepay.com`
|Installation | `see separate installation manual`

## Changelog

### Version 3.3.0 -
* Oxid EE compatibility implemented
* added shopId to ratepay settings table
* fixed rate calculator bug in subshops

### Version 3.2.3 - Released 2015-10-01
* Device Fingerprint implemented
* SEPA form revised
* fixed AT ELV bug
* IBAN only configurable
* fixed bug in order history
* added order information table
* persisted non article items in order details table

### Version 3.2.2 - Released 2015-07-03
* fixed bug in case of item prices > 1000
* fixed bug in validation of max limit

### Version 3.2.1 - Released 2015-04-09
* fixed missing delivery cost
* fixed empty basket items (wrapping, delivery, tsprotection)
* added default item oxgiftcard
* added customer number in PAYMENT REQUEST
* fixed wrong discount tax calculation

### Version 3.2.0 - Released 2015-02-19
* compatibility with AT (CH ready) with different credentials via PR
* support of OXID 4.9.x while retaining support of OXID 4.7.x and 4.8.x
* bundled PI und PR
* simplified directory structure
* removed customer and payment block at payment changes
* fixed elv bank owner bug in case of latin1 db setting
* fixed bug in delivery address

### Version 3.1.4 - Released 2014-10-29
* new RatePAY Gateway URL

### Version 3.1.3 - Released 2014-05-20
* fixed bug in positive voucher price

### Version 3.1.2 - Released 2014-05-02
* fixed sandbox changes from 3.1.0
* extended Whitelabel mode
* removed agreement box (invoice/installment); not needed anymore

### Version 3.1.1 - Released 2014-02-06
* IBAN validation without JS

### Version 3.1.0 - Released 2014-01-31
* added SEPA functionality - includes IBAN and BIC fields, improved IBAN validation and new text blocks
* deactivated saving of user bank account data
* changes in sandbox mode - no decline of rp payment methods after negative response while sandbox mode

### Version 3.0.5 - Released 2013-12-02*
* fixed different basket item title (in CONFIRMATION_DELIVER & PAYMENT_CHANGE)
* fixed calculation of unit-price and tax in case of prices > 1000 (in CONFIRMATION_DELIVER & PAYMENT_CHANGE)

### Version 3.0.4 - Released 2013-11-07*
* fixed PC bug (in case of aborted orders)

### Version 3.0.3 - Released 2013-10-09*
* fixed request voucher bug

### Version 3.0.2 - Released 2013-07-19*
* additional fixes in the rate calculator

### Version 3.0.1 - Released 2013-07-16*
* few changes in the Ratenrechner
* cached installment configuration by one click config (profile request)

### Version 3.0.0 - Released 2013-07-02*
* new feature: one click configuration

### Version 2.5.0.4 - Released 2013-06-05
* changed deprecated methods to new core methods
* fixed bootstrap bug for ratecalculator

### Version 2.5.0.3 * Released 2013-05-27
* added new option for whitelabeling of payment methods in frontend view
* disabled rate for b2b

### Version 2.5.0.2 - Released 2012-11-06
* minor fix in encryption library: Upgrade if you encounter problems with ELV.

### Version 2.5.0.1 - Released 2012-10-22
* Modified encryption library to fix utf-8 encoding problems while saving bank-
  data.
  IMPORTANT: Old bank data table must be backuped and cleared before usage.
             DOES NOT WORK WITH OLD DATA.

### Version 2.5.0 - Released 2012-03-27*
* added new payment method RatePAY Lastschrift
* added ELV for RatePAY Rate
* added PayIntelligent Encryption Library v1.0.0
* added additional features
* improvement shortened checkout, policy page removed
* fixed [RPOX-46] - module not compatible with non-UTF8 OXID shops
* fixed [RPOX-41] - wrong order-id in backend requests
* updated RatePAY Ratenrechner to v1.0.3

### Version 2.0.1 - Released 2012-03-27*
* added new logo for RatePAY Installment invoice pdf

### Version 2.0.0 - Released 2012-03-26*
* added unit tests
* refactoring of view classes
* improvement - template snippets are now integrated with the oxid block feature (OXID 4.5.1 and above)
* Many under the hood improvements.

********| Version 1.3.0 RC1 - Released 2012-02-01
* fixed [RPOX-12] - telephone number or birthdate only saved if both telephone number and birthdate is given
* fixed [RPOX-13] - order id not shown correctly in log view
* fixed [RPOX-17] - two digit birthdate converts to wrong date (56 to 2056)
* improvement [RPOX-2] - Error Message if request to RatePAY server timed out
* Many under the hood improvements.

### Version 1.2.1 - Released 2012-01-04
* fixed (0000467) - send oxordernr of order as 'order-id' not oxid

### Version 1.2.0 - Released 2011-12-27
* fixed (0000448) - two times delivery costs on merchant voucher [backend]
* fixed (0000447) - canceled article still visible in pdf invoice [backend]
* fixed (0000446) - items not shown in retoure view [backend]
* fixed (0000445) - RatePAY backend view did not update on changes (cancellation, shipment etc.) [backend]
* fixed (0000443) - voucher had always label "RatePAY-Gutschein" [backend]
* fixed (0000442) - voucher not shown in history after full cancellation [backend]
* fixed (0000441) - voucher not shown after full cancellation [backend]
* fixed (0000440) - vouchers and delivery costs not shipped [backend]
* fixed (0000439) - delivery costs added on full cancellation [backend]
* fixed (0000438) - voucher can be added although it exceeds total price [backend]
* fixed (0000436) - full cancellation does not work [backend]
* fixed (0000435) - full shipment does not work [backend]
* fixed (0000434) - vat on vouchers [backend]
* fixed (0000431) - install.sql: error on first run [install]
* fixed double md5 hashing
* fixed installment on logging of thankyou
* removed deprecated agb check
* removed custom order tpls
* removed thankyou.tpl
* changed payment method setting: 'purchase price' to default to from: 200 to 2000 for Rate and from: 20 to 1500 for Rechnung
* changed (0000449) "RatePAY-Gutschein" to "Anbieter Gutschrift"
* changed more meaningful error message if user forgets to insert vat-id or company name (applies only if company name or vat-id is set, and the other is forgotten)

### Version 1.1.2 - Released 2011-12-02
* changed getModulePath() to relative Paths
* fixed URL for RatePAY Testing Server
* fixed Logging for failed INIT,
* fixed Wiederrufsrecht URL on Basic Theme
* removed md5 Hash of Security Code

### Version 1.1.1 - Released 2011-12-01
* changed Logging
* added Loggingcolumns

### Version 1.1.0 - Released 2011-11-23
* RatePAY Rechnung and RatePAY Rate now as one module
* new RatePAY Rate Calculator
* changed RatePAY Rate Invoice PDF