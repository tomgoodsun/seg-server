-- vim : ft=mysql :
-- Adminer 4.8.1 MySQL 8.3.0 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `api_token` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `expired_date` timestamp NULL DEFAULT NULL,
  `updated_date` timestamp NOT NULL,
  `created_date` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `score`;
CREATE TABLE `score` (
  `user_game_id` bigint unsigned NOT NULL,
  `score` bigint unsigned NOT NULL,
  `updated_date` timestamp NOT NULL,
  `created_date` timestamp NOT NULL,
  PRIMARY KEY (`user_game_id`),
  CONSTRAINT `fk_score_user_game_id` FOREIGN KEY (`user_game_id`) REFERENCES `user_game` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `score_history`;
CREATE TABLE `score_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_game_id` bigint unsigned NOT NULL,
  `score` bigint unsigned NOT NULL DEFAULT '0',
  `updated_date` timestamp NOT NULL,
  `created_date` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_score_history_user_game_id_idx` (`user_game_id`),
  CONSTRAINT `fk_score_history_user_game_id` FOREIGN KEY (`user_game_id`) REFERENCES `user_game` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `access_token` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `refresh_token` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `expired_date` timestamp NULL DEFAULT NULL,
  `deleted_flag` tinyint unsigned NOT NULL DEFAULT '0',
  `updated_date` timestamp NOT NULL,
  `created_date` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


DROP TABLE IF EXISTS `user_game`;
CREATE TABLE `user_game` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `game_id` bigint unsigned NOT NULL,
  `deleted_flag` tinyint unsigned NOT NULL DEFAULT '0',
  `update_date` timestamp NOT NULL,
  `created_date` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_game_user_id_idx` (`user_id`),
  KEY `fk_user_game_game_id_idx` (`game_id`),
  CONSTRAINT `fk_user_game_game_id` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`),
  CONSTRAINT `fk_user_game_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


-- 2024-03-13 18:32:25
