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
		$cImport->import('TOKOLOGO', 'TokologoCombined.csv', array(
				'asset_type_id' 			=> 3,
				'asset_sub_type_id' 		=> 4,
				'asset_description_id' 		=> 5,
				'asset_sub_description_id' 	=> 6,
				'identifier' 				=> 0,
				'details' 					=> 1,
				'condition_id' 				=> 18,
				'town_id' 					=> 10,
				'lat_start' 				=> 34,
				'long_start' 				=> 35,
				'lat_end' 					=> 36,
				'long_end' 					=> 37,
				'street_id' 				=> 40,
				'building_id' 				=> 12,
				'floor_id' 					=> 13,
				'room_id' 					=> 14,
				'owner_id' 					=> 19
		));
		exit(0);
	}


}

