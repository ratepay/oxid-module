-- Update Script, updates 2.0.1.x DB RatePAY OXID Module to 2.5.0.3 (Whitelabel) DB

ALTER TABLE `pi_ratepay_settings`
  ADD `WHITELABEL` TINYINT( 1 ) NOT NULL DEFAULT '0';
