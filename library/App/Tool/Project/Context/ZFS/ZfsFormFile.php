<?php
/**
 * @see Zend_Tool_Project_Context_Filesystem_File
 */
require_once 'Zend/Tool/Project/Context/Filesystem/File.php';

/**
 * This class creates a form for Zend-Framework-Skeleton
 *
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Context_ZFS_ZfsFormFile extends Zend_Tool_Project_Context_Filesystem_File
{

    /**
     * @var string
     */
    protected $_formName = 'Base';

    /**
     * @var string
     */
    protected $_moduleName = 'Backoffice';
    
    /**
     * @var string
     */
    protected $_filesystemName = 'formName';

    /**
     * init()
     *
     */
    public function init()
    {
        $this->_formName = $this->_resource->getAttribute('formName');
        $this->_moduleName = $this->_resource->getAttribute('moduleName');
        $this->_filesystemName = ucfirst($this->_formName) . '.php';
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
            'formName' => $this->getFormName(),
        	'moduleName' => $this->getModuleName()
            );
    }

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'ZfsFormFile';
    }
    
	/**
	 * getFormName()
	 *
	 * @return string
	 */
    public function getFormName()
    {
        return $this->_formName;
    }
    
    public function getModuleName()
    {
    	return $this->_moduleName;
    }
    
	/**
	 * Creates contets for this form
	 * 
	 * @see Zend_Tool_Project_Context_Filesystem_File::getContents()
	 */
    public function getContents()
    {

        $className = ucfirst($this->_formName);
        $moduleName = ucfirst($this->_moduleName);
		
        $codeGenFile = new Zend_CodeGenerator_Php_File(array(
            'fileName' => $this->getPath(),
            'classes' => array(
                new Zend_CodeGenerator_Php_Class(array(
                    'name' => $className,
                	'extendedClass' => 'App_'.$moduleName.'_Form',
                	'methods'	=> array(
                		new Zend_CodeGenerator_Php_Method(
                			array(
                				'name'	=> 'init',
                				'body'	=> 'parent::init();',
                				'visibility'	=> Zend_CodeGenerator_Php_Method::VISIBILITY_PUBLIC
                			)
                		)
                	)
                ))
            )
        ));
        return $codeGenFile->generate();
    }

}
