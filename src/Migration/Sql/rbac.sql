
DROP TABLE IF EXISTS `dbauth_rule`;
CREATE TABLE `dbauth_rule`
(
    `name`       varchar(64) NOT NULL,
    `data`       blob,
    `created_at` int         NULL,
    `updated_at` int         NULL,
    PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=`utf8mb4` COLLATE=`utf8mb4_unicode_ci`;

DROP TABLE IF EXISTS `dbauth_item`;
CREATE TABLE `dbauth_item`
(
    `name`        varchar(64)  NOT NULL,
    `type`        tinyint      NOT NULL,
    `description` varchar(192) NULL,
    `external_id` int          NULL,
    `attached_id` int          NULL,
    `rule_name`   varchar(64)  NULL,
    `data`        blob,
    `created_at`  int          NULL,
    `updated_at`  int          NULL,
    PRIMARY KEY (`name`),
    KEY `idx_dbauth_item_type` (`type`),
    FOREIGN KEY `fk_dbauth_item_rule_name` (`rule_name`) REFERENCES `dbauth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=`utf8mb4` COLLATE=`utf8mb4_unicode_ci`;

DROP TABLE IF EXISTS `dbauth_item_child`;
CREATE TABLE `dbauth_item_child`
(
    `parent` varchar(64) NOT NULL,
    `child`  varchar(64) NOT NULL,
    PRIMARY KEY (`parent`, `child`),
    FOREIGN KEY (`parent`) REFERENCES `dbauth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY `fk_dbauth_item_child_child` (`child`) REFERENCES `dbauth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=`utf8mb4` COLLATE=`utf8mb4_unicode_ci`;

DROP TABLE IF EXISTS `dbauth_assignment`;
CREATE TABLE `dbauth_assignment`
(
    `item_name`  varchar(64) NOT NULL,
    `user_id`    varchar(64) NOT NULL,
    `created_at` int         NULL,
    PRIMARY KEY (`item_name`, `user_id`),
    KEY `idx_dbauth_assignment_user_id` (`user_id`),
    FOREIGN KEY (`item_name`) REFERENCES `dbauth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=`utf8mb4` COLLATE=`utf8mb4_unicode_ci`;
