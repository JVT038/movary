<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTvCrewAndCastTables extends AbstractMigration
{
    public function down() : void
    {
        $this->execute(
            <<<SQL
            DROP TABLE `tv_show_cast`;
            DROP TABLE `tv_show_crew`;
            SQL
        );
    }
    public function up(): void
    {
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_crew` (
                `person_id` INT(10) UNSIGNED NOT NULL,
                `tv_show_id` INT(10) UNSIGNED NOT NULL,
                `job` VARCHAR(255) NOT NULL,
                `department` VARCHAR(255) NOT NULL,
                `position` SMALLINT UNSIGNED,
                FOREIGN KEY (`person_id`) REFERENCES person (`id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES `tv_shows` (`id`),
                UNIQUE (`tv_show_id`, `position`)
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB;
            SQL
        );

        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_cast` (
                `person_id` INT(10) UNSIGNED NOT NULL,
                `tv_show_id` INT(10) UNSIGNED NOT NULL,
                `season_id` INT(10) UNSIGNED NOT NULL,
                `episode_id` INT(10) UNSIGNED NOT NULL,
                `character_name` TEXT NOT NULL,
                `position` SMALLINT UNSIGNED,
                FOREIGN KEY (`person_id`) REFERENCES person (`id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES tv_shows (`id`),
                FOREIGN KEY (`season_id`) REFERENCES tv_seasons (`id`),
                FOREIGN KEY (`episode_id`) REFERENCES tv_episodes (`id`),
                UNIQUE (`episode_id`, `position`)
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB
            SQL
        );
    }
}
