SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+08:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `opml` (
  `uuid` varchar(36) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='OPML数据表';

CREATE TABLE `opml_access_history` (
  `id` int(11) NOT NULL,
  `opml_uuid` varchar(36) NOT NULL,
  `access_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `access_ip` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='OPML访问日志表';

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `option_name` varchar(128) NOT NULL,
  `option_val` text NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户设置表';

CREATE TABLE `rss` (
  `uuid` varchar(36) NOT NULL,
  `opml_uuid` varchar(36) NOT NULL,
  `feed_name` text NOT NULL,
  `feed_comment` text,
  `feed_url` text,
  `website_url` text,
  `enabled` tinyint(1) DEFAULT '1',
  `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='RSS数据表';

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(32) NOT NULL,
  `login_history` text,
  `register_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `last_login_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户信息表';


ALTER TABLE `opml`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `opml_uuid_uindex` (`uuid`),
  ADD KEY `opml_user_id_fk` (`uid`);

ALTER TABLE `opml_access_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opml_access_history_opml_uuid_fk` (`opml_uuid`);

ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `options_user_id_fk` (`uid`);

ALTER TABLE `rss`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `rss_uuid_uindex` (`uuid`),
  ADD KEY `rss_opml_uuid_fk` (`opml_uuid`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_email_uindex` (`email`);


ALTER TABLE `opml_access_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `opml`
  ADD CONSTRAINT `opml_user_id_fk` FOREIGN KEY (`uid`) REFERENCES `user` (`id`);

ALTER TABLE `opml_access_history`
  ADD CONSTRAINT `opml_access_history_opml_uuid_fk` FOREIGN KEY (`opml_uuid`) REFERENCES `opml` (`uuid`);

ALTER TABLE `options`
  ADD CONSTRAINT `options_user_id_fk` FOREIGN KEY (`uid`) REFERENCES `user` (`id`);

ALTER TABLE `rss`
  ADD CONSTRAINT `rss_opml_uuid_fk` FOREIGN KEY (`opml_uuid`) REFERENCES `opml` (`uuid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
