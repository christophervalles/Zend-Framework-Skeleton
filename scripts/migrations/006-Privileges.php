<?php

/**
 * Create the main table
 *
 * @package migrations
 */

class Privileges extends Akrabat_Db_Schema_AbstractChange
{
    public function up(){
        $sql = "CREATE TABLE `privileges` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `flag_id` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_name_flag_id` (`name`,`flag_id`),
  KEY `idx_resource_id` (`flag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;";
        $this->_db->query($sql);
        
        $sql = "INSERT INTO `privileges` (`id`,`name`,`flag_id`,`description`)
VALUES
	(1, 'index', '1', 'Allows the user to view all the flags registered in the application'),
	(2, 'toggleprod', '1', 'Change the active status of a flag on production'),
	(3, 'toggledev', '1', 'Change the active status of a flag on development'),
	(4, 'index', '2', 'Allows the user to view all the user groups registered\nin the application'),
	(5, 'add', '2', 'Allows the user to add another user group in the\napplication'),
	(6, 'edit', '2', 'Edits an existing user group'),
	(7, 'delete', '2', 'Allows the user to delete an existing user group. All the users attached to\nthis group *WILL NOT* be deleted, they will just lose all'),
	(8, 'flippers', '2', 'Allows the user to manage individual permissions for each\nuser group'),
	(9, 'index', '3', 'Controller\'s entry point'),
	(10, 'index', '4', 'Allows the user to view all the permissions registered\nin the application'),
	(11, 'add', '4', 'Allows the user to add another privilege in the application'),
	(12, 'edit', '4', 'Edits an existing privilege'),
	(13, 'delete', '4', 'Allows the user to delete an existing privilege. All the flippers related to\nthis privilege will be removed'),
	(14, 'index', '5', 'Allows users to see their dashboards'),
	(15, 'edit', '5', 'Allows the users to update their profiles'),
	(16, 'change-password', '5', 'Allows users to change their passwords'),
	(17, 'login', '5', 'Allows users to log into the application'),
	(18, 'logout', '5', 'Allows users to log out of the application'),
	(19, 'index', '6', 'Controller\'s entry point'),
	(20, 'example', '6', 'Theme example page'),
	(21, 'index', '7', 'Allows users to see all other users that are registered in\nthe application'),
	(22, 'add', '7', 'Allows users to add new users in the application\n(should be reserved for administrators)'),
	(23, 'edit', '7', 'Allows users to edit another users\' data\n(should be reserved for administrators)'),
	(24, 'view', '7', 'Allows users to see other users\' profiles'),
	(25, 'delete', '7', 'Allows users to logically delete other users\n(should be reserved for administrators)'),
	(26, 'index', '8', 'Controller\'s entry point'),
	(27, 'static', '8', 'Static Pages'),
	(28, 'zfdebug', '9', 'Debug toolbar'),
	(29, 'zfdebug', '10', 'Debug toolbar');
";
        $this->_db->query($sql);
    }
    
    public function down(){
        $sql = "DROP TABLE IF EXISTS `privileges`";
        $this->_db->query($sql);
    }
}
