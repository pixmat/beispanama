CREATE TABLE `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `screen_name` varchar(50) NOT NULL,
  `equipo` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`screen_name`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `screen_name` (`screen_name`),
  KEY `equipo` (`equipo`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;