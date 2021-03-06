<?php

/**
 * Table model for location
 */
class Source_LocationController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Location';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Location';

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
    protected function setupLocationGrid()
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
        $this->view->result = array("Location" => $response->result);
        $this->view->data   = array("Location" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLocationGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function locationGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLocationGrid();
    }


}

