<?php
/**
 * Creates model for Zend-Framework-Skeleton
 * 
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Provider_ZfsModel extends Zend_Tool_Project_Provider_Abstract implements Zend_Tool_Framework_Provider_Pretendable
{

    public static function createResource(Zend_Tool_Project_Profile $profile, $modelName)
    {
        if (!is_string($modelName)) {
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsModel::createResource() expects \"modelName\" is the name of a model resource to create.');
        }

        if (!($modelsDirectory = self::_getModelsDirectoryResource($profile))) {
            
            $exceptionMessage = 'App/Model Directory was not found.';
            
            throw new Zend_Tool_Project_Provider_Exception($exceptionMessage);
        }
		
        $newModel = $modelsDirectory->createResource('ZfsModelFile',
            			array('modelName' => $modelName)
            		);

        return $newModel;
    }

    /**
     * hasResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $modelName
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function hasResource(Zend_Tool_Project_Profile $profile, $modelName)
    {
        if (!is_string($modelName)) {
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsModel::createResource() expects \"modelName\" is the name of a model resource to check for existence.');
        }

        $modelsDirectory = self::_getModelsDirectoryResource($profile);
        
        if (!$modelsDirectory instanceof Zend_Tool_Project_Profile_Resource) {
            return false;
        }
        
        return (($modelsDirectory->search(array('zfsModelFile' => array('modelName' => $modelName)))) instanceof Zend_Tool_Project_Profile_Resource);
    }

    /**
     * _getModelsDirectoryResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @return Zend_Tool_Project_Profile_Resource
     */
    protected static function _getModelsDirectoryResource(Zend_Tool_Project_Profile $profile)
    {

        $profileSearchParams = array('appLibraryDirectory','zfsModelDirectory');

        return $profile->search($profileSearchParams);
    }

    /**
     * Create a new model
     *
     * @param string $name
     * @param boolean $rowTableClassIncluded
     */
    public function create($name, $rowTableClassIncluded = true)
    {
        $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

        $originalName = $name;

        $name = ucwords($name);

        // determine if testing is enabled in the project
        $testingEnabled = false; //Zend_Tool_Project_Provider_Test::isTestingEnabled($this->_loadedProfile);
        $testModelResource = null;

        // Check that there is not a dash or underscore, return if doesnt match regex
        if (preg_match('#[_-]#', $name)) {
            throw new Zend_Tool_Project_Provider_Exception('Model names should be camel cased.');
        }

        if (self::hasResource($this->_loadedProfile, $name)) {
            throw new Zend_Tool_Project_Provider_Exception('This project already has a model named ' . $name);
        }
    	
        // get request/response object
        $request = $this->_registry->getRequest();
        $response = $this->_registry->getResponse();

        // alert the user about inline converted names
        $tense = (($request->isPretend()) ? 'would be' : 'is');

        if ($name !== $originalName) {
            $response->appendContent(
                'Note: The canonical model name that ' . $tense
                    . ' used with other providers is "' . $name . '";'
                    . ' not "' . $originalName . '" as supplied',
                array('color' => array('yellow'))
                );
        }

        try {
            $modelResource = self::createResource($this->_loadedProfile, $name);
            
            if($rowTableClassIncluded){
            	$tableResource = App_Tool_Project_Provider_ZfsTable::createResource($this->_loadedProfile, $name);
            }
            
            if ($testingEnabled) {
                 //$testModelResource = Zend_Tool_Project_Provider_Test::createApplicationResource($this->_loadedProfile, $name, 'index');
            }

        } catch (Exception $e) {
            $response->setException($e);
            return;
        }

        // do the creation
        if ($request->isPretend()) {

            $response->appendContent('Would create a model at '  . $modelResource->getContext()->getPath());
			if(isset($tableResource)){
				$response->appendContent('Would create a table at ' . $tableResource->getContext()->getPath());
			}
            if ($testModelResource) {
                $response->appendContent('Would create a model test file at ' . $testModelResource->getContext()->getPath());
            }

        } else {

            $response->appendContent('Creating a model at ' . $modelResource->getContext()->getPath());
            $modelResource->create();
			
            if(isset($tableResource)){
            	$response->appendContent('Creating a table at ' . $tableResource->getContext()->getPath());
            	$tableResource->create();
            }
            if ($testModelResource) {
                $response->appendContent('Creating a model test file at ' . $testModelResource->getContext()->getPath());
                $testModelResource->create();
            }

            $this->_storeProfile();
        }

    }


}
