--
-- Table structure for table `cs_role_privilege`
--

DROP TABLE IF EXISTS `CS_User_Privilege`;
CREATE TABLE IF NOT EXISTS `CS_User_Privilege` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `privilege` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_user`
--

DROP TABLE IF EXISTS `CS_User`;
CREATE TABLE IF NOT EXISTS `CS_User` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cs_user_role`
--

DROP TABLE IF EXISTS `CS_User_Role`;
CREATE TABLE IF NOT EXISTS `CS_User_Role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;