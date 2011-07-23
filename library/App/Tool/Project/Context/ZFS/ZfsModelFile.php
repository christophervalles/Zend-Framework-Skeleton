<?php
/**
 * @see Zend_Tool_Project_Context_Filesystem_File
 */
require_once 'Zend/Tool/Project/Context/Filesystem/File.php';

/**
 * This class creates a model for Zend-Framework-Skeleton
 *
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Context_ZFS_ZfsModelFile extends Zend_Tool_Project_Context_Filesystem_File
{

    /**
     * @var string
     */
    protected $_modelName = 'Base';

    /**
     * @var string
     */
    protected $_filesystemName = 'modelName';

    /**
     * init()
     *
     */
    public function init()
    {
        $this->_modelName = $this->_resource->getAttribute('modelName');
        $this->_filesystemName = ucfirst($this->_modelName) . '.php';
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
            'modelName' => $this->getModelName()
            );
    }

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'ZfsModelFile';
    }
    
	/**
	 * getModelName()
	 *
	 * @return string
	 */
    public function getModelName()
    {
        return $this->_modelName;
    }
	/**
	 * Creates contets for this model
	 * 
	 * @see Zend_Tool_Project_Context_Filesystem_File::getContents()
	 */
    public function getContents()
    {

        $className = ucfirst($this->_modelName);
		
        $tableName = $this->camelCaseToUnderscore($this->_modelName); 
		
        $codeGenFile = new Zend_CodeGenerator_Php_File(array(
            'fileName' => $this->getPath(),
            'classes' => array(
                new Zend_CodeGenerator_Php_Class(array(
                    'name' => $className,
                	'extendedClass' => 'App_Model',
                	'properties' => array(
                        new Zend_CodeGenerator_Php_Property(array(
                            'name' => '_primary',
                            'visibility' => Zend_CodeGenerator_Php_Property::VISIBILITY_PROTECTED,
                            'defaultValue' => 'id'
                            )
                        ),
                        new Zend_CodeGenerator_Php_Property(array(
                            'name' => '_name',
                            'visibility' => Zend_CodeGenerator_Php_Property::VISIBILITY_PROTECTED,
                            'defaultValue' => $tableName
                            )
                        ),
                        new Zend_CodeGenerator_Php_Property(array(
                            'name' => '_rowClass',
                            'visibility' => Zend_CodeGenerator_Php_Property::VISIBILITY_PROTECTED,
                            'defaultValue' => 'App_Table_'.$className
                            )
                        )

                    )
                ))
            )
        ));
        return $codeGenFile->generate();
    }

	/**
     * Converts a camelCasedString to lower cased string with
     * words separated by underscores
     *
     * Ex: myCamelCasedString => my_camel_cased_string
     * 
     * @param string $string 
     * @access public
     * @return string
     */
    protected function camelCaseToUnderscore($string){
        $string = preg_replace('/([A-Z]+)([A-Z])/','$1_$2', $string);
        $string = preg_replace('/([a-z])([A-Z])/', '$1_$2', $string);
        
        return strtolower($string);
    }

}
