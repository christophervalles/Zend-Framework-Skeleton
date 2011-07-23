<?php
/**
 * @see Zend_Tool_Project_Context_Filesystem_File
 */
require_once 'Zend/Tool/Project/Context/Filesystem/File.php';

/**
 * This class creates a table for Zend-Framework-Skeleton
 *
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Context_ZFS_ZfsTableFile extends Zend_Tool_Project_Context_Filesystem_File
{

    /**
     * @var string
     */
    protected $_tableName = 'Base';

    /**
     * @var string
     */
    protected $_filesystemName = 'tableName';

    /**
     * init()
     *
     */
    public function init()
    {
        $this->_tableName = $this->_resource->getAttribute('tableName');
        $this->_filesystemName = ucfirst($this->_tableName) . '.php';
        parent::init();
    }

    /**
     * getPersistentAttributes
     *
     * @return array
     */
    public function getPersistentAttributes()
    {
        return array(
            'tableName' => $this->getTableName()
            );
    }

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'ZfsTableFile';
    }
    
	/**
	 * getTableName()
	 *
	 * @return string
	 */
    public function getTableName()
    {
        return $this->_tableName;
    }
    
	/**
	 * Creates contets for this table
	 * 
	 * @see Zend_Tool_Project_Context_Filesystem_File::getContents()
	 */
    public function getContents()
    {

        $className = ucfirst($this->_tableName);
		
        $codeGenFile = new Zend_CodeGenerator_Php_File(array(
            'fileName' => $this->getPath(),
            'classes' => array(
                new Zend_CodeGenerator_Php_Class(array(
                    'name' => 'App_Table_'.$className,
                	'extendedClass' => 'App_Table',
                ))
            )
        ));
        return $codeGenFile->generate();
    }

}
