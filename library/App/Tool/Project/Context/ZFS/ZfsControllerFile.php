<?php
/**
 * This class creates a Zend-Framework-Skeleton controller
 *
 * @category   App
 * @package    App_Tool
 * @copyright  Copyright company
 */
class App_Tool_Project_Context_ZFS_ZfsControllerFile extends Zend_Tool_Project_Context_Zf_ControllerFile
{

	public function getName(){
		return 'ZfsControllerFile';
	}
    
    /**
     * getContents()
     *
     * @return string
     */
    public function getContents()
    {
        $filter = new Zend_Filter_Word_DashToCamelCase();
        $moduleName = ucfirst($this->_moduleName);
        $className = ucfirst($this->_controllerName) . 'Controller';

        $codeGenFile = new Zend_CodeGenerator_Php_File(array(
            'fileName' => $this->getPath(),
            'classes' => array(
                new Zend_CodeGenerator_Php_Class(array(
                    'name' => $className,
                    'extendedClass' => 'App_'.$moduleName.'_Controller',
                    'methods' => array(
                        new Zend_CodeGenerator_Php_Method(array(
                            'name' => 'init',
                            'body' => '/* Initialize action controller here */',
                            ))
                        )
                    ))
                )
            ));


        if ($className == 'ErrorController') {

            $codeGenFile = new Zend_CodeGenerator_Php_File(array(
                'fileName' => $this->getPath(),
                'classes' => array(
                    new Zend_CodeGenerator_Php_Class(array(
                        'name' => $className,
                        'extendedClass' => 'App_'.$moduleName.'_Controller',
                    	'properties' => array(
                    		new Zend_CodeGenerator_Php_Property(array(
                    			'name'	=>	'_dispatch404s',
                    			'visibility' => Zend_CodeGenerator_Php_Property::VISIBILITY_PROTECTED,
                    			'defaultValue' => array(
									Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE,
									Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER,
									Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION
								), 
                    		))
                    	),
                        'methods' => array(
                    		new Zend_CodeGenerator_Php_Method(
                    			array(
                    				'name'	=> 'init',
                    				'body'	=> <<<EOS
parent::init();
        
\$this->_helper->layout()->setLayout('layout');
EOS
                    		)),
                            new Zend_CodeGenerator_Php_Method(array(
                                'name' => 'errorAction',
                                'body' => <<<EOS
\$errorInfo = \$this->_getParam('error_handler');

if (in_array(\$errorInfo->type, \$this->_dispatch404s)) {
	\$this->_dispatch404();
	return;
}
        
\$this->getResponse()->setRawHeader('HTTP/1.1 500 Internal Server Error');
        
\$this->title = 'Internal Server Error';
        
\$this->view->exception = \$errorInfo->exception;
EOS
                                )),
                            new Zend_CodeGenerator_Php_Method(array(
                                'name' => 'flagflippersAction',
                                'body' => <<<EOS
if (Zend_Registry::get('IS_DEVELOPMENT')) {
	\$this->title = 'Flag and Flipper not found';
            
	\$this->view->originalController = \$this->_getParam('originalController');
	\$this->view->originalAction = \$this->_getParam('originalAction');
} else {
	\$this->_dispatch404();
}
EOS
                                )),
                            new Zend_CodeGenerator_Php_Method(array(
                            	'name'	=> 'forbiddenAction',
                            	'body'	=> '$this->title = \'Forbidden\';'
                            )),
                            new Zend_CodeGenerator_Php_Method(array(
                            	'name'	=> '_dispatch404',
                            	'visibility' => Zend_CodeGenerator_Php_Method::VISIBILITY_PROTECTED,
                            	'body'	=> <<<EOS
\$this->title = 'Page not found';
\$this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
        
\$this->render('error-404');
EOS
                            )),
                            )
                        ))
                    )
                ));

        }

        // store the generator into the registry so that the addAction command can use the same object later
        Zend_CodeGenerator_Php_File::registerFileCodeGenerator($codeGenFile); // REQUIRES filename to be set
        return $codeGenFile->generate();
    }

}