<?php

namespace Pluto;

defined('ROOTPATH') OR exit('Access Denied');

 /**
 * Sports class
 */

class Sports extends Migration
{
    public function up()
    {
        // $this->addColumn();
        // $this->addPrimaryKey();
        // $this->addUniqueKey();
        // $this->addData();
        // $this->insertData();
        // $this->createTable();

        $this->addColumn('id int(11) NOT NULL AUTO_INCREMENT');
        $this->addColumn('date_created datetime NULL');
        $this->addColumn('date_updated datetime NULL');
        $this->addPrimaryKey('id');
        $this->createTable('sports');
        $this->addData('date_created', date("Y-m-d H:i:s"));
        $this->addData('date_updated', date("Y-m-d H:i:s"));
        $this->insertData('sports');
    }

    public function down()
    {
        $this->dropTable('sports');
    }
}