<?php

/**
 * Table model for import_data
 */
class Source_ImportDataController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_ImportData';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'ImportData';

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
    protected function setupImportDataGrid()
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
        $this->view->result = array("ImportData" => $response->result);
        $this->view->data   = array("ImportData" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupImportDataGrid();
        $this->dataContext = "listImport";
        $this->listDataReturnView("Object_Import");
    }

    /**
     * Retrieve data grid for display.
     */
    public function importDataGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupImportDataGrid();
    }


}

