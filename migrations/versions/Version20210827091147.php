<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210827091147 extends AbstractMigration
{
    public function getDescription() : string
    {
	    return 'Create new fields for controller and view in page categories';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
	    $prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
	    $this->addSql("ALTER TABLE `{$prefix}sections` ADD `sc_controller` VARCHAR(255) NOT NULL, ADD `sc_view` VARCHAR(255) NOT NULL");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
	    $prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
	    $this->addSql("ALTER TABLE `{$prefix}sections` DROP `sc_controller`, DROP `sc_view`");
    }
}
