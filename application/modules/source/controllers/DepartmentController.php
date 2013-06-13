<?php

/**
 * Table model for department
 */
class Source_DepartmentController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Department';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Department';

    /**
     * Action controller initializer.
     */
    public function init()
    {
        if (!Struct_Registry::isAuthenticated())
        {
        	header("Location: /login");
        	exit();
        }
    }

    /**
     * Setup data grid on default value object.
     */
    protected function setupDepartmentGrid()
    {
        $sqf = new Struct_Util_SmartQueryFilter();
        $response = $sqf->handleGrid(
        	$this->getRequest(), false, $this->_defaultObjectName,
        	array(), // default filters
        	array(), // default order
        	array(), // exclude
        	array(), // chain
        	10, "Json"
        );
        $this->view->result = array("Department" => $response->result);
        $this->view->data   = array("Department" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupDepartmentGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function departmentGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupDepartmentGrid();
    }


}

