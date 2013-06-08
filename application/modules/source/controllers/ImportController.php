<?php

/**
 * Table model for import
 */
class Source_ImportController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Import';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Import';

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
    protected function setupImportGrid()
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
        $this->view->result = array("Import" => $response->result);
        $this->view->data   = array("Import" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupImportGrid();
    }

    /**
     * Retrieve data grid for display.
     */
    public function importGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupImportGrid();
    }


}

