CREATE TABLE `webpagetest` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `url` varchar(800) NOT NULL,
  `datetime` datetime NOT NULL,
  `jsonUrl` varchar(800) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

CREATE TABLE `perf` (
  `ID` int(255) NOT NULL AUTO_INCREMENT,
  `backend` decimal(10,2) NOT NULL,
  `frontend` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `url` varchar(800) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1099 ;
