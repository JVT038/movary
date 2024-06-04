<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTvGenres extends AbstractMigration
{
    public function down() : void
    {
        $this->execute(
            <<<SQL
            DROP TABLE `tv_show_genre`;
            SQL
        );
    }
    public function up(): void
    {
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_genre` (
                `genre_id` INT(10) UNSIGNED NOT NULL,
                `tv_show_id` INT(10) UNSIGNED NOT NULL,
                `position` SMALLINT UNSIGNED,
                FOREIGN KEY (`genre_id`) REFERENCES genre (`id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES tv_shows (`id`),
                UNIQUE (`genre_id`, `tv_show_id`),
                UNIQUE (`tv_show_id`, `position`)
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB
            SQL
        );
    }
}
