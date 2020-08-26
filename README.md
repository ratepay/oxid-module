# RatePAY GmbH - OXID Payment Module
============================================

|Module | RatePAY Payment Module for OXID
|------|----------
|Author | `Annegret Seufert`, `Tim Bischoff`, `Fatchip GmbH`
|Prefix | `pi`
|Shop Version | `CE/PE` `4.7.x-6.1.x` `EE` `5.0.x-6.1.x`
|Version | `5.0.8`
|Link | http://www.ratepay.com
|Mail | integration@ratepay.com
|Installation | https://ratepay.gitbook.io/oxid/
|Terms of service / Nutzungsbedingungen | http://www.ratepay.com/nutzungsbedingungen
|Legal Disclaimer|https://ratepay.gitbook.io/docs/#legal-disclaimer

## Changelog

### Version 5.0.8 - Released 2020-08-26
* Fixed : add check to prevent loading a log if none got selected (on first time
* Fixed : add session data cleaning process after successful order placement
* Fixed : add check to accept alphanumeric format for NL IBAN
* Fixed : bug in database migration steps
* Fixed : removed NL lang files, as this translation is not fully supported
* Fixed : Adjust shipping cost fetching to use brutto value during payment placement
* Update : renaming and rebranding
* Update : add step on activation to update database existing values

### Version 5.0.7 - Released 2020-06-23
* Fixed Trusted Shops issues
* Fixed problem with payment surcharges
* Fixed ordernr missing for DirectDebit payment confirmation
* Update : checkout (mandatory agreement checkbox, no more "classical bank data" option, only IBAN)
* Update : missing translation (EN, NL), update for DE
* docs: add license file

### Version 5.0.6 - Released 2020-03-12
* Fixed problems with individual items in checkout
* Fixed problems in order-ratepay tab in backend
* Fixed order post-handling for new data handling
* Add the transmission of invoice number in request, when available
* Update log entries to display more information from the response (codes, messages)
* Rate payment : Added choice of payment mode (transfer/debit) directly to checkout form
* Visual improvement (error messages, translations, legal text)

### Version 5.0.5 - Released 2019-12-17
* Template updated to remove a wrong price displayed
* Fixed backward compatibility problems
* Link style in payment page reworked
* Fixed the currency misuse for order in CHF
* Fixed missing order number in payment request

### Version 5.0.4 - Released 2019-09-30
* Refactored payment process to use OXIDs executePayment routine
* Extended update routine to work with all previous database layouts
* Fixed character set of OXID column in pi_ratepay_settings table
* Fixed problems with credit and vouchers in RatePAY order management in backend

### Version 5.0.3 - Released 2019-06-21
* Added default Device-Fingerprint Snippet id

### Version 5.0.2 - Released 2019-06-12
* Fixed a couple of problems with RatePay order management in backend
* Fixed problems in the backend profile configuration
* Fixed orders with differing addresses when ALA is enabled for configured profile
* Updated general terms text
* Changed device fingerprint mechanic
* Added config option for installment settlement
* Made buttons for installment settlement more obvious

### Version 5.0.1 - Released 2019-04-29
* Fixed problem in order tab

### Version 5.0.0 - Released 2019-04-17
* Added compatibility to PHP7
* Added compatibility to OXID6
* Refactored module to use more oxid standards
* solving installation problems

### Version 4.0.3 - Released 2018-05-04
* add compatibility for old rp order structure
* add privacy information

### Version 4.0.2 - Released 2018-02-10
* add optional auto confirm

### Version 4.0.1 - Released 2018-01-10
* fix multicountry backend process

### Version 4.0.0.1 - Released 2017-11-28
* address changes

### Version 4.0.0 - Released 2017-07-30
* change requests to the new ratepay library
* add payment method installment elv
* change installment calculator design

### Version 3.3.3.1 - Released 2017-11-28
* change RatePAY company address

### Version 3.3.3 - Released 2017-05-15
* compatibility for oxid version 4.10.2
* fix tooltip for retour at the admin panel
* only show possibl months ind the calculator
* send invoice information at the confirmation delivery request
* add extendet responses
* add b2b max limit configuration
* rate calculation without intermediate step

### Version 3.3.2 - Released 2017-03-02
* SEPA - BIC field removed
* IBAN country prefix validation removed

### Version 3.3.1 - Released 2016-08-09
* renaming of paymentmethods improved
* credit handling improved

### Version 3.3.0 - Released 2016-06-13
* Oxid EE compatibility implemented
* descriptor field in database, size changed from 20 to 128
* sandbox notification added
* address and Telephone validation added
* CH embedded
* conflict of SimpleXMLExtended class resolved
* DFP fixed
* NL embedded
* fixed credit issue
* fixed Trusted Shops issue
* currency and country-code added to DB
* fixed wrappingcosts issue
* fixed CDATA vouchercode/title issue
* same order id issue fixed
* missing order id issue fixed
* fixed price updating issue

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
