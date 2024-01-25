CREATE TABLE `users` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '아이디',
	`created_at` TIMESTAMP(6) NULL DEFAULT current_timestamp(6) COMMENT '생성일시',
	`status` ENUM('사용','중단') NULL DEFAULT '사용' COMMENT '상태' COLLATE 'utf8mb4_unicode_ci',
	`token` VARCHAR(8) NULL DEFAULT NULL COMMENT '토큰' COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(64) NULL DEFAULT NULL COMMENT '이메일' COLLATE 'utf8mb4_unicode_ci',
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `token` (`token`) USING BTREE,
	UNIQUE INDEX `email` (`email`) USING BTREE
)
COMMENT='사용자'
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;
