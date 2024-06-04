<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAllTvTables extends AbstractMigration
{
    public function down() : void
    {
        $this->execute(
            <<<SQL
                DROP TABLE `tv_episodes`;
                DROP TABLE `tv_seasons`;
                DROP TABLE `tv_shows`;
                DROP TABLE `tv_show_cast`;
                DROP TABLE `tv_show_crew`;
                DROP TABLE `tv_show_user_rating`;
                DROP TABLE `season_user_rating`;
                DROP TABLE `episode_user_rating`;
                DROP TABLE `episode_user_watch_dates`;
                DROP TABLE `tv_show_genre`;
                DROP TABLE `tv_show_production_company`;
            SQL
        );
    }
    public function up() : void
    {
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_shows` (
                `id` INTEGER,
                `title` TEXT NOT NULL,
                `trakt_id` INTEGER NOT NULL,
                `imdb_id` TEXT NOT NULL,
                `tmdb_id` INTEGER NOT NULL,
                `created_at` TEXT NOT NULL,
                `updated_at` TEXT DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`trakt_id`),
                UNIQUE (`imdb_id`),
                UNIQUE (`tmdb_id`)
            )
            SQL,
        );
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_seasons` (
                `id` INTEGER,
                `tv_show_id` INTEGER NOT NULL,
                `title` TEXT NOT NULL,
                `season` INTEGER NOT NULL,
                `rating` INTEGER DEFAULT NULL,
                `trakt_id` INTEGER NOT NULL,
                `imdb_id` TEXT NOT NULL,
                `tmdb_id` INTEGER NOT NULL,
                `created_at` TEXT NOT NULL,
                `updated_at` TEXT DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`trakt_id`),
                UNIQUE (`imdb_id`),
                UNIQUE (`tmdb_id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES `tv_shows` (`id`)
            )  
            SQL
        );
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_episodes` (
                `id` INTEGER,
                `tv_season_id` INTEGER NOT NULL,
                `title` TEXT NOT NULL,
                `season` INTEGER NOT NULL,
                `episode` INTEGER NOT NULL,
                `year` INTEGER NOT NULL,
                `rating` INTEGER DEFAULT NULL,
                `trakt_id` INTEGER NOT NULL,
                `imdb_id` TEXT NOT NULL,
                `tmdb_id` INTEGER NOT NULL,
                `created_at` TEXT NOT NULL,
                `updated_at` TEXT DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`trakt_id`),
                UNIQUE (`imdb_id`),
                UNIQUE (`tmdb_id`),
                FOREIGN KEY (`tv_season_id`) REFERENCES `tv_seasons` (`id`)
            )  
            SQL
        );
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_user_rating` (
                `tv_show_id` INTEGER NOT NULL,
                `user_id` INTEGER NOT NULL,
                `rating` INTEGER NOT NULL,
                `updated_at` TEXT DEFAULT NULL,
                `created_at` TEXT NOT NULL,
                PRIMARY KEY (`tv_show_id`, `user_id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES `tv_shows`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
            );
            SQL
        );

        $this->execute(
            <<<SQL
            CREATE TABLE `season_user_rating` (
                `season_id` INTEGER NOT NULL,
                `user_id` INTEGER NOT NULL,
                `rating` INTEGER NOT NULL,
                `updated_at` TEXT DEFAULT NULL,
                `created_at` TEXT NOT NULL,
                PRIMARY KEY (`season_id`, `user_id`),
                FOREIGN KEY (`season_id`) REFERENCES `tv_seasons`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
            );
            SQL
        );

        $this->execute(
            <<<SQL
            CREATE TABLE `episode_user_rating` (
                `episode_id` INTEGER NOT NULL,
                `user_id` INTEGER NOT NULL,
                `rating` INTEGER NOT NULL,
                `updated_at` TEXT DEFAULT NULL,
                `created_at` TEXT NOT NULL,
                PRIMARY KEY (`episode_id`, `user_id`),
                FOREIGN KEY (`episode_id`) REFERENCES `tv_episodes`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE
            );
            SQL
        );
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_crew` (
                `person_id` INTEGER NOT NULL,
                `tv_show_id` INTEGER NOT NULL,
                `job` TEXT NOT NULL,
                `department` TEXT NOT NULL,
                `position` INTEGER UNSIGNED,
                FOREIGN KEY (`person_id`) REFERENCES person (`id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES `tv_shows` (`id`),
                UNIQUE (`tv_show_id`, `position`)
            );
            SQL
        );

        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_cast` (
                `person_id` INTEGER NOT NULL,
                `tv_show_id` INTEGER NOT NULL,
                `season_id` INTEGER NOT NULL,
                `episode_id` INTEGER NOT NULL,
                `character_name` TEXT NOT NULL,
                `position` INTEGER UNSIGNED,
                FOREIGN KEY (`person_id`) REFERENCES person (`id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES tv_shows (`id`),
                FOREIGN KEY (`season_id`) REFERENCES tv_seasons (`id`),
                FOREIGN KEY (`episode_id`) REFERENCES tv_episodes (`id`),
                UNIQUE (`episode_id`, `position`)
            )
            SQL
        );
        $this->execute(
            <<<SQL
            CREATE TABLE `episode_user_watch_dates` (
                `episode_id` INTEGER NOT NULL,
                `user_id` INTEGER NOT NULL,
                `watched_at` TEXT NULL,
                `plays` INTEGER DEFAULT 1 NULL,
                `comment` TEXT NULL,
                `position` INTEGER DEFAULT 1 NOT NULL,
                UNIQUE (`episode_id`, `user_id`, `watched_at`),
                FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
                FOREIGN KEY (`episode_id`) REFERENCES `tv_episodes` (`id`) ON DELETE CASCADE
            )
            SQL
        );
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_genre` (
                `genre_id` INTEGER NOT NULL,
                `tv_show_id` INTEGER NOT NULL,
                `position` INTEGER DEFAULT NULL,
                FOREIGN KEY (`genre_id`) REFERENCES genre (`id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES tv_shows (`id`),
                UNIQUE (`genre_id`, `tv_show_id`),
                UNIQUE (`tv_show_id`, `position`)
            )
            SQL
        );
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_production_company` (
                `company_id` INTEGER NOT NULL,
                `tv_show_id` INTEGER NOT NULL,
                `position` INTEGER DEFAULT NULL,
                FOREIGN KEY (`company_id`) REFERENCES company (`id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES tv_shows (`id`),
                UNIQUE (`company_id`, `tv_show_id`),
                UNIQUE (`tv_show_id`, `position`)
            )
            SQL
        );
    }
}
