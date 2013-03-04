<?php

class Api_DataController extends Struct_Abstract_Controller
{
	
	/**
	 * @var string
	 */
	protected $_defaultObjectName = '';
	
	/**
	 * @var string
	 */
	protected $_nameSpace = '';
	
	/**
	 * @var Struct_Abstract_DataAccess
	 */
	protected $_object = false;
	
	/**
	 * @var array
	 */
	protected $_data   = false;
	
	/**
	 * @var array
	 */
	protected $_options   = false;
	
	
	
	public function init()
	{
		/* if (!Struct_Registry::isAuthenticated() && !defined('DEBUG_UNITTEST'))
		{
			$this->jsonResult(Struct_ActionFeedback::error(
					'Data call without authentication.',
					'You are not authenticated, please login.'
			));
		} */
		$this->_helper->layout()->disableLayout();
		//$this->_helper->viewRenderer->setNoRender(true);
		$params = $this->getRequest()->getParams();
		if (count($params) <= 3)
		{
			$params = json_decode(file_get_contents('php://input'), true);
			if (empty($params))
			{
				$this->jsonResult(Struct_ActionFeedback::error(
						'No data context',
						'No data context specified, server could not service the request.'
						));
			}
		}
		else
		{
			unset($params['module']);
			unset($params['controller']);
			unset($params['action']);
		}
		$action = $this->getRequest()->getActionName();
		$this->_options = array();
		if ('find-query' == $action
				|| 'find-many' == $action
				|| 'list' == $action
				|| 'vehicle-type-from-type' == $action
				|| 'vehicle-make-from-type' == $action
				|| 'drop-list' == $action
				|| 'options' == $action
				|| 'grid-query' == $action)
		{
			$this->_data = array();
			list($this->_nameSpace, $this->_options) = each($params);
		}
		elseif ('find-all' == $action)
		{
			$this->_data = array();
			list($this->_nameSpace, $this->_options) = each($params);
			$this->_options = array();
		}
		else
		{
			$this->_options = isset($params['Options'])
				? $params['Options']
				: array();
			list($this->_nameSpace, $this->_data) = each($params);
			if (empty($this->_options) && isset($this->_data['Options']))
			{
				$this->_options = $this->_data['Options'];
				unset($this->_data['Options']);
			}
		}
		
		$class = 'Object_' . str_replace('DropList', '', ucfirst($this->_nameSpace));
		$this->_object = new $class();
		return true;
	}

	public function indexAction()
	{
		$this->jsonResult(Struct_ActionFeedback::success());
	}

	public function createAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'Create',
								$this->_data,
								$this->_options
								))
				);
	}

	public function updateAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'Update',
								$this->_data,
								$this->_options
								))
				);
	}

	public function deleteAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'Delete',
								$this->_data,
								$this->_options
								))
				);
	}

	public function findAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'Find',
								$this->_data,
								$this->_options
								))
				);
	}

	public function findQueryAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'View',
								$this->_data,
								$this->_options
								))
				);
	}

	public function findManyAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'Grid',
								$this->_data,
								$this->_options
								))
				);
	}

	public function findAllAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'Grid',
								$this->_data,
								$this->_options
								))
				);
	}

	public function listAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'List',
								$this->_data,
								$this->_options
								))
				);
	}

	public function dropListAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'List',
								$this->_data,
								$this->_options
								))
				);
	}

	public function gridQueryAction()
	{
		$this->jsonNsResult(
				$this->_object->process(
						new Struct_ActionRequest(
								'Grid',
								$this->_data,
								$this->_options
								))
				);
	}

	public function optionsAction()
	{
		$data = $this->_object->process(
						new Struct_ActionRequest(
								'List',
								$this->_data,
								$this->_options
								)
				)->data;
		$selected = isset($this->_options['select'])
			? $this->_options['select']
			: false;
		$html = isset($this->_options['title'])
			? '<option value="">' . $this->_options['title'] . '</option>'
			: '';
		$valueField = isset($this->_options['valueField'])
			? $this->_options['valueField']
			: 'id';
		$labelField = isset($this->_options['labelField'])
			? $this->_options['labelField']
			: 'name';
		foreach ($data as $entry)
		{
			$select = ($entry[$valueField] == $selected)
				? 'selected'
				: '';
			$html .= '<option value="' . $entry[$valueField] . '"' . $select . '>' . $entry[$labelField] . '</option>';
		}
		echo $html;
		exit();
	}


}

