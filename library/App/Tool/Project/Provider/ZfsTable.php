<?php
/**
 * Creates table class for Zend-Framework-Skeleton
 * 
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Provider_ZfsTable extends Zend_Tool_Project_Provider_Abstract implements Zend_Tool_Framework_Provider_Pretendable
{

    public static function createResource(Zend_Tool_Project_Profile $profile, $tableName)
    {
        if (!is_string($tableName)) {
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsTable::createResource() expects \"tableName\" is the name of a table resource to create.');
        }

        if (!($tablesDirectory = self::_getTablesDirectoryResource($profile))) {
            
            $exceptionMessage = 'App/Table Directory was not found.';
            
            throw new Zend_Tool_Project_Provider_Exception($exceptionMessage);
        }
		
        $newTable = $tablesDirectory->createResource('ZfsTableFile',
            			array('tableName' => $tableName)
            		);

        return $newTable;
    }

    /**
     * hasResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $tableName
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function hasResource(Zend_Tool_Project_Profile $profile, $tableName)
    {
        if (!is_string($tableName)) {
            throw new Zend_Tool_Project_Provider_Exception('App_Tool_Project_Provider_ZfsTable::createResource() expects \"tableName\" is the name of a table resource to check for existence.');
        }

        $tablesDirectory = self::_getTablesDirectoryResource($profile);
        if (!$tablesDirectory instanceof Zend_Tool_Project_Profile_Resource) {
            return false;
        }
        
        return (($tablesDirectory->search(array('zfsTableFile' => array('tableName' => $tableName)))) instanceof Zend_Tool_Project_Profile_Resource);
    }

    /**
     * _getTablesDirectoryResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @return Zend_Tool_Project_Profile_Resource
     */
    protected static function _getTablesDirectoryResource(Zend_Tool_Project_Profile $profile)
    {
        
        $profileSearchParams = array('appLibraryDirectory','zfsTableDirectory');

        return $profile->search($profileSearchParams);
    }

    /**
     * Create a new table
     *
     * @param string $name
     */
    public function create($name)
    {
        $this->_loadProfile(self::NO_PROFILE_THROW_EXCEPTION);

        $originalName = $name;

        $name = ucwords($name);

        // determine if testing is enabled in the project
        $testingEnabled = false; //Zend_Tool_Project_Provider_Test::isTestingEnabled($this->_loadedProfile);
        $testTableResource = null;

        // Check that there is not a dash or underscore, return if doesnt match regex
        if (preg_match('#[_-]#', $name)) {
            throw new Zend_Tool_Project_Provider_Exception('Table names should be camel cased.');
        }

        if (self::hasResource($this->_loadedProfile, $name)) {
            throw new Zend_Tool_Project_Provider_Exception('This project already has a table named ' . $name);
        }

        // get request/response object
        $request = $this->_registry->getRequest();
        $response = $this->_registry->getResponse();

        // alert the user about inline converted names
        $tense = (($request->isPretend()) ? 'would be' : 'is');

        if ($name !== $originalName) {
            $response->appendContent(
                'Note: The canonical table name that ' . $tense
                    . ' used with other providers is "' . $name . '";'
                    . ' not "' . $originalName . '" as supplied',
                array('color' => array('yellow'))
                );
        }

        try {
            $tableResource = self::createResource($this->_loadedProfile, $name);

            if ($testingEnabled) {
                 //$testTableResource = Zend_Tool_Project_Provider_Test::createApplicationResource($this->_loadedProfile, $name, 'index');
            }

        } catch (Exception $e) {
            $response->setException($e);
            return;
        }

        // do the creation
        if ($request->isPretend()) {

            $response->appendContent('Would create a table at '  . $tableResource->getContext()->getPath());

            if ($testTableResource) {
                $response->appendContent('Would create a table test file at ' . $testTableResource->getContext()->getPath());
            }

        } else {

            $response->appendContent('Creating a table at ' . $tableResource->getContext()->getPath());
            $tableResource->create();

            if ($testTableResource) {
                $response->appendContent('Creating a table test file at ' . $testTableResource->getContext()->getPath());
                $testTableResource->create();
            }

            $this->_storeProfile();
        }

    }


}
