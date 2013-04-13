<?php

/**
 * Table model for asset
 */
class Source_AssetController extends Struct_Abstract_Controller
{

    /**
     * Default object for DataAccess methods.
     */
    protected $_defaultObjectName = 'Object_Asset';

    /**
     * Default session namespace for the view.
     */
    protected $_sessionNamespace = 'Asset';

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
    protected function setupAssetGrid()
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
        $this->view->result = array("Asset" => $response->result);
        $this->view->data   = array("Asset" => $response->data);
    }

    /**
     * Default page view for this theme.
     */
    public function indexAction()
    {
        $this->setupAssetGrid();
        $this->dataContext = "listLocation";
        $this->listDataReturnView("Object_Location");
        $this->dataContext = "listTown";
        $this->listDataReturnView("Object_Town");
        $this->dataContext = "listStreet";
        $this->listDataReturnView("Object_Street");
        $this->dataContext = "listBuilding";
        $this->listDataReturnView("Object_Building");
        $this->dataContext = "listFloor";
        $this->listDataReturnView("Object_Floor");
        $this->dataContext = "listRoom";
        $this->listDataReturnView("Object_Room");
        $this->dataContext = "listOwner";
        $this->listDataReturnView("Object_Owner");
        $this->dataContext = "listAssetType";
        $this->listDataReturnView("Object_AssetType");
        $this->dataContext = "listAssetSubType";
        $this->listDataReturnView("Object_AssetSubType");
        $this->dataContext = "listAssetDescription";
        $this->listDataReturnView("Object_AssetDescription");
        $this->dataContext = "listAssetSubDescription";
        $this->listDataReturnView("Object_AssetSubDescription");
        $this->dataContext = "listMaterial";
        $this->listDataReturnView("Object_Material");
        $this->dataContext = "listStreetLightType";
        $this->listDataReturnView("Object_StreetLightType");
        $this->dataContext = "listCondition";
        $this->listDataReturnView("Object_Condition");
        $this->dataContext = "listPreviousCondition";
        $this->listDataReturnView("Object_Condition");
    }

    /**
     * Retrieve data grid for display.
     */
    public function assetGridAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->setupAssetGrid();
    }


}

