
DROP TABLE IF EXISTS `dbqueue`;
CREATE TABLE `dbqueue`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `channel`     varchar(254) NOT NULL,
    `job`         mediumblob   NOT NULL,
    `pushed_at`   int          NOT NULL,
    `ttr`         int          NOT NULL,
    `delay`       int          NOT NULL DEFAULT 0,
    `priority`    int          NOT NULL DEFAULT 1024,
    `reserved_at` int          NULL,
    `attempt`     int          NULL,
    `done_at`     int          NULL,
    PRIMARY KEY (`id`),
    KEY `idx_dbqueue__channel` (`channel`),
    KEY `idx_dbqueue__reserved_at` (`reserved_at`),
    KEY `idx_dbqueue__priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=`utf8mb4` COLLATE=`utf8mb4_unicode_ci`;
