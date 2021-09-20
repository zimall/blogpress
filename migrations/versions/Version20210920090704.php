<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920090704 extends AbstractMigration
{
    public function getDescription() : string
    {
	    return 'Create new field for gallery image caption';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
	    $prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
	    $this->addSql("ALTER TABLE `{$prefix}gallery_images` ADD `gi_caption` VARCHAR(255) NOT NULL default ''");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
	    $prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
	    $this->addSql("ALTER TABLE `{$prefix}gallery_images` DROP `gi_caption`");
    }
}
