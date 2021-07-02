<?php

declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210702162021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Update courses section';
    }

    public function up(Schema $schema) : void
    {
    	$prefix = defined('TABLE_PREFIX') ? TABLE_PREFIX : '';

	    $update = "UPDATE `{$prefix}sections` SET `sc_f1` = 'Day',`sc_v1` = 'e.g 08',`sc_f2` = 'Month',`sc_v2` = 'e.g December' WHERE `sc_value` = 'events'";
	    $this->write($update);
	    $this->addSql($update);
	    $update = "UPDATE `{$prefix}sections` SET `sc_f1` = 'course_id',`sc_v1` = 'enter course ID if this page is related to a specific course' 
			WHERE `sc_value` = 'admission'";
	    $this->write($update);
	    $this->addSql($update);
		$update = "UPDATE `{$prefix}sections` SET `sc_f1` = 'Program Code',`sc_v1` = 'e.g ABCD01',`sc_f2` = 'Accreditation',`sc_v2` = '',`sc_f3` = 'Length of Study',
			                         `sc_v3` = 'e.g 6 months',`sc_f4` = 'Study Options',`sc_v4` = 'e.g. Full Time',`sc_f5` = 'banner',
			                         `sc_v5` = 'e.g. image01.jpg (path is relative to images folder)', `sc_f7` = 'short-name',`sc_v7` = 'shortened name of the course',
			                         `sc_f8` = 'front-thumb',`sc_v8` = 'e.g. image01.jpg (path is relative to images folder)' WHERE `sc_value` = 'courses'";
	    $this->write($update);
	    $this->addSql($update);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
