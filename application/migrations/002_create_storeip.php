<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_storeip extends CI_Migration{

	public function up()
	{
		$prefix = "sip" ;
		$table_name = $this->db->dbprefix("storeip");
		//color -> white 1 , black 2
		$this->db->query(
				"CREATE TABLE {$table_name} (
				{$prefix}_id INT(13) NOT NULL AUTO_INCREMENT ,
				{$prefix}_ip varchar(45) NOT NULL,
				{$prefix}_color INT(2) NOT NULL ,
				{$prefix}_wrongcaptcha INT(4) NULL ,
				{$prefix}_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
				CONSTRAINT storeip_pk PRIMARY KEY ({$prefix}_id) ,
				UNIQUE ({$prefix}_ip) 
				) ENGINE=INNODB
				  DEFAULT CHARSET = utf8  
				  DEFAULT COLLATE = utf8_unicode_ci
				  ;"
						);
	}

	public function down()
	{
		$this->dbforge->drop_table('ipstore');
	}

}