<?php

/**
 * Table model for lib_country
 */
class Source_LibCountryController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_LibCountry';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'LibCountry';

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
    protected function setupLibCountryGrid()
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
        $this->view->result = array("LibCountry" => $response->result);
        $this->view->data   = array("LibCountry" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupLibCountryGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function libCountryGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupLibCountryGrid();
    }


}

