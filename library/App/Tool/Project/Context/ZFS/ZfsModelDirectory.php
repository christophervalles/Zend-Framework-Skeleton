<?php
/**
 * @see Zend_Tool_Project_Context_Filesystem_Directory
 */
require_once 'Zend/Tool/Project/Context/Filesystem/Directory.php';

/**
 * This class is used for creating App/Model directory for Zend-Framework-Skeleton
 *
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Context_ZFS_ZfsModelDirectory extends Zend_Tool_Project_Context_Filesystem_Directory
{

    /**
     * @var string
     */
    protected $_filesystemName = 'Model';

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'ZfsModelDirectory';
    }
}
