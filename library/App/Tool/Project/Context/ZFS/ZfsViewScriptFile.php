<?php
/**
 * @see Zend_Tool_Project_Context_Zf_ViewScriptFile
 */
require_once 'Zend/Tool/Project/Context/Zf/ViewScriptFile.php';

/**
 * This class creates view scripts for Zend-Framework-Skeleton
 *
 *
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Context_ZFS_ZfsViewScriptFile extends Zend_Tool_Project_Context_Zf_ViewScriptFile
{

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'ZfsViewScriptFile';
    }

    /**
     * getContents()
     *
     * @return string
     */
    public function getContents()
    {
        $contents = '';

        $controllerName = $this->_resource->getParentResource()->getAttribute('forControllerName');
        
        $viewsDirectoryResource = $this->_resource
            ->getParentResource() // view script
            ->getParentResource() // view controller dir
            ->getParentResource(); // views dir
        if ($viewsDirectoryResource->getParentResource()->getName() == 'ModuleDirectory') {
            $moduleName = $viewsDirectoryResource->getParentResource()->getModuleName();
        } else {
            $moduleName = 'default';
        }
        
        if ($this->_filesystemName == 'error.phtml') {  // should also check that the above directory is forController=error
            $contents .= <<<EOS
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Zend Framework Default Application</title>
</head>
<body>
  <h1>An error occurred</h1>
  <h2><?php echo \$this->message ?></h2>

  <?php if (isset(\$this->exception)): ?>
  
  <h3>Exception information:</h3>
  <p>
      <b>Message:</b> <?php echo \$this->exception->getMessage() ?>
  </p>

  <h3>Stack trace:</h3>
  <pre><?php echo \$this->exception->getTraceAsString() ?>
  </pre>

  <h3>Request Parameters:</h3>
  <pre><?php echo \$this->escape(var_export(\$this->request->getParams(), true)) ?>
  </pre>

  <?php endif ?>

</body>
</html>

EOS;
        } elseif ($this->_forActionName == 'index' && $moduleName == 'backoffice') {

            $contents =<<<EOS
<?php
/**
 * Displays rows from paginator
 *
 * @package backoffice_views
 * @subpackage backoffice_views_$moduleName
 * @copyright company
 */

\$config = array();

// messages
\$config['pageTitle'] = 'Manage $moduleName;
\$config['addMessage'] = 'Add new $moduleName; 
\$config['emptyMessage'] = 'There is not $moduleName registered at this time.';

// column names & indexes
\$config['columnNames'] = array(
    'ID',
);
\$config['columnIndexes'] = array(
    'id',
);

// render the default listing
echo \$this->partial(
    'partials/default-listing.phtml',
    array(
        'config' => \$config,
        'paginator' => \$this->paginator,
    )
);
EOS;
        } elseif(($this->_forActionName == 'add' || $this->_forActionName == 'edit') && $moduleName == 'backoffice'){
        	$contents =<<<EOS
<?php
/**
 * Add/edit
 *
 * @category backoffice
 * @package backoffice_views
 * @subpackage backoffice_views_$controllerName
 * @copyright company
 */

\$config = array();
\$config['pageTitle'] = 'Add new [SET_THIS]';

echo \$this->partial(
    'partials/default-add-edit.phtml',
    array(
        'form' => \$this->form,
        'config' => \$config
    )
);
EOS;
        } elseif( $this->_forActionName == 'delete' && $moduleName == 'backoffice'){
        	$contents = <<<EOS
<?php
/**
 * Delete 
 *
 * @category backoffice
 * @package backoffice_views
 * @subpackage backoffice_views_$controllerName
 * @copyright company
 */

\$config = array();
\$config['pageTitle'] = 'Delete [SET_THIS]';
\$config['areYouSureMessage'] = 'Are you sure you want to delete [SET_THIS]?';

// column names & indexes
\$config['columnNames'] = array(
    'Name',
);
\$config['columnIndexes'] = array(
    'name',
);

echo \$this->partial(
    'partials/default-delete.phtml',
    array(
        'form' => \$this->form,
        'config' => \$config,
        'item' => \$this->item
    )
);
EOS;
        } else {
            $actionName = $this->_forActionName;
            $contents = <<<EOS
<br /><br />
<div id="view-content">
	<p>View script for controller <b>$controllerName</b> and script/action name <b>$actionName</b></p>
</div>
EOS;
        }
        return $contents;
    }

}