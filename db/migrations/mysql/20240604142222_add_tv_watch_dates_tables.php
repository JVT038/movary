<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTvWatchDatesTables extends AbstractMigration
{

    public function down() : void
    {
        $this->execute("DROP TABLE `episode_user_watch_dates`");
    }
    public function up(): void
    {
        $this->execute(
            <<<SQL
            CREATE TABLE `episode_user_watch_dates` (
                `episode_id` INT(10) UNSIGNED NOT NULL,
                `user_id` INT UNSIGNED NOT NULL,
                `watched_at` DATE NULL,
                `plays` SMALLINT DEFAULT 1 NULL,
                `comment` TEXT NULL,
                `position` SMALLINT DEFAULT 1 NOT NULL,
                CONSTRAINT unique_watched_dates_episodes UNIQUE (`episode_id`, `user_id`, `watched_at`),
                CONSTRAINT foreign_key_user_id FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
                CONSTRAINT foreign_key_episode_id FOREIGN KEY (`episode_id`) REFERENCES `tv_episodes` (`id`) ON DELETE CASCADE
            )
            SQL
        );
    }
}
