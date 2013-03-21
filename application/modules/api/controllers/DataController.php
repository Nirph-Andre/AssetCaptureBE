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
Struct_Debug::errorLog('data/synch', 'init');
		$this->_helper->layout()->disableLayout();
		//$this->_helper->viewRenderer->setNoRender(true);
		$params = $this->getRequest()->getParams();
Struct_Debug::errorLog('params', $params);
		if (count($params) <= 3)
		{
			$params = json_decode(file_get_contents('php://input'), true);
Struct_Debug::errorLog('params', $params);
			if (empty($params))
			{
Struct_Debug::errorLog('params error', 'no data context');
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
Struct_Debug::errorLog('_nameSpace', $this->_nameSpace);
Struct_Debug::errorLog('_data', $this->_data);
Struct_Debug::errorLog('_options', $this->_options);
		
		$class = 'Object_' . str_replace('DropList', '', ucfirst($this->_nameSpace));
Struct_Debug::errorLog('data class', $class);
		$this->_object = new $class();
		return true;
	}

	public function indexAction()
	{
		$this->jsonResult(Struct_ActionFeedback::success());
	}
	
	public function synchAction()
	{
Struct_Debug::errorLog('synchAction', 'init');
		#-> Upstream.
		$synchDate = date('Y-m-d H:i:s', time() - 1);
		$feedback = array();
		$uniqueIdentifier = $this->_object->getUniqueIdentifier();
Struct_Debug::errorLog('uniqueIdentifier', $uniqueIdentifier);
		if (isset($this->_data['create']) && !empty($this->_data['create']))
		{
Struct_Debug::errorLog('create', $this->_data['create']);
			if (empty($uniqueIdentifier))
			{
				// Nothing to test against for duplication, create as is.
				foreach($this->_data['create'] as $synchEntry)
				{
					$remoteId = $synchEntry['id'];
					$res = $this->_object->process(
							new Struct_ActionRequest(
									'Create',
									$synchEntry
							));
					if ($res->ok())
					{
						$feedback[] = array('id' => $remoteId, 'sid' => $res->data['id']);
					}
				}
			}
			else
			{
				// Check for existing record.
				foreach($this->_data['create'] as $synchEntry)
				{
					$remoteId = $synchEntry['id'];
					$filter = array();
					foreach ($uniqueIdentifier as $field)
					{
						if (isset($synchEntry[$field]))
						{
							$filter[$field] = $synchEntry[$field];
						}
					}
					$item = $this->_object->view(null, $filter)->data;
					if (isset($item['id']) && $item['id'])
					{
						// Update.
						$synchEntry['id'] = $item['id'];
						$res = $this->_object->process(
								new Struct_ActionRequest(
										'Update',
										$synchEntry
								));
						if ($res->ok())
						{
							$feedback[] = array('id' => $remoteId, 'sid' => $item['id']);
						}
					}
					else
					{
						// Insert.
						$res = $this->_object->process(
								new Struct_ActionRequest(
										'Create',
										$synchEntry
								));
						if ($res->ok())
						{
							$feedback[] = array('id' => $remoteId, 'sid' => $res->data['id']);
						}
					}
				}
			}
Struct_Debug::errorLog('create', 'done');
		}
		if (isset($this->_data['update']) && !empty($this->_data['update']))
		{
Struct_Debug::errorLog('update', $this->_data['update']);
			foreach($this->_data['update'] as $synchEntry)
			{
				$remoteId = $synchEntry['id'];
				$item = $this->_object->view($synchEntry['sid'])->data;
				if (isset($item['id']) && $item['id'])
				{
					// Update.
					$synchEntry['id'] = $item['id'];
					$res = $this->_object->process(
							new Struct_ActionRequest(
									'Update',
									$synchEntry
							));
					if ($res->ok())
					{
						$feedback[] = array('id' => $remoteId, 'sid' => $item['id']);
					}
				}
			}
Struct_Debug::errorLog('update', 'done');
		}
		if (isset($this->_data['remove']) && !empty($this->_data['remove']))
		{
Struct_Debug::errorLog('remove', $this->_data['remove']);
			foreach($this->_data['remove'] as $synchEntry)
			{
				$remoteId = $synchEntry['id'];
				$item = $this->_object->view($synchEntry['sid'])->data;
				if (isset($item['id']) && $item['id'])
				{
					// Delete.
					$synchEntry['id'] = $item['id'];
					$res = $this->_object->process(
							new Struct_ActionRequest(
									'Delete',
									$item
							));
					if ($res->ok())
					{
						$feedback[] = array('id' => $remoteId, 'archive' => 'true');
					}
				}
			}
Struct_Debug::errorLog('remove', 'done');
		}
		
		#-> Downstream.
Struct_Debug::errorLog('synch', 'downstream');
		$lastSynch = $this->_data['lastSynchDate'];
		$extraFilter = isset($this->_data['filter'])
										&& is_array($this->_data['filter'])
			? $this->_data['filter']
			: array();
Struct_Debug::errorLog('create', array_merge($extraFilter, array(
				'created' => '>' . $lastSynch . ' AND <=' . $synchDate,
				'archived' => 0
		)));
		$create = $this->_object->listAll(array_merge($extraFilter, array(
				'created' => '>' . $lastSynch . ' AND <=' . $synchDate,
				'archived' => 0
		)), array(), true)->data;
Struct_Debug::errorLog('update', array_merge($extraFilter, array(
				'created' => '<=' . $lastSynch,
				'updated' => '>' . $lastSynch . ' AND <=' . $synchDate,
				'archived' => 0
		)));
		$update = $this->_object->listAll(array_merge($extraFilter, array(
				'created' => '<=' . $lastSynch,
				'updated' => '>' . $lastSynch . ' AND <=' . $synchDate,
				'archived' => 0
		)), array(), true)->data;
Struct_Debug::errorLog('remove', array_merge($extraFilter, array(
				'updated' => '>' . $lastSynch . ' AND <=' . $synchDate,
				'archived' => 1
		)));
		$remove = $this->_object->listAll(array_merge($extraFilter, array(
				'updated' => '>' . $lastSynch . ' AND <=' . $synchDate,
				'archived' => 1
		)), array(), true)->data;
Struct_Debug::errorLog('synch', 'downstream data collection completed');
		
		#-> Done, provide relevant feedback and downstream data.
		$this->jsonNsResult(
			Struct_ActionFeedback::successWithData(array(
				'Feedback' => $feedback,
				'Create' => $create,
				'Update' => $update,
				'Remove' => $remove
			), array(
				'synch_datetime' => $synchDate
			)));
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

