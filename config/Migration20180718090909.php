<?php
namespace Migrations;

use \Doctrine\DBAL\Migrations\AbstractMigration;
use \Doctrine\DBAL\Schema\Schema;

class Version20180718090909 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'This is the initial migration which creates user  tables.';
        return $description;
    }


    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('users');
        $schema->dropTable('language');
        $schema->dropTable('roles');
    }


}