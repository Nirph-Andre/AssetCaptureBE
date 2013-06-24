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
		echo 'NOOOOOO imports yet ;)';
		exit();
		$cImport = new Component_Import();
		$cImport->import('TOKOLOGO', 'Tokologo.csv', array(
				'asset_type_id' 			=> 3,
				'asset_sub_type_id' 		=> 4,
				'asset_description_id' 		=> 1,
				'asset_sub_description_id' 	=> 5,
				'identifier' 				=> 0,
				'details' 					=> 6,
				'detail2' 					=> 7,
				'serial' 					=> 2,
				'condition_id' 				=> 20,
				'town_id' 					=> 11,
				'lat_start' 				=> 36,
				'long_start' 				=> 37,
				'lat_end' 					=> 38,
				'long_end' 					=> 39,
				'street_id' 				=> 42,
				'building_id' 				=> 14,
				'floor_id' 					=> 15,
				'room_id' 					=> 16,
				'owner_id' 					=> 21
		));
		exit(0);
	}

	public function exportAction()
	{
		$cImport = new Component_Import();
		$cImport->export(1, 1, 'TokEx.csv');
		echo 'Done';
		exit();
	}




}

