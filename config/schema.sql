CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calories` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

CREATE TABLE `dishes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eating_time` datetime NOT NULL,
  `dish` varchar(100) NOT NULL,
  `calories` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eating_time` (`eating_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
