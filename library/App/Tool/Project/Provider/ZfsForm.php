<?php
/**
 * Creates form for Zend-Framework-Skeleton
 * 
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Provider_ZfsForm extends Zend_Tool_Project_Provider_Abstract implements Zend_Tool_Framework_Provider_Pretendable
{

	/**
	 * createResource()
	 * 
	 * @param Zend_Tool_Project_Profile $profile
	 * @param string $formName
	 * @param string $moduleName
	 * @throws Zend_Tool_Project_Provider_Exception
	 */
    public static function createResource(Zend_Tool_Project_Profile $profile, $formName, $module = 'backoffice')
    {
        if (!is_string($formName)) {
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsForm::createResource() expects \"formName\" is the name of a form resource to create.');
        }

    	if (!is_string($module)) {
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsForm::createResource() expects \"module\" is the name of a module to create form in it.');
        }
        
        if (!($formsDirectory = self::_getFormsDirectoryResource($profile, $module))) {
            
            $exceptionMessage = 'forms Directory was not found in "'.$module.'"';
            
            throw new Zend_Tool_Project_Provider_Exception($exceptionMessage);
        }
		
        $newForm = $formsDirectory->createResource('ZfsFormFile',
            			array('formName' => $formName,'moduleName' => $module)
            		);

        return $newForm;
    }

    /**
     * hasResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $formName
     * @param string $module
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function hasResource(Zend_Tool_Project_Profile $profile, $formName, $module = 'backoffice')
    {
        if (!is_string($formName)) {
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsForm::hasResource() expects \"formName\" is the name of a form resource to check for existence.');
        }

    	if (!is_string($module)) {
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsForm::hasResource() expects \"module\" is the name of a module to create form in it.');
        }
        
        $formsDirectory = self::_getFormsDirectoryResource($profile, $module);
        
        if (!$formsDirectory instanceof Zend_Tool_Project_Profile_Resource) {
            return false;
        }
        
        return (($formsDirectory->search(array('zfsFormFile' => array('formName' => $formName)))) instanceof Zend_Tool_Project_Profile_Resource);
    }

    /**
     * _getFormsDirectoryResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @return Zend_Tool_Project_Profile_Resource
     */
    protected static function _getFormsDirectoryResource(Zend_Tool_Project_Profile $profile, $module)
    {

        $profileSearchParams = array('modulesDirectory', 'moduleDirectory' => array('moduleName' => $module),'formsDirectory');

        return $profile->search($profileSearchParams);
    }

    /**
     * Create a new form
     *
     * @param string $name
     * @param string $module
     * @param boolean $rowTableClassIncluded
     */
    public function create($name, $module = 'backoffice')
    {
        $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

        $originalName = $name;

        $name = ucwords($name);

        // determine if testing is enabled in the project
        $testingEnabled = false; //Zend_Tool_Project_Provider_Test::isTestingEnabled($this->_loadedProfile);
        $testFormResource = null;

        // Check that there is not a dash or underscore, return if doesnt match regex
        if (preg_match('#[_-]#', $name)) {
            throw new Zend_Tool_Project_Provider_Exception('Form names should be camel cased.');
        }

        if (self::hasResource($this->_loadedProfile, $name, $module)) {
            throw new Zend_Tool_Project_Provider_Exception('This module already has a form named ' . $name);
        }
    	
        // get request/response object
        $request = $this->_registry->getRequest();
        $response = $this->_registry->getResponse();

        // alert the user about inline converted names
        $tense = (($request->isPretend()) ? 'would be' : 'is');

        if ($name !== $originalName) {
            $response->appendContent(
                'Note: The canonical form name that ' . $tense
                    . ' used with other providers is "' . $name . '";'
                    . ' not "' . $originalName . '" as supplied',
                array('color' => array('yellow'))
                );
        }

        try {
            $formResource = self::createResource($this->_loadedProfile, $name, $module);
            
            if ($testingEnabled) {
                 //$testFormResource = Zend_Tool_Project_Provider_Test::createApplicationResource($this->_loadedProfile, $name, 'index');
            }

        } catch (Exception $e) {
            $response->setException($e);
            return;
        }

        // do the creation
        if ($request->isPretend()) {

            $response->appendContent('Would create a form at '  . $formResource->getContext()->getPath());

        } else {

            $response->appendContent('Creating a form at ' . $formResource->getContext()->getPath());
            $formResource->create();

            if ($testFormResource) {
                $response->appendContent('Creating a form test file at ' . $testFormResource->getContext()->getPath());
                $testFormResource->create();
            }

            $this->_storeProfile();
        }

    }


}
