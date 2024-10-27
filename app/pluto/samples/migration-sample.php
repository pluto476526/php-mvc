<?php

namespace Pluto;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

 /**
 * {CLASSNAME} class
 */

class {CLASSNAME} extends Migration
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
        $this->createTable('{classname}');
        $this->addData('date_created', date("Y-m-d H:i:s"));
        $this->addData('date_updated', date("Y-m-d H:i:s"));
        $this->insertData('{classname}');
    }

    public function down()
    {
        $this->dropTable('{classname}');
    }
}