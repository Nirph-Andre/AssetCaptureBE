<?php




class Util_TesterController extends Struct_Abstract_Controller
{


	protected $_defaultObjectName = 'Object_Admin';



	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
		#-> Try something new today ;)
		$cImport = new Component_Import();
		$cImport->import('TOKOLOGO', 'Tokologo.csv', array(
				'asset_type_id' 			=> 3,
				'asset_sub_type_id' 		=> 4,
				'asset_description_id' 		=> 1,
				'asset_sub_description_id' 	=> 5,
				'identifier' 				=> 0,
				'details' 					=> 6,
				'condition_id' 				=> 19,
				'town_id' 					=> 10,
				'lat_start' 				=> 35,
				'long_start' 				=> 36,
				'lat_end' 					=> 37,
				'long_end' 					=> 38,
				'street_id' 				=> 41,
				'building_id' 				=> 13,
				'floor_id' 					=> 14,
				'room_id' 					=> 15,
				'owner_id' 					=> 20
		));
		exit(0);
	}


}

