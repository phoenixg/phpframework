DROP TABLE IF EXISTS `Tbl_User`;
CREATE TABLE IF NOT EXISTS `Tbl_User` (
  `FUserId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FScoreCount` int(11) DEFAULT '0',
  `FTotalScoreCount` int(11) DEFAULT '0',
  `FVoteCount` int(11) DEFAULT '0',
  `FInviteCount` int(11) DEFAULT '0',
  `FFileCount` int(11) DEFAULT '0',
  `FBType` tinyint(3) unsigned NOT NULL,
  `FLType` tinyint(3) unsigned NOT NULL,
  `FEnable` tinyint(3) unsigned NOT NULL,
  `FQQ` varchar(16) NOT NULL,
  `FTrueName` varchar(255) NOT NULL,
  `FUser` varchar(255) NOT NULL,
  `FPwd` varchar(255) NOT NULL,
  `FNick` varchar(255) NOT NULL,
  `FSex` varchar(8) NOT NULL,
  `FAge` varchar(8) NOT NULL,
  `FBirthday` varchar(32) NOT NULL,
  `FHeight` varchar(8) NOT NULL,
  `FWeight` varchar(8) NOT NULL,
  `FDegree` varchar(32) NOT NULL,
  `FProvince` varchar(32) NOT NULL,
  `FCity` varchar(32) NOT NULL,
  `FAddr` text NOT NULL,
  `FZipCode` varchar(8) NOT NULL,
  `FTel` varchar(32) NOT NULL,
  `FMobile` varchar(32) NOT NULL,
  `FEmail` varchar(255) NOT NULL,
  `FIdcard` varchar(64) NOT NULL,
  `FBloodType` varchar(8) NOT NULL,
  `FCareer` varchar(255) NOT NULL,
  `FCollege` varchar(255) NOT NULL,
  `FHomePage` varchar(255) NOT NULL,
  `FFileName` varchar(255) NOT NULL,
  `FFileUrl` text NOT NULL,
  `FFileDesc` text NOT NULL,
  `FFavorate` varchar(255) NOT NULL,
  `FTime` datetime NOT NULL,
  `FDate` date NOT NULL DEFAULT '0000-00-00',
  `FLastTime` datetime NOT NULL,
  `FPersonalDesc` text NOT NULL,
  `FValue1` varchar(32) NOT NULL,
  `FValue2` varchar(32) NOT NULL,
  `FValue3` varchar(32) NOT NULL,
  `FValue4` text NOT NULL,
  `FValue5` text NOT NULL,
  `FQzoneBlog` int(10) NOT NULL,
  `FQzoneWidget` int(10) NOT NULL,
  `FQzoneFeeds` int(10) NOT NULL,
  `FMemo` varchar(255) NOT NULL,
  PRIMARY KEY (`FUserId`),
  UNIQUE KEY `INDEX_FQQ` (`FQQ`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Tbl_InviteHistory`;
CREATE TABLE IF NOT EXISTS `Tbl_InviteHistory` (
  `FInviteHistoryId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FInviterQQ` varchar(16) NOT NULL,
  `FInviterId` int(10) NOT NULL,
  `FInvitedQQ` varchar(16) NOT NULL,
  `FTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `FDate` date NOT NULL DEFAULT '0000-00-00',
  `FMemo` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`FInviteHistoryId`),
  KEY `INDEX_FSRCQQ` (`FInviterQQ`),
  KEY `INDEX_FDESCQQ` (`FInvitedQQ`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Tbl_ScoreHistory`;
CREATE TABLE IF NOT EXISTS `Tbl_ScoreHistory` (
  `FScoreHistoryId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FQQ` varchar(16) NOT NULL,
  `FStrategy` varchar(64) NOT NULL,
  `FScore` int(11) NOT NULL DEFAULT '0',
  `FIp` varchar(32) NOT NULL,
  `FTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `FDate` date NOT NULL DEFAULT '0000-00-00',
  `FMemo` varchar(255) NOT NULL,
  PRIMARY KEY (`FScoreHistoryId`),
  KEY `FQQ` (`FQQ`,`FDate`,`FStrategy`),
  KEY `FDate` (`FDate`,`FStrategy`),
  KEY `FQQ_2` (`FQQ`,`FStrategy`),
  KEY `FStrategy` (`FStrategy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Fake_Tbl_Qzonegood`;
CREATE TABLE IF NOT EXISTS `Fake_Tbl_Qzonegood` (
  `FQzonegoodId` int(10) NOT NULL AUTO_INCREMENT,
  `FQQ` varchar(16) NOT NULL,
  `FType` varchar(255) NOT NULL,
  `FTime` datetime NOT NULL,
  `FDate` date NOT NULL,
  PRIMARY KEY (`FQzonegoodId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Tbl_Award`;
CREATE TABLE IF NOT EXISTS `Tbl_Award` (
  `FAwardId` int(10) NOT NULL AUTO_INCREMENT,
  `FQQ` varchar(16) NOT NULL,
  `FStrategy` varchar(255) NOT NULL,
  `FTime` datetime NOT NULL,
  `FDate` date NOT NULL,
  `FPMProductNo` varchar(255) NOT NULL,
  `FPMItemNo` varchar(255) NOT NULL,
  `FPMSendStatus` int(10) NOT NULL,
  `FPMDealTime` datetime NOT NULL,
  `FAwardZone` varchar(255) NOT NULL,
  `FAwardOrder` int(10) NOT NULL,
  `FMemo` varchar(255) NOT NULL,
  PRIMARY KEY (`FAwardId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Fake_Send_Tbl_Award`;
CREATE TABLE IF NOT EXISTS `Fake_Send_Tbl_Award` (
  `FSendAwardId` int(10) NOT NULL AUTO_INCREMENT,
  `FAwardId` int(10) NOT NULL,
  `FQQ` varchar(16) NOT NULL,
  `FProductNo` varchar(255) NOT NULL,
  `FItemNo` varchar(255) NOT NULL,
  `FTime` datetime NOT NULL,
  `FDate` date NOT NULL,
  PRIMARY KEY (`FSendAwardId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Tbl_Code`;
CREATE TABLE IF NOT EXISTS `Tbl_Code` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `FCode` varchar(15) NOT NULL,
  `FQQ` varchar(255) NOT NULL,
  `FValue` varchar(10) NOT NULL,
  `FStatus` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `FCode` (`FCode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Fake_Tbl_Jump`;
CREATE TABLE IF NOT EXISTS `Fake_Tbl_Jump` (
  `FJumpId` int(10) NOT NULL AUTO_INCREMENT,
  `FQQ` varchar(255) NOT NULL,
  `FUrl` varchar(255) NOT NULL,
  `FType` varchar(255) NOT NULL,
  `FTamsId` int(10) unsigned NOT NULL,
  `FTime` datetime NOT NULL,
  `FDate` date NOT NULL,
  PRIMARY KEY (`FJumpId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Tbl_LotteryHistory`;
CREATE TABLE IF NOT EXISTS `Tbl_LotteryHistory` (
  `FLotteryHistoryId` int(11) NOT NULL AUTO_INCREMENT,
  `FQQ` varchar(32) NOT NULL,
  `FPosition` varchar(255) NOT NULL,
  `FStrategy` varchar(255) NOT NULL,
  `FLimitStrategy` varchar(255) NOT NULL,
  `FScoreStrategy` varchar(255) NOT NULL,
  `FAwardStrategy` varchar(255) NOT NULL,
  `FDate` date NOT NULL,
  `FTime` datetime NOT NULL,
  PRIMARY KEY (`FLotteryHistoryId`),
  KEY `FQQ` (`FQQ`,`FDate`,`FStrategy`),
  KEY `FDate` (`FDate`,`FStrategy`),
  KEY `FQQ_2` (`FQQ`,`FStrategy`),
  KEY `FStrategy` (`FStrategy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Tbl_ExchangeHistory`;
CREATE TABLE IF NOT EXISTS `Tbl_ExchangeHistory` (
  `FExchangeHistoryId` int(11) NOT NULL AUTO_INCREMENT,
  `FQQ` varchar(32) NOT NULL,
  `FCode` varchar(255) NOT NULL,
  `FStrategy` varchar(255) NOT NULL,
  `FLimitStrategy` varchar(255) NOT NULL,
  `FScoreStrategy` varchar(255) NOT NULL,
  `FAwardStrategy` varchar(255) NOT NULL,
  `FDate` date NOT NULL,
  `FTime` datetime NOT NULL,
  PRIMARY KEY (`FExchangeHistoryId`),
  KEY `FQQ` (`FQQ`,`FDate`,`FStrategy`),
  KEY `FDate` (`FDate`,`FStrategy`),
  KEY `FQQ_2` (`FQQ`,`FStrategy`),
  KEY `FStrategy` (`FStrategy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Fake_Tbl_ActionTrack`;
CREATE TABLE IF NOT EXISTS `Fake_Tbl_ActionTrack` (
  `FActionTrackId` int(11) NOT NULL AUTO_INCREMENT,
  `FQQ` varchar(16) NOT NULL,
  `FActionId` varchar(255) NOT NULL,
  `FTamsId` varchar(255) NOT NULL,
  `FDate` date NOT NULL,
  `FTime` datetime NOT NULL,
  PRIMARY KEY (`FActionTrackId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `Tbl_File`;
CREATE TABLE IF NOT EXISTS `Tbl_File` (
	`FFileId` int(11) NOT NULL AUTO_INCREMENT,
	`FQQ` varchar(16) NOT NULL,
	`FUserId` int(11) ,
	`FUser` varchar (255),
	`FName` varchar (255),
	`FNick` varchar (255),
	`FFriend`varchar (255),
	`FType` tinyint (3),
	`FSendAll` tinyint (3),
	`FVoteCount` int(11) DEFAULT '0',
	`FViewCount` int(11),
	`FScore` int(11) ,
	`FCheckTime` datetime ,
	`FEnable` tinyint (3),
	`FFileName` varchar (255),
	`FText` text ,
	`FUrl`varchar (255),
	`FMiniUrl` varchar (255),
	`FDesc` varchar (255),
	`FTime` datetime ,
	`FDate` date ,
	`FMemo` varchar (255),
	`FState` tinyint (3),
	`FDealTime` datetime ,
	`FAudioUrl` varchar (255),
	`FVideoUrl` varchar (255),
	`FVideoMiniUrl` varchar (255),
PRIMARY KEY (`FFileId`),
KEY `FQQ` (`FQQ`),
KEY `FDate` (`FDate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Tbl_Comment`;
CREATE TABLE `Tbl_Comment` (
     `FCommentId`     INT UNSIGNED NOT NULL AUTO_INCREMENT,
     `FFileId`         INT UNSIGNED NOT NULL,
     `FFileQQ`         VARCHAR(16) NOT NULL,
     `FQQ`         VARCHAR(16) NOT NULL,
     `FNick`          VARCHAR(255) NOT NULL,
     `FTitle`         VARCHAR(255) NOT NULL,
     `FComment`       TEXT NOT NULL,
     `FStrategy`         INT UNSIGNED NOT NULL,
     `FTime`          DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
     `FDate`          DATE DEFAULT '0000-00-00' NOT NULL,
     `FEnable`        TINYINT UNSIGNED DEFAULT '0' NOT NULL,
     `FCheckTime`     DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
     `FMemo`          VARCHAR(255) NOT NULL DEFAULT '',
     PRIMARY KEY (`FCommentId`),
     INDEX `INDEX_FDESID` (`FFileId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Tbl_VoteHistory`;
CREATE TABLE IF NOT EXISTS `Tbl_VoteHistory` (
  `FVoteHistoryId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FStrategy` varchar(255) NOT NULL,
  `FLimitStrategy` varchar(255) NOT NULL,
  `FAwardStrategy` varchar(255) NOT NULL,
  `FScoreStrategy` varchar(255) NOT NULL,
  `FFileId` int(10) unsigned NOT NULL,
  `FQQ` varchar(16) NOT NULL,
  `FIp` varchar(32) NOT NULL,
  `FVoteCounts` varchar(8) NOT NULL,
  `FTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `FDate` date NOT NULL DEFAULT '0000-00-00',
  `FMemo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`FVoteHistoryId`),
  KEY `INDEX_FSRCQQ` (`FQQ`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `Tbl_WidgetHistory`;
CREATE TABLE IF NOT EXISTS `Tbl_WidgetHistory` (
  `FWidgetHistoryId` int(11) NOT NULL AUTO_INCREMENT,
  `FQQ` varchar(32) NOT NULL,
  `FStrategy` varchar(255) NOT NULL,
  `FLimitStrategy` varchar(255) NOT NULL,
  `FScoreStrategy` varchar(255) NOT NULL,
  `FAwardStrategy` varchar(255) NOT NULL,
  `FDate` date NOT NULL,
  `FTime` datetime NOT NULL,
  PRIMARY KEY (`FWidgetHistoryId`),
  KEY `FQQ` (`FQQ`,`FDate`,`FStrategy`),
  KEY `FDate` (`FDate`,`FStrategy`),
  KEY `FQQ_2` (`FQQ`,`FStrategy`),
  KEY `FStrategy` (`FStrategy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `Tbl_QzoneLog` (
  `FQzoneLogId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FActivityId`   INT(4) UNSIGNED NOT NULL ,
  `FQQ` varchar(16) COLLATE utf8_bin NOT NULL,
  `FDate` DATE NOT NULL DEFAULT '0000-00-00',
  `FTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `FType` int(10) unsigned NOT NULL,
  `FEnable` int(10) unsigned NOT NULL,
  `FMemo`  VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`FQzoneLogId`),
  KEY `INDEX_FQQ` (`FQQ`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10;