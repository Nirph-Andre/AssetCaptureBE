<?php

/**
 * Table model for photo
 */
class Source_PhotoController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Photo';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Photo';

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
    protected function setupPhotoGrid()
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
        $this->view->result = array("Photo" => $response->result);
        $this->view->data   = array("Photo" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupPhotoGrid();
        $this->dataContext = "listAsset";
        $this->listDataReturnView("Object_Asset");
    }

    /**
     * Retrieve data grid for display.
     */
    public function photoGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupPhotoGrid();
    }


}

