<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTvTables extends AbstractMigration
{
    public function down() : void
    {
        $this->execute(
            <<<SQL
            DROP TABLE `tv_episodes`;
            DROP TABLE `tv_seasons`;
            DROP TABLE `tv_shows`;            
            SQL,
        );
    }

    public function up() : void
    {
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_shows` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(256) NOT NULL,
                `trakt_id` INT(10) UNSIGNED NOT NULL,
                `imdb_id` VARCHAR(10) NOT NULL,
                `tmdb_id` INT(10) UNSIGNED NOT NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
                `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE NOW(),
                PRIMARY KEY (`id`),
                UNIQUE (`trakt_id`),
                UNIQUE (`imdb_id`),
                UNIQUE (`tmdb_id`)
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB
            SQL,
        );
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_seasons` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `tv_show_id` INT(10) UNSIGNED NOT NULL,
                `title` VARCHAR(256) NOT NULL,
                `season` INT(10) UNSIGNED NOT NULL,
                `rating` TINYINT UNSIGNED DEFAULT NULL,
                `trakt_id` INT(10) UNSIGNED NOT NULL,
                `imdb_id` VARCHAR(10) NOT NULL,
                `tmdb_id` INT(10) UNSIGNED NOT NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
                `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE NOW(),
                PRIMARY KEY (`id`),
                UNIQUE (`trakt_id`),
                UNIQUE (`imdb_id`),
                UNIQUE (`tmdb_id`),
                FOREIGN KEY `foreign_key_tv_show` (`tv_show_id`) REFERENCES `tv_shows` (`id`)
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB  
            SQL
        );
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_episodes` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `tv_season_id` INT(10) UNSIGNED NOT NULL,
                `title` VARCHAR(256) NOT NULL,
                `season` INT(10) UNSIGNED NOT NULL,
                `episode` INT(10) UNSIGNED NOT NULL,
                `year` YEAR NOT NULL,
                `rating` TINYINT UNSIGNED DEFAULT NULL,
                `trakt_id` INT(10) UNSIGNED NOT NULL,
                `imdb_id` VARCHAR(10) NOT NULL,
                `tmdb_id` INT(10) UNSIGNED NOT NULL,
                `created_at` TIMESTAMP NOT NULL DEFAULT NOW(),
                `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE NOW(),
                PRIMARY KEY (`id`),
                UNIQUE (`trakt_id`),
                UNIQUE (`imdb_id`),
                UNIQUE (`tmdb_id`),
                FOREIGN KEY `foreign_key_tv_season` (`tv_season_id`) REFERENCES `tv_seasons` (`id`)
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB  
            SQL
        );
    }
}
