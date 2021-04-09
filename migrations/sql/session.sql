
DROP TABLE IF EXISTS `dbsession`;
CREATE TABLE `dbsession`
(
  `id` varchar(128) NOT NULL,
  `expire` int NULL,
  `data` mediumblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=`utf8mb4` COLLATE=`utf8mb4_unicode_ci`;
