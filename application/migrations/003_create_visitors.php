<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_visitors extends CI_Migration{

	public function up()
	{
		$prefix = "vst" ;
		$table_name = $this->db->dbprefix("visitors");
		$this->db->query(
				"CREATE TABLE {$table_name} (
				{$prefix}_id INT(13) NOT NULL AUTO_INCREMENT ,
				{$prefix}_ip varchar(45) NOT NULL,
				{$prefix}_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
				CONSTRAINT visitors_pk PRIMARY KEY ({$prefix}_id) ,
				INDEX ({$prefix}_ip) 
				) ENGINE=INNODB
				  DEFAULT CHARSET = utf8  
				  DEFAULT COLLATE = utf8_unicode_ci
				  ;"
						);
	}

	public function down()
	{
		$this->dbforge->drop_table('visitors');
	}

}