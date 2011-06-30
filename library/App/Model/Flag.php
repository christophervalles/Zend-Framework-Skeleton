<?php
/**
 * Model that manages the flags (controller names) for defining
 * the Flags in the application
 *
 * @package backoffice_models
 * @copyright company
 */

class Flag extends App_Model
{
    /**
     * Column for the primary key
     *
     * @var string
     * @access protected
     */
    protected $_primary = 'id';
    
    /**
     * Holds the table's name
     *
     * @var string
     * @access protected
     */
    protected $_name = 'flags';
    
    /**
     * Holds the associated model class
     * 
     * @var string
     * @access protected
     */
    protected $_rowClass = 'App_Table_Flag';
    
    /**
     * Name of the column whose content will be displayed
     * on <select> widgets
     * 
     * @var string
     * @access protected
     */
    protected $_displayColumn = 'name';
    
    /**
     * Paths that are hardcoded in the application and should not
     * be displayed to the users for editing. These resources manage
     * very critical areas of the app
     * 
     * @var array
     * @access protected
     */
    protected $_hardcodedResources = array(
        'error',
        'index',
    );
    
    /**
     * Returns an array with all resources and their associated
     * privileges
     * 
     * @access public
     * @return array
     */
    public function getAllFlagsAndPrivileges(){
        $select = $this->_select();
        $select->order('name ASC');
        
        $rows = $this->fetchAll($select);
        $items = array();
        
        $privilegeModel = new Privilege();
        
        foreach($rows as $key => $flag){
            if(!in_array($flag->name, $this->_hardcodedResources)){
                $flag->privileges = $flag->findDependentRowset('Privilege');
                
                $items[] = $flag;
            }
        }
        
        return $items;
    }
    
    /**
     * Checks if a resource is registered. This is used only for
     * debugging purposes
     * 
     * @param string $resource 
     * @param string $privilege 
     * @access public
     * @return void
     */
    public function checkRegistered($resource, $privilege){
        $select = $this->_select();
        $select->setIntegrityCheck(FALSE);
        $select->from(array('r' => $this->_name));
        $select->join(array('p' => 'privileges'), 'r.id = p.flag_id');
        $select->where('r.name = ?', $resource);
        $select->where('p.name = ?', $privilege);
        $select->reset(Zend_Db_Table::COLUMNS);
        $select->columns(array('COUNT(r.id)'));
        
        $row = $this->fetchRow($select);
        
        return !is_null($row);
    }
    
    /**
     * Change the activation of a flag in a given environment
     *
     * @param int $id 
     * @param string $env 
     * @return void
     */
    public function toggleFlag($id, $env){
        $select = $this->_select();
        $select->where('id = ?', $id);
        
        $row = $this->fetchRow($select);
        
        switch($env){
            case APP_STATE_PRODUCTION:
                $row->active_on_prod = !$row->active_on_prod;
                break;
            default:
                $row->active_on_dev = !$row->active_on_dev;
                break;
        }
        
        $row->save();
    }
}