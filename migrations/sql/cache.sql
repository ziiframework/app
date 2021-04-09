
DROP TABLE IF EXISTS `dbcache`;
CREATE TABLE `dbcache`
(
  `id` varchar(128) NOT NULL,
  `expire` int NULL,
  `data` mediumblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=`utf8mb4` COLLATE=`utf8mb4_unicode_ci`;
