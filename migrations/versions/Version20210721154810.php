<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721154810 extends AbstractMigration
{
    public function getDescription() : string
    {
	    return 'Create new fields for handling new feature of default items per page';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
	    $prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
	    $this->addSql("ALTER TABLE `{$prefix}sections` add column `sc_items` int NOT NULL default 10");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
	    $prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
	    $this->addSql("ALTER TABLE `{$prefix}sections` drop `sc_items`");
    }
}
