<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_setting extends CI_Migration{

	public function up()
	{
		$prefix = "sng" ;
		$table_name = $this->db->dbprefix("setting");
		$this->db->query(
				"CREATE TABLE {$table_name} (
				{$prefix}_id INT(13) NOT NULL AUTO_INCREMENT ,
				{$prefix}_name varchar(100) NOT NULL,
				{$prefix}_value varchar(100) NOT NULL,
				CONSTRAINT setting_pk PRIMARY KEY ({$prefix}_id) 
				) ENGINE=INNODB
				  DEFAULT CHARSET = utf8  
				  DEFAULT COLLATE = utf8_unicode_ci
				  ;"
						);
	}

	public function down()
	{
		$this->dbforge->drop_table('setting');
	}

}