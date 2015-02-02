CREATE TABLE `perf` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `backend` decimal(10,2) NOT NULL,
  `frontend` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `url` varchar(800) NOT NULL,
  `ipAddress` varchar(100) NOT NULL,
  `country` varchar(200) NOT NULL,
  `state` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `webpagetest` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `url` varchar(800) NOT NULL,
  `datetime` datetime NOT NULL,
  `jsonUrl` varchar(800) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `webpagetest_results` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `ipAddress` varchar(100) NOT NULL,
  `country` varchar(200) NOT NULL,
  `state` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `firstByte` varchar(20) NOT NULL,
  `startRender` varchar(20) NOT NULL,
  `speedIndex` varchar(20) NOT NULL,
  `loadTime` varchar(20) NOT NULL,
  `visuallyComplete` varchar(20) NOT NULL,
  `domElements` varchar(20) NOT NULL,
  `totalSize` varchar(20) NOT NULL,
  `link` varchar(500) NOT NULL,
  `json` varchar(500) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
