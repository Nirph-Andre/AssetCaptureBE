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
		$cImport->importSewer();
	}


}

