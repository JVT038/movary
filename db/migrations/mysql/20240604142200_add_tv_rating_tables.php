<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTvRatingTables extends AbstractMigration
{
    public function down() : void
    {
        $this->execute(
            <<<SQL
            DROP TABLE `tv_show_user_rating`;
            DROP TABLE `season_user_rating`;
            DROP TABLE `episode_user_rating`;
            SQL
        );
    }
    public function up(): void
    {
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_user_rating` (
                `tv_show_id` INT(10) UNSIGNED NOT NULL,
                `user_id` INT(10) UNSIGNED NOT NULL,
                `rating` TINYINT NOT NULL,
                `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE NOW(),
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`tv_show_id`, `user_id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES `tv_shows`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB;
            SQL
        );

        $this->execute(
            <<<SQL
            CREATE TABLE `season_user_rating` (
                `season_id` INT(10) UNSIGNED NOT NULL,
                `user_id` INT(10) UNSIGNED NOT NULL,
                `rating` TINYINT NOT NULL,
                `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE NOW(),
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`season_id`, `user_id`),
                FOREIGN KEY (`season_id`) REFERENCES `tv_seasons`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB;
            SQL
        );

        $this->execute(
            <<<SQL
            CREATE TABLE `episode_user_rating` (
                `episode_id` INT(10) UNSIGNED NOT NULL,
                `user_id` INT(10) UNSIGNED NOT NULL,
                `rating` TINYINT NOT NULL,
                `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE NOW(),
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`episode_id`, `user_id`),
                FOREIGN KEY (`episode_id`) REFERENCES `tv_episodes`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB;
            SQL
        );
    }
}
