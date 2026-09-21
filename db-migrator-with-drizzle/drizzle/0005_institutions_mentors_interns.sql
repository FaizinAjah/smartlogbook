CREATE TABLE `institutions` (
	`id` bigint unsigned AUTO_INCREMENT NOT NULL,
	`name` varchar(255) NOT NULL,
	`type` varchar(50) NOT NULL DEFAULT 'SMK',
	`address` text,
	`phone` varchar(50),
	`email` varchar(255),
	`website` varchar(255),
	`is_active` tinyint NOT NULL DEFAULT 1,
	`created_by` bigint unsigned,
	`updated_by` bigint unsigned,
	`deleted_by` bigint unsigned,
	`created_at` timestamp,
	`updated_at` timestamp,
	`deleted_at` timestamp,
	CONSTRAINT `institutions_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE TABLE `interns` (
	`id` bigint unsigned AUTO_INCREMENT NOT NULL,
	`user_id` bigint unsigned NOT NULL,
	`institution_id` bigint unsigned NOT NULL,
	`mentor_id` bigint unsigned NOT NULL,
	`nis_nim` varchar(100),
	`major` varchar(150),
	`start_date` date,
	`end_date` date,
	`status` varchar(50) NOT NULL DEFAULT 'aktif',
	`notes` text,
	`created_by` bigint unsigned,
	`updated_by` bigint unsigned,
	`deleted_by` bigint unsigned,
	`created_at` timestamp,
	`updated_at` timestamp,
	`deleted_at` timestamp,
	CONSTRAINT `interns_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE TABLE `mentors` (
	`id` bigint unsigned AUTO_INCREMENT NOT NULL,
	`institution_id` bigint unsigned NOT NULL,
	`user_id` bigint unsigned,
	`name` varchar(255) NOT NULL,
	`identity_number` varchar(100),
	`phone` varchar(50),
	`position` varchar(100),
	`is_active` tinyint NOT NULL DEFAULT 1,
	`created_by` bigint unsigned,
	`updated_by` bigint unsigned,
	`deleted_by` bigint unsigned,
	`created_at` timestamp,
	`updated_at` timestamp,
	`deleted_at` timestamp,
	CONSTRAINT `mentors_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
ALTER TABLE `institutions` ADD CONSTRAINT `institutions_created_by_users_id_fk` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `institutions` ADD CONSTRAINT `institutions_updated_by_users_id_fk` FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `institutions` ADD CONSTRAINT `institutions_deleted_by_users_id_fk` FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `interns` ADD CONSTRAINT `interns_user_id_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `interns` ADD CONSTRAINT `interns_institution_id_institutions_id_fk` FOREIGN KEY (`institution_id`) REFERENCES `institutions`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `interns` ADD CONSTRAINT `interns_mentor_id_mentors_id_fk` FOREIGN KEY (`mentor_id`) REFERENCES `mentors`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `interns` ADD CONSTRAINT `interns_created_by_users_id_fk` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `interns` ADD CONSTRAINT `interns_updated_by_users_id_fk` FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `interns` ADD CONSTRAINT `interns_deleted_by_users_id_fk` FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `mentors` ADD CONSTRAINT `mentors_institution_id_institutions_id_fk` FOREIGN KEY (`institution_id`) REFERENCES `institutions`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `mentors` ADD CONSTRAINT `mentors_user_id_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `mentors` ADD CONSTRAINT `mentors_created_by_users_id_fk` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `mentors` ADD CONSTRAINT `mentors_updated_by_users_id_fk` FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
ALTER TABLE `mentors` ADD CONSTRAINT `mentors_deleted_by_users_id_fk` FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`) ON DELETE no action ON UPDATE no action;--> statement-breakpoint
CREATE INDEX `interns_user_id_index` ON `interns` (`user_id`);--> statement-breakpoint
CREATE INDEX `interns_institution_id_index` ON `interns` (`institution_id`);--> statement-breakpoint
CREATE INDEX `interns_mentor_id_index` ON `interns` (`mentor_id`);--> statement-breakpoint
CREATE INDEX `interns_status_index` ON `interns` (`status`);--> statement-breakpoint
CREATE INDEX `mentors_institution_id_index` ON `mentors` (`institution_id`);--> statement-breakpoint
CREATE INDEX `mentors_user_id_index` ON `mentors` (`user_id`);