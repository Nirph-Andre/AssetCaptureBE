<?php

/**
 * Table model for lib_person
 */
class Source_LibPersonController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibPerson';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibPerson';

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
    protected function setupLibPersonGrid()
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
        $this->view->result = array("LibPerson" => $response->result);
        $this->view->data   = array("LibPerson" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibPersonGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libPersonGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibPersonGrid();
    }


}

