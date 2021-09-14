<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210826072814 extends AbstractMigration
{
    public function getDescription() : string
    {
	    return 'Create new fields for keywords and description in page categories';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
	    $prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
	    $this->addSql("ALTER TABLE `{$prefix}sections` ADD `sc_keywords` VARCHAR(255) NOT NULL AFTER `sc_value`, 
    		ADD `sc_description` VARCHAR(255) NOT NULL AFTER `sc_keywords`");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
	    $prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
	    $this->addSql("ALTER TABLE `{$prefix}sections` DROP `sc_keywords`, DROP `sc_description`");
    }
}
