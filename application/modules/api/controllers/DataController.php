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
		if (!Struct_Registry::isAuthenticated() && !defined('DEBUG_UNITTEST'))
		{
			Struct_Debug::errorLog('Data call without authentication.', $this->getRequest()->getParams());
			$this->jsonResult(Struct_ActionFeedback::error(
					'Data call without authentication.',
					'You are not authenticated, please login.'
			));
		}
		$this->_helper->layout()->disableLayout();
		//$this->_helper->viewRenderer->setNoRender(true);
		$params = $this->getRequest()->getParams();
		if (count($params) <= 3)
		{
			$params = json_decode(file_get_contents('php://input'), true);
			$jsonErr = json_last_error();
			switch ($jsonErr)
			{
				case JSON_ERROR_DEPTH:
					Struct_Debug::errorLog('JSON error', 'JSON_ERROR_DEPTH');
					Struct_Debug::errorLog('JSON', file_get_contents('php://input'));
					break;
				case JSON_ERROR_STATE_MISMATCH:
					Struct_Debug::errorLog('JSON error', 'JSON_ERROR_STATE_MISMATCH');
					Struct_Debug::errorLog('JSON', file_get_contents('php://input'));
					break;
				case JSON_ERROR_CTRL_CHAR:
					Struct_Debug::errorLog('JSON error', 'JSON_ERROR_CTRL_CHAR');
					Struct_Debug::errorLog('JSON', file_get_contents('php://input'));
					break;
				case JSON_ERROR_SYNTAX:
					Struct_Debug::errorLog('JSON error', 'JSON_ERROR_SYNTAX');
					Struct_Debug::errorLog('JSON', file_get_contents('php://input'));
					break;
				case JSON_ERROR_UTF8:
					Struct_Debug::errorLog('JSON error', 'JSON_ERROR_UTF8');
					Struct_Debug::errorLog('JSON', file_get_contents('php://input'));
					break;
				/* case JSON_ERROR_NONE:
					Struct_Debug::errorLog('JSON error', 'JSON_ERROR_NONE');
					Struct_Debug::errorLog('JSON', file_get_contents('php://input'));
					break; */
			}
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
			if (!is_array($this->_data))
			{
				$this->_data = $params;
			}
			if (empty($this->_options) && is_array($this->_data) && isset($this->_data['Options']))
			{
				$this->_options = $this->_data['Options'];
				unset($this->_data['Options']);
			}
		}

		$class = 'Object_' . str_replace('DropList', '', ucfirst($this->_nameSpace));
		'Object_Type' == $class
			&& $class = 'Object_Photo';
		try
		{
			$this->_object = new $class();
		}
		catch (Exception $e)
		{
			// oops
		}
		return true;
	}

	public function indexAction()
	{
		$this->jsonResult(Struct_ActionFeedback::success());
	}

	public function uploadAction()
	{
		$uploadHandler = new Component_DocumentUpload($this->_data['asset_id'], $this->_data['type']);
		header("HTTP/1.1 200 OK");
		echo 'OK';
		exit(0);
	}

	public function synchAction()
	{
		#-> Upstream.
		$synchDate = date('Y-m-d H:i:s', time() - 1);
		$ini = new Zend_Config_Ini(APPLICATION_PATH . '/configs/synch.ini', 'Table');
		$config = $ini->toArray();
		$config = isset($config[$this->_nameSpace])
			? $config[$this->_nameSpace]
			: array('FromMobile' => false, 'ToMobile' => false);

		#-> Upstream.
		$feedback = array();
		if (isset($config['FromMobile']) && 1 == $config['FromMobile'])
		{
			$uniqueIdentifier = $this->_object->getUniqueIdentifier();
			if (isset($this->_data['create']) && !empty($this->_data['create']))
			{
				if ('Photo' == ucfirst($this->_nameSpace))
				{
					foreach($this->_data['create'] as $synchEntry)
					{
						$remoteId = $synchEntry['id'];
						Struct_Debug::errorLog($this->_nameSpace . '.create', $synchEntry['type']);
						Struct_Debug::errorLog($this->_nameSpace . '.create', $synchEntry['asset_id']);
						file_put_contents(APPLICATION_PATH . '/../public/img/test.jpg', $synchEntry['data']);
						unset($synchEntry['data']);
						$feedback[] = isset($config['AutoArchive']) && 1 == $config['AutoArchive']
							? array('id' => $remoteId, 'archive' => 1 )
							: array('id' => $remoteId, 'sid' => $res->data['id'] );
					}
				}
				else
				{
					//Struct_Debug::errorLog($this->_nameSpace . '.create', $this->_data['create']);
					if (empty($uniqueIdentifier))
					{
						// Nothing to test against for duplication, create as is.
						foreach($this->_data['create'] as $synchEntry)
						{
							$remoteId = $synchEntry['id'];
							unset($synchEntry['id']);
							$res = $this->_object->process(
									new Struct_ActionRequest(
											'Create',
											$synchEntry
									));
							if ($res->ok())
							{
								$feedback[] = isset($config['AutoArchive']) && 1 == $config['AutoArchive']
									? array('id' => $remoteId, 'archive' => 1 )
									: array('id' => $remoteId, 'sid' => $res->data['id'] );
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
									$feedback[] = isset($config['AutoArchive']) && 1 == $config['AutoArchive']
										? array('id' => $remoteId, 'archive' => 1 )
										: array('id' => $remoteId, 'sid' => $item['id'] );
								}
							}
							else
							{
								// Insert.
								unset($synchEntry['id']);
								$res = $this->_object->process(
										new Struct_ActionRequest(
												'Create',
												$synchEntry
										));
								if ($res->ok())
								{
									$feedback[] = isset($config['AutoArchive']) && 1 == $config['AutoArchive']
										? array('id' => $remoteId, 'archive' => 1 )
										: array('id' => $remoteId, 'sid' => $res->data['id'] );
								}
							}
						}
					}
				}
			}
			if (isset($this->_data['update']) && !empty($this->_data['update']))
			{
				//Struct_Debug::errorLog($this->_nameSpace . '.update', $this->_data['update']);
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
			}
			if (isset($this->_data['remove']) && !empty($this->_data['remove']))
			{
				//Struct_Debug::errorLog($this->_nameSpace . '.remove', $this->_data['remove']);
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
			}
		}

		#-> Downstream.
		if (isset($config['ToMobile']) && 1 == $config['ToMobile'])
		{
			$lastSynch = $this->_data['lastSynchDate'];
			$extraFilter = isset($this->_data['filter'])
							&& is_array($this->_data['filter'])
				? $this->_data['filter']
				: array();
			$create = $this->_object->listAll(array_merge($extraFilter, array(
					'created' => '>' . $lastSynch . ' AND <=' . $synchDate,
					'archived' => 0
			)), array(), true)->data;
			$update = $this->_object->listAll(array_merge($extraFilter, array(
					'created' => '<=' . $lastSynch,
					'updated' => '>' . $lastSynch . ' AND <=' . $synchDate,
					'archived' => 0
			)), array(), true)->data;
			$remove = $this->_object->listAll(array_merge($extraFilter, array(
					'updated' => '>' . $lastSynch . ' AND <=' . $synchDate,
					'archived' => 1
			)), array(), true)->data;
		}
		else
		{
			$create = array();
			$update = array();
			$remove = array();
		}

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

