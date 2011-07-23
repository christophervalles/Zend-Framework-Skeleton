<?php
/**
 * @see Zend_Tool_Project_Provider_Abstract
 */
require_once 'Zend/Tool/Project/Provider/Abstract.php';

/**
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Provider_ZfsView extends Zend_Tool_Project_Provider_View
{

    /**
     * createResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $actionName
     * @param string $controllerName
     * @param string $moduleName
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function createResource(Zend_Tool_Project_Profile $profile, $actionName, $controllerName, $moduleName = 'backoffice')
    {
        if (!is_string($actionName)) {
            require_once 'Zend/Tool/Project/Provider/Exception.php';
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsView::createResource() expects \"actionName\" is the name of a controller resource to create.');
        }

        if (!is_string($controllerName)) {
            require_once 'Zend/Tool/Project/Provider/Exception.php';
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsView::createResource() expects \"controllerName\" is the name of a controller resource to create.');
        }

    	if (!is_string($moduleName)) {
            require_once 'Zend/Tool/Project/Provider/Exception.php';
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsView::createResource() expects \"moduleName\" is the name of a module resource to create.');
        }

        $profileSearchParams = array('modulesDirectory', 'moduleDirectory' => array('moduleName' => $moduleName), 'viewsDirectory', 'viewScriptsDirectory');

        if (($viewScriptsDirectory = $profile->search($profileSearchParams)) === false) {
            require_once 'Zend/Tool/Project/Provider/Exception.php';
            throw new Zend_Tool_Project_Provider_Exception('This project does not have a viewScriptsDirectory resource.');
        }

        $profileSearchParams['viewControllerScriptsDirectory'] = array('forControllerName' => $controllerName);

        // @todo check if below is failing b/c of above search params
        if (($viewControllerScriptsDirectory = $viewScriptsDirectory->search($profileSearchParams)) === false) {
            $viewControllerScriptsDirectory = $viewScriptsDirectory->createResource('viewControllerScriptsDirectory', array('forControllerName' => $controllerName));
        }
        
        $profileSearchParams['zfsViewScriptFile'] = array('forActionName' => $actionName);
        
		if(($newViewScriptFile = $viewControllerScriptsDirectory->search($profileSearchParams)) === false){
        	$newViewScriptFile = $viewControllerScriptsDirectory->createResource('ZfsViewScriptFile', array('forActionName' => $actionName));
		}

        return $newViewScriptFile;
    }

    public static function hasResource(Zend_Tool_Project_Profile $profile, $controllerName, $actionNameOrSimpleName, $moduleName = 'backoffice'){
    	if ($moduleName == '' || $controllerName == '' || $actionNameOrSimpleName == '') {
            require_once 'Zend/Tool/Project/Provider/Exception.php';
            throw new Zend_Tool_Project_Provider_Exception('ModuleName and/or ControllerName and/or ActionName are empty.');
        }
        
        $profileSearchParams = array('modulesDirectory', 'moduleDirectory' => array('moduleName' => $moduleName), 'viewsDirectory', 'viewScriptsDirectory');

        if (($viewScriptsDirectory = $profile->search($profileSearchParams)) === false) {
            require_once 'Zend/Tool/Project/Provider/Exception.php';
            throw new Zend_Tool_Project_Provider_Exception('This project does not have a viewScriptsDirectory resource.');
        }
        
        $profileSearchParams['viewControllerScriptsDirectory'] = array('forControllerName' => $controllerName);
        
		//@FIXME Search returns false even on existance of search params.
        if (($viewControllerScriptsDirectory = $viewScriptsDirectory->search($profileSearchParams)) === false) {
            return false;
        }
        
        $profileSearchParams['zfsViewScriptFile'] = array('forActionName' => $actionNameOrSimpleName);
        
        return ($viewControllerScriptsDirectory->search($profileSearchParams) instanceof Zend_Tool_Project_Profile_Resource);
    }
    
    /**
     * create()
     *
     * @param string $controllerName
     * @param string $actionNameOrSimpleName
     */
    public function create($controllerName, $actionNameOrSimpleName, $module = 'backoffice')
    {

        if ($module == '' || $controllerName == '' || $actionNameOrSimpleName == '') {
            require_once 'Zend/Tool/Project/Provider/Exception.php';
            throw new Zend_Tool_Project_Provider_Exception('ModuleName and/or ControllerName and/or ActionName are empty.');
        }

        $profile = $this->_loadProfile();
		
        if(self::hasResource($this->_loadedProfile, $controllerName, $actionNameOrSimpleName, $module)){
        	require_once 'Zend/Tool/Project/Provider/Exception.php';
            throw new Zend_Tool_Project_Provider_Exception('This controller already has this view script.');
        }
        
        $view = self::createResource($profile, $actionNameOrSimpleName, $controllerName, $module);

        if ($this->_registry->getRequest()->isPretend()) {
            $this->_registry->getResponse(
                'Would create a view script in location ' . $view->getContext()->getPath()
                );
        } else {
            $this->_registry->getResponse(
                'Creating a view script in location ' . $view->getContext()->getPath()
                );
            $view->create();
            $this->_storeProfile();
        }

    }
    
}