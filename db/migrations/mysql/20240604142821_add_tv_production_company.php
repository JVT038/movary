<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTvProductionCompany extends AbstractMigration
{
    public function down() : void
    {
        $this->execute(
            <<<SQL
            DROP TABLE `tv_show_production_company`;
            SQL
        );
    }

    public function up() : void
    {
        $this->execute(
            <<<SQL
            CREATE TABLE `tv_show_production_company` (
                `company_id` INT(10) UNSIGNED NOT NULL,
                `tv_show_id` INT(10) UNSIGNED NOT NULL,
                `position` SMALLINT UNSIGNED,
                FOREIGN KEY (`company_id`) REFERENCES company (`id`),
                FOREIGN KEY (`tv_show_id`) REFERENCES tv_shows (`id`),
                UNIQUE (`company_id`, `tv_show_id`),
                UNIQUE (`tv_show_id`, `position`)
            ) COLLATE="utf8mb4_unicode_ci" ENGINE=InnoDB
            SQL
        );
    }
}
