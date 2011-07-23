<?php
/**
 * @see Zend_Tool_Project_Provider_Manifest
 */
require_once 'Zend/Tool/Project/Provider/Manifest.php';

/**
 * @see App_Tool_Project_Provider_ZfsModel
 */
require_once 'App/Tool/Project/Provider/ZfsModel.php';

/**
 * @see App_Tool_Project_Provider_ZfsTable
 */
require_once 'App/Tool/Project/Provider/ZfsTable.php';

/**
 * @see App_Tool_Project_Provider_ZfsForm
 */
require_once 'App/Tool/Project/Provider/ZfsForm.php';

/**
 * @see App_Tool_Project_Provider_ZfsController
 */
require_once 'App/Tool/Project/Provider/ZfsController.php';

/**
 * @see App_Tool_Project_Provider_ZfsController
 */
require_once 'App/Tool/Project/Provider/ZfsAction.php';

/**
 * @see App_Tool_Project_Provider_ZfsController
 */
require_once 'App/Tool/Project/Provider/ZfsView.php';

/**
 * @category   App
 * @package    App_Tool
 * @copyright  company
 */
class App_Tool_Project_Provider_Manifest extends Zend_Tool_Project_Provider_Manifest
{

    /**
     * getProviders()
     *
     * @return array Array of Providers
     */
    public function getProviders()
    {
        $this->addContexts();
        return array(
            new App_Tool_Project_Provider_ZfsModel(),
            new App_Tool_Project_Provider_ZfsTable(),
            new App_Tool_Project_Provider_ZfsForm(),
            new App_Tool_Project_Provider_ZfsController(),
            new App_Tool_Project_Provider_ZfsAction(),
            new App_Tool_Project_Provider_ZfsView()
        );
    }
    public function addContexts(){
        // add contexts for ZFS specific directory structure.
        $contextRegistry = Zend_Tool_Project_Context_Repository::getInstance();
        $contextRegistry->addContextsFromDirectory(
            dirname(dirname(__FILE__)) . '/Context/ZFS/', 'App_Tool_Project_Context_ZFS_'
        );
    }
}
