<?php

/**
 * Create the main table
 *
 * @package migrations
 */

class BackofficeUsers extends Akrabat_Db_Schema_AbstractChange
{
    public function up(){
         $username = 'admin';
         $password = sha1('kajwdawadmin');
         $email = '4948@mailinator.com';

        $sql = "CREATE TABLE `backoffice_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(40) NOT NULL,
  `password_valid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `email` varchar(340) NOT NULL,
  `phone_number` varchar(20) NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `last_password_update` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`(255))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `backoffice_users` (`id`,`firstname`,`lastname`,`username`,`password`,`password_valid`,`email`)
VALUES
	(1, 'Admin', '', '" . $username . "', '" . $password . "', 0, '" . $email . "');
";
        $this->_db->query($sql);
    }
    
    public function down(){
        $sql = "DROP TABLE IF EXISTS `backoffice_users`";
        $this->_db->query($sql);
    }
}
