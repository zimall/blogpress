<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210702123101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added new field/value pair columns in article sections';
    }

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
		$this->addSql("ALTER TABLE `{$prefix}sections` 
			add column `sc_f1` varchar(255) NOT NULL default '',
			add column   `sc_v1` varchar(255) NOT NULL default '',
			add column  `sc_f2` varchar(255) NOT NULL default '',
			add column  `sc_v2` varchar(255) NOT NULL default '',
			add column  `sc_f3` varchar(255) NOT NULL default '',
			add column  `sc_v3` varchar(255) NOT NULL default '',
			add column  `sc_f4` varchar(255) NOT NULL default '',
			add column  `sc_v4` varchar(255) NOT NULL default '',
			add column   `sc_f5` varchar(255) NOT NULL default '',
			add column   `sc_v5` varchar(255) NOT NULL default '',
			add column  `sc_f6` varchar(255) NOT NULL default '',
			add column  `sc_v6` varchar(255) NOT NULL default '',
			add column  `sc_f7` varchar(255) NOT NULL default '',
			add column  `sc_v7` varchar(255) NOT NULL default '',
			add column   `sc_f8` varchar(255) NOT NULL default '',
			add column  `sc_v8` varchar(255) NOT NULL default ''");
	}

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
	    $prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';
	    $this->addSql("ALTER TABLE `{$prefix}sections` 
			drop sc_f1, drop sc_v1, drop sc_f2, drop sc_v2, drop sc_f3, drop sc_v3, drop sc_f4, drop sc_v4,
			drop sc_f5, drop sc_v5, drop sc_f6, drop sc_v6, drop sc_f7, drop sc_v7, drop sc_f8, drop sc_v8");
    }
}
