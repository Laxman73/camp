CREATE TABLE `pur_gret` (
  `iGRETID` bigint(20) unsigned NOT NULL default '0',
  `iGRETNum` bigint(20) unsigned NOT NULL default '0',
  `iLocID` tinyint(3) unsigned NOT NULL default '0',
  `iGRID` bigint(20) unsigned NOT NULL default '0',
  `iGRNum` bigint(20) unsigned NOT NULL default '0',
  `dDor` date default NULL,
  `iVendorID` int(10) unsigned NOT NULL default '0',
  `iItems` bigint(20) unsigned NOT NULL default '0',
  `fBasicAmount` decimal(10,2) NOT NULL default '0.00',
  `fDisc_pre` decimal(10,2) NOT NULL default '0.00',
  `fAdjust_pre` decimal(10,2) NOT NULL default '0.00',
  `fValue` decimal(10,2) NOT NULL default '0.00',
  `fTaxComp` decimal(10,2) NOT NULL default '0.00',
  `fDisc_post` decimal(10,2) NOT NULL default '0.00',
  `fAdjust_post` decimal(10,2) NOT NULL default '0.00',
  `fTotalCharges` decimal(10,2) NOT NULL default '0.00',
  `fOtherCharges` decimal(10,2) NOT NULL default '0.00',
  `fTotal` decimal(10,2) NOT NULL default '0.00',
  `fPayable` decimal(10,2) NOT NULL default '0.00',
  `cPayAuth` char(1) NOT NULL default 'N',
  `iUserID_PayAuth` int(10) unsigned NOT NULL default '0',
  `dtDt_PayAuth` datetime default NULL,
  `fPaid` decimal(10,2) NOT NULL default '0.00',
  `cPaidStatus` char(1) NOT NULL default 'N',
  `dPaid` date default NULL,
  `iAcctTransID` bigint(20) unsigned NOT NULL default '0',
  `iUserID` int(10) unsigned NOT NULL default '0',
  `iUserID_auth` int(10) unsigned NOT NULL default '0',
  `vNotes` varchar(255) default NULL,
  `cType` char(1) NOT NULL default 'Y',
  `cIGST` char(1) NOT NULL default '0',
  `cStatus` char(1) NOT NULL default 'I',
  PRIMARY KEY  (`iGRETID`,`iLocID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `pur_gret` (
  `iGRETID` bigint(20) unsigned NOT NULL default '0',
  `iGRETNum` bigint(20) unsigned NOT NULL default '0',
  `iLocID` tinyint(3) unsigned NOT NULL default '0',
  `iGRID` bigint(20) unsigned NOT NULL default '0',
  `iGRNum` bigint(20) unsigned NOT NULL default '0',
  `dDor` date default NULL,
  `iVendorID` int(10) unsigned NOT NULL default '0',
  `iItems` bigint(20) unsigned NOT NULL default '0',
  `fBasicAmount` decimal(10,2) NOT NULL default '0.00',
  `fDisc_pre` decimal(10,2) NOT NULL default '0.00',
  `fAdjust_pre` decimal(10,2) NOT NULL default '0.00',
  `fValue` decimal(10,2) NOT NULL default '0.00',
  `fTaxComp` decimal(10,2) NOT NULL default '0.00',
  `fDisc_post` decimal(10,2) NOT NULL default '0.00',
  `fAdjust_post` decimal(10,2) NOT NULL default '0.00',
  `fTotalCharges` decimal(10,2) NOT NULL default '0.00',
  `fOtherCharges` decimal(10,2) NOT NULL default '0.00',
  `fTotal` decimal(10,2) NOT NULL default '0.00',
  `fPayable` decimal(10,2) NOT NULL default '0.00',
  `cPayAuth` char(1) NOT NULL default 'N',
  `iUserID_PayAuth` int(10) unsigned NOT NULL default '0',
  `dtDt_PayAuth` datetime default NULL,
  `fPaid` decimal(10,2) NOT NULL default '0.00',
  `cPaidStatus` char(1) NOT NULL default 'N',
  `dPaid` date default NULL,
  `iAcctTransID` bigint(20) unsigned NOT NULL default '0',
  `iUserID` int(10) unsigned NOT NULL default '0',
  `iUserID_auth` int(10) unsigned NOT NULL default '0',
  `vNotes` varchar(255) default NULL,
  `cType` char(1) NOT NULL default 'Y',
  `cIGST` char(1) NOT NULL default '0',
  `cStatus` char(1) NOT NULL default 'I',
  PRIMARY KEY  (`iGRETID`,`iLocID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE `pur_gret_dat` (
  `iGRETDATID` bigint(20) unsigned NOT NULL default '0',
  `iGRETID` bigint(20) unsigned NOT NULL default '0',
  `iGRID` int(10) unsigned NOT NULL default '0',
  `iItemID` bigint(20) unsigned NOT NULL default '0',
  `vSuppCode` varchar(60) default NULL,
  `vSuppName` varchar(255) default NULL,
  `iBatchID` bigint(20) unsigned NOT NULL default '0',
  `iLocID` tinyint(3) unsigned NOT NULL default '0',
  `iUOM` tinyint(3) unsigned NOT NULL default '0',
  `iUOM_convFactor` int(10) unsigned NOT NULL default '1',
  `fQty` decimal(10,2) NOT NULL default '0.00',
  `fBC` decimal(10,2) NOT NULL default '0.00',
  `fDisc_pre` decimal(10,2) NOT NULL default '0.00',
  `fValue` decimal(10,2) NOT NULL default '0.00',
  `iTaxID` tinyint(3) unsigned NOT NULL default '0',
  `fTax_In_perc` decimal(10,2) NOT NULL default '0.00',
  `fTax_In_comp` decimal(10,2) NOT NULL default '0.00',
  `fVAT_Out_perc` decimal(10,2) NOT NULL default '0.00',
  `fVAT_Out_comp` decimal(10,2) NOT NULL default '0.00',
  `fDisc_post` decimal(10,2) NOT NULL default '0.00',
  `fTotal` decimal(10,2) NOT NULL default '0.00',
  `fMRP` decimal(10,2) NOT NULL default '0.00',
  `fSP` decimal(10,2) NOT NULL default '0.00',
  `fYield` decimal(10,2) NOT NULL default '0.00',
  `cExtra` char(1) NOT NULL default 'N',
  `vSchemeDesc` varchar(255) default NULL,
  `dExpiry` date default NULL,
  `cType` char(1) default NULL,
  `cStatus` char(1) NOT NULL default 'I',
  PRIMARY KEY  (`iGRETDATID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `pur_gret_dat` (
  `iGRETDATID` bigint(20) unsigned NOT NULL default '0',
  `iGRETID` bigint(20) unsigned NOT NULL default '0',
  `iGRID` int(10) unsigned NOT NULL default '0',
  `iItemID` bigint(20) unsigned NOT NULL default '0',
  `vSuppCode` varchar(60) default NULL,
  `vSuppName` varchar(255) default NULL,
  `iBatchID` bigint(20) unsigned NOT NULL default '0',
  `iLocID` tinyint(3) unsigned NOT NULL default '0',
  `iUOM` tinyint(3) unsigned NOT NULL default '0',
  `iUOM_convFactor` int(10) unsigned NOT NULL default '1',
  `fQty` decimal(10,2) NOT NULL default '0.00',
  `fBC` decimal(10,2) NOT NULL default '0.00',
  `fDisc_pre` decimal(10,2) NOT NULL default '0.00',
  `fValue` decimal(10,2) NOT NULL default '0.00',
  `iTaxID` tinyint(3) unsigned NOT NULL default '0',
  `fTax_In_perc` decimal(10,2) NOT NULL default '0.00',
  `fTax_In_comp` decimal(10,2) NOT NULL default '0.00',
  `fVAT_Out_perc` decimal(10,2) NOT NULL default '0.00',
  `fVAT_Out_comp` decimal(10,2) NOT NULL default '0.00',
  `fDisc_post` decimal(10,2) NOT NULL default '0.00',
  `fTotal` decimal(10,2) NOT NULL default '0.00',
  `fMRP` decimal(10,2) NOT NULL default '0.00',
  `fSP` decimal(10,2) NOT NULL default '0.00',
  `fYield` decimal(10,2) NOT NULL default '0.00',
  `cExtra` char(1) NOT NULL default 'N',
  `vSchemeDesc` varchar(255) default NULL,
  `dExpiry` date default NULL,
  `cType` char(1) default NULL,
  `cStatus` char(1) NOT NULL default 'I',
  PRIMARY KEY  (`iGRETDATID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



