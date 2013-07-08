<?php


/**
 * Handy import component.
 * @author andre.fourie
 */
class Component_Import
{

	protected $importId;
	protected $oImportData;


	/**
	 * Retrieve id of existing record or create record if not found.
	 * @param	string $object
	 * @param	string $value
	 * @param	string $field
	 * @param	array	$filter
	 * @return integer
	 */
	protected function sourceId($object, $value, $field = 'name', array $filter = array())
	{
		$class = 'Object_' . $object;
		$oClass = new $class();
		$filter[$field] = $value;
		$entry = $oClass->view(null, $filter)->data;
		if (empty($entry))
		{
			if (!isset($filter[$field]) || is_null($filter[$field]))
			{
				echo "class: $class\n";
				var_dump($filter);
				exit();
			}
			$ret = $oClass->save(null, array(), $filter);
			if (!isset($ret->data['id']))
			{
				echo "class: $class\n";
				var_dump($filter);
				var_dump($ret->result);
				exit();
			}
			return $ret->data['id'];
		}
		else
		{
			return $entry['id'];
		}
	}

	/**
	 * Retrieve id of existing record.
	 * @param	string $object
	 * @param	string $value
	 * @param	string $field
	 * @param	array	$filter
	 * @return integer|null
	 */
	protected function getId($object, $value, $field = 'name', array $filter = array())
	{
		$class = 'Object_' . $object;
		$oClass = new $class();
		$filter[$field] = $value;
		$entry = $oClass->view(null, $filter)->data;
		return empty($entry)
			? null
			: $entry['id'];
	}


	protected function addLine($identifier, array $line)
	{
		$this->oImportData->save(null, array(), array(
				'import_id'  => $this->importId,
				'identifier' => $identifier,
				'packet'     => serialize($line)
		));
	}


	public function import($location, $fileName, $csvToFieldMap)
	{
		#-> Safety check.
		set_time_limit(0);
		if (!file_exists(APPLICATION_PATH . '/../data/' . $fileName))
		{
			echo 'File not found for import! > ' . APPLICATION_PATH . '/../data/' . $fileName;
			exit(0);
		}

		#-> Prep location.
		$oLocation = new Object_Location();
		$loc = $oLocation->view(null, array('name' => $location));
		if (empty($loc->data))
		{
			$loc = $oLocation->save(null, array(), array('name' => $location));
		}
		$locationId = $loc->data['id'];

		#-> Prep import.
		$oImport = new Object_Import();
		$this->importId = $oImport->save(null, array(), array(
				'filename' => $fileName,
				'imported' => date('Y-m-d H:i:s'),
				'records'  => 0,
				'map'      => serialize($csvToFieldMap)
		))->data['id'];
		$this->oImportData = new Object_ImportData();

		#-> Prep file.
		$handle  = fopen(APPLICATION_PATH . '/../data/' . $fileName, "r");
		$columns = fgetcsv($handle);
		$this->addLine('columns', $columns);
		$oAsset = new Object_Asset();

		#-> Process file.
		$i = 0;
		while (($data = fgetcsv($handle)) !== false)
		{
			#-> Extract asset data from input.
			$i++;
			$entry = array();
			foreach ($csvToFieldMap as $field => $csvIndex)
			{
				$entry[$field] = ('N/A' != $data[$csvIndex] && '' != $data[$csvIndex])
					? $data[$csvIndex]
					: null;
			}
			is_null($entry['identifier'])
				&& $entry['identifier'] = '';
			is_null($entry['asset_type_id'])
				&& $entry['asset_type_id'] = 'N/A';
			is_null($entry['asset_sub_type_id'])
				&& $entry['asset_sub_type_id'] = 'N/A';
			is_null($entry['town_id'])
				&& $entry['town_id'] = 'N/A';

			#-> Construct asset.
			error_log("Processing: $i : " . $entry['identifier']);
			$record = array();
			$record['location_id'] = $locationId;
			is_null($entry['town_id'])
				&& $entry['town_id'] = 'N/A';
			$record['town_id'] = $this->sourceId('Town', $entry['town_id'], 'name', array(
					'location_id' => $locationId
			));
			if (!is_null($entry['street_id']))
			{
				$record['street_id'] = $this->sourceId('Street', $entry['street_id'], 'name', array(
						'town_id' => $record['town_id']
				));
			}
			if (!is_null($entry['building_id']))
			{
				$record['building_id'] = $this->sourceId('Building', $entry['building_id'], 'name', array(
						'location_id' => $locationId
				));
				if (!is_null($entry['floor_id']) || !is_null($entry['room_id']))
				{
					is_null($entry['floor_id'])
						&& $entry['floor_id'] = 'N/A';
					$record['floor_id'] = $this->sourceId('Floor', $entry['floor_id'], 'name', array(
							'building_id' => $record['building_id']
					));
				}
				if (!is_null($entry['room_id']))
				{
					$record['room_id'] = $this->sourceId('Room', $entry['room_id'], 'name', array(
							'floor_id' => $record['floor_id']
					));
				}
			}
			$record['owner_id'] = !is_null($entry['owner_id'])
				? $this->sourceId('Owner', $entry['owner_id'])
				: $this->sourceId('Owner', 'N\A');
			$gpsLat = !is_null($entry['lat_start'])
				? $entry['lat_start']
				: $entry['lat_end'];
			$gpsLong = !is_null($entry['long_start'])
				? $entry['long_start']
				: $entry['long_end'];
			if (!is_null($gpsLat) && !is_null($gpsLong) && strpos($gpsLat, ' ') && strpos($gpsLong, ' '))
			{
				$latN = strpos($gpsLat, '" S')
					? true
					: false;
				$gpsLat = trim(str_replace(array("?", "�", "'", '" N', '" S'), '', $gpsLat));
				list($deg, $min, $sec) = explode(' ', $gpsLat);
				$gpsLat = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0);
				$latN && $gpsLat *= -1.0;

				$longN = strpos($gpsLong, '" W')
					? true
					: false;
				$gpsLong = trim(str_replace(array("?", "�", "'", '" E', '" W'), '', $gpsLong));
				list($deg, $min, $sec) = explode(' ', $gpsLong);
				$gpsLong = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0);
				$longN && $gpsLong *= -1.0;

				$record['gps_lat']  = $gpsLat;
				$record['gps_long'] = $gpsLong;
			}

			$record['identifier'] = $entry['identifier'];
			$record['asset_type_id'] = $this->sourceId('AssetType', $entry['asset_type_id']);
			$record['asset_sub_type_id'] = $this->sourceId('AssetSubType', $entry['asset_sub_type_id'], 'name', array(
							'asset_type_id' => $record['asset_type_id']
					));
			$record['asset_description_id'] = !is_null($entry['asset_description_id'])
				? $this->sourceId('AssetDescription', $entry['asset_description_id'], 'name', array(
							'asset_sub_type_id' => $record['asset_sub_type_id']
					))
				: $record['asset_sub_type_id'];
			$record['asset_sub_description_id'] = !is_null($entry['asset_sub_description_id'])
				? $this->sourceId('AssetSubDescription', $entry['asset_sub_description_id'], 'name', array(
							'asset_description_id' => $record['asset_description_id']
					))
				: null;
			if (!is_null($entry['details']))
			{
				$items = explode(' , ', $entry['details']);
				if (3 == count($items))
				{
					list($field, $material) = explode(':', $items[1]);
					$record['material_id'] = $this->sourceId('Material', $material);
					$record['details'] = $items[0] . ' , ' . $items[2];
				}
				else
				{
					$record['details'] = $entry['details'];
				}
			}
			!is_null($entry['detail2'])
				&& $record['detail2'] = $entry['detail2'];
			!is_null($entry['serial'])
				&& $record['serial'] = $entry['serial'];
			$assetRes = $oAsset->save(null, array(), $record);
			if (!isset($assetRes->data['id']))
			{
				exit();
			}

			$this->addLine($assetRes->data['id'], $data);
		}
	}

	public function export($locationId, $importId, $fileName)
	{
		set_time_limit(0);
		if (file_exists(APPLICATION_PATH . '/../data/' . $fileName))
		{
			echo 'Export file already exists! > ' . APPLICATION_PATH . '/../data/' . $fileName;
			exit(0);
		}
		#-> Prep import.
		$expDate = '2013-06-26';
		$this->importId = $importId;
		$oImport = new Object_Import();
		$oImportData = new Object_ImportData();
		$import = $oImport->view($this->importId)->data;
		$csvToFieldMap = unserialize($import['map']);
		$importData = $oImportData->grid(
			array('import_id' => $this->importId), array(), null, null,
			array(), array('import'),
			array()
		)->data;
		/* echo '<pre>';
		var_dump($importData);
		echo '</pre>';
		exit(); */

		#-> Prep file.
		$handle  = fopen(APPLICATION_PATH . '/../data/' . $fileName, "w");
		$oAsset = new Object_Asset();

		#-> Export original records.
		$i = 0;
		$maxId = 0;
		foreach ($importData as $row)
		{
			$packet = unserialize($row['packet']);
			if ('columns' == $row['identifier'])
			{
				$packet[] = 'UPDATE STATUS';
				$packet[] = 'CREATE TIME';
				$packet[] = 'UPDATE TIME';
				$packet[] = 'PREV CONDITION';
				$packet[] = 'PREV CATEGORY 1';
				$packet[] = 'PREV CATEGORY 2';
				$packet[] = 'PREV CATEGORY 3';
				$packet[] = 'PREV CATEGORY 4';
				$packet[] = 'PREV TOWN';
				$packet[] = 'PREV STREET';
				$packet[] = 'PREV BUILDING';
				$packet[] = 'PREV FLOOR';
				$packet[] = 'PREV ROOM';
				fputcsv($handle, $packet);
				continue;
			}
			if (empty($row['identifier']))
			{
				$packet[] = 'Unchanged';
				$packet[] = '';
				$packet[] = '';
				$packet[] = $csvToFieldMap['condition_id'];
				$packet[] = $csvToFieldMap['asset_type_id'];
				$packet[] = $csvToFieldMap['asset_sub_type_id'];
				$packet[] = $csvToFieldMap['asset_sub_description_id'];
				$packet[] = $csvToFieldMap['details'];
				$packet[] = $csvToFieldMap['town_id'];
				$packet[] = $csvToFieldMap['street_id'];
				$packet[] = $csvToFieldMap['building_id'];
				$packet[] = $csvToFieldMap['floor_id'];
				$packet[] = $csvToFieldMap['room_id'];
				//fputcsv($handle, $packet);
				continue;
			}
			$asset = $oAsset->view($row['identifier'], array(), true)->data;
			if (empty($asset))
			{
				echo 'Could not find import_data_id ' . $row['id'] . ' : asset ' . $row['identifier'];
				exit();
			}
			$cP = explode(' ', $asset['created']);
			$uP = '';
			if (!is_null($asset['updated']) && !empty($asset['updated']))
			{
				$uP = explode(' ', $asset['updated']);
			}
			/* $continue = $expDate == $cP[0] || $expDate == $uP[0]
				? true
				: false;
			if (!$continue)
			{
				continue;
			} */
			$maxId = ($maxId < $asset['id'])
				? $asset['id']
				: $maxId;
			/* echo '<pre>';
			var_dump($csvToFieldMap);
			var_dump($asset);
			echo '</pre>';
			exit(); */
			foreach ($csvToFieldMap as $field => $csvIndex)
			{
				switch($field)
				{
					case 'asset_type_id':
						$prevCat1 = $packet[$csvIndex];
						$value = !is_null($asset['asset_type']['name'])
							? $asset['asset_type']['name']
							: 'N/A';
						break;
					case 'asset_sub_type_id':
						$prevCat2 = $packet[$csvIndex];
						$value = !is_null($asset['asset_sub_type']['name'])
							? $asset['asset_sub_type']['name']
							: 'N/A';
						break;
					case 'asset_description_id':
						$value = !is_null($asset['asset_description']['name'])
							? $asset['asset_description']['name']
							: 'N/A';
						break;
					case 'asset_sub_description_id':
						$prevCat3 = $packet[$csvIndex];
						$value = !is_null($asset['asset_sub_description']['name'])
							? $asset['asset_sub_description']['name']
							: '';
						break;
					case 'identifier':
						$value = !is_null($asset['identifier'])
							? $asset['identifier']
							: '';
						break;
					case 'details':
						$prevCat4 = $packet[$csvIndex];
						$value = !is_null($asset['details'])
							? $asset['details']
							: '';
						break;
					case 'detail2':
						$value = !is_null($asset['detail2'])
							? $asset['detail2']
							: '';
						break;
					case 'serial':
						$value = !is_null($asset['serial'])
							? $asset['serial']
							: '';
						break;
					case 'condition_id':
						$prevCondition = $packet[$csvIndex];
						$value = !is_null($asset['condition']['name'])
							? $asset['condition']['name']
							: '';
						break;
					case 'town_id':
						$prevTown = $packet[$csvIndex];
						$value = !is_null($asset['town']['name'])
							? $asset['town']['name']
							: 'N/A';
						break;
					case 'lat_start':
						$value = !is_null($asset['gps_lat'])
							? $asset['gps_lat']
							: '';
						break;
					case 'long_start':
						$value = !is_null($asset['gps_long'])
							? $asset['gps_long']
							: '';
						break;
					case 'lat_end':
						$value = $packet[$csvIndex];
						break;
					case 'long_end':
						$value = $packet[$csvIndex];
						break;
					case 'street_id':
						$prevStreet = $packet[$csvIndex];
						$value = !is_null($asset['street']['name'])
							? $asset['street']['name']
							: '';
						break;
					case 'building_id':
						$prevBuilding = $packet[$csvIndex];
						$value = !is_null($asset['building']['name'])
							? $asset['building']['name']
							: '';
						break;
					case 'floor_id':
						$prevFloor = $packet[$csvIndex];
						$value = !is_null($asset['floor']['name'])
							? $asset['floor']['name']
							: '';
						break;
					case 'room_id':
						$prevRoom = $packet[$csvIndex];
						$value = !is_null($asset['room']['name'])
							? $asset['room']['name']
							: '';
						break;
					case 'owner_id':
						$value = !is_null($asset['owner']['name'])
							? $asset['owner']['name']
							: '';
						break;
				}
				$packet[$csvIndex] = $value;
			}
			$updated = $asset["created"] != $asset["updated"]
				? 'Updated'
				: 'Unchanged';
			$packet[] = $updated;
			$packet[] = $asset['created'];
			$packet[] = $asset['updated'];
			$packet[] = $prevCondition;
			$packet[] = $prevCat1;
			$packet[] = $prevCat2;
			$packet[] = $prevCat3;
			$packet[] = $prevCat4;
			$packet[] = $prevTown;
			$packet[] = $prevStreet;
			$packet[] = $prevBuilding;
			$packet[] = $prevFloor;
			$packet[] = $prevRoom;
			fputcsv($handle, $packet);
			$i++;
		}

		$blank = array();
		for ($i = 0; $i < 60; $i++)
		{
			$blank[$i] = '';
		}

		#-> Export new records. :: $record['id'] > 5011
		$prevCondition = '';
		$prevCat1 = '';
		$prevCat2 = '';
		$prevCat3 = '';
		$prevCat4 = '';
		$prevTown = '';
		$prevStreet = '';
		$prevBuilding = '';
		$prevFloor = '';
		$prevRoom = '';
		error_log("max id: $maxId (5011)");
		$assetData = $oAsset->grid(
				array('location.id' => $locationId, 'asset.id' => '>' . $maxId), array(), null, null,
				array(), array(),
				array()
		)->data;
		error_log(count($assetData));
		foreach ($assetData as $asset)
		{
			if ($asset['id'] < $maxId)
			{
				error_log('.');
				continue;
			}
			$cP = explode(' ', $asset['created']);
			$uP = '';
			if (!is_null($asset['updated']) && !empty($asset['updated']))
			{
				$uP = explode(' ', $asset['updated']);
			}
			/* $continue = $expDate == $cP[0] || $expDate == $uP[0]
				? true
				: false;
			if (!$continue)
			{
				continue;
			} */
			$packet = $blank;
			foreach ($csvToFieldMap as $field => $csvIndex)
			{
				switch($field)
				{
					case 'asset_type_id':
						$value = !is_null($asset['asset_type_name'])
						? $asset['asset_type_name']
						: 'N/A';
						break;
					case 'asset_sub_type_id':
						$value = !is_null($asset['asset_sub_type_name'])
						? $asset['asset_sub_type_name']
						: 'N/A';
						break;
					case 'asset_description_id':
						$value = !is_null($asset['asset_description_name'])
						? $asset['asset_description_name']
						: 'N/A';
						break;
					case 'asset_sub_description_id':
						$value = !is_null($asset['asset_sub_description_name'])
						? $asset['asset_sub_description_name']
						: '';
						break;
					case 'identifier':
						$value = !is_null($asset['identifier'])
						? $asset['identifier']
						: '';
						break;
					case 'details':
						$value = !is_null($asset['details'])
						? $asset['details']
						: '';
						break;
					case 'detail2':
						$value = !is_null($asset['detail2'])
						? $asset['detail2']
						: '';
						break;
					case 'serial':
						$value = !is_null($asset['serial'])
						? $asset['serial']
						: '';
						break;
					case 'condition_id':
						$value = !is_null($asset['condition_name'])
						? $asset['condition_name']
						: '';
						break;
					case 'town_id':
						$value = !is_null($asset['town_name'])
						? $asset['town_name']
						: 'N/A';
						break;
					case 'lat_start':
						$value = !is_null($asset['gps_lat'])
						? $asset['gps_lat']
						: '';
						break;
					case 'long_start':
						$value = !is_null($asset['gps_long'])
						? $asset['gps_long']
						: '';
						break;
					case 'lat_end':
						$value = $packet[$csvIndex];
						break;
					case 'long_end':
						$value = $packet[$csvIndex];
						break;
					case 'street_id':
						$value = !is_null($asset['street_name'])
						? $asset['street_name']
						: '';
						break;
					case 'building_id':
						$value = !is_null($asset['building_name'])
						? $asset['building_name']
						: '';
						break;
					case 'floor_id':
						$value = !is_null($asset['floor_name'])
						? $asset['floor_name']
						: '';
						break;
					case 'room_id':
						$value = !is_null($asset['room_name'])
						? $asset['room_name']
						: '';
						break;
					case 'owner_id':
						$value = !is_null($asset['owner_name'])
						? $asset['owner_name']
						: '';
						break;
				}
				$packet[$csvIndex] = $value;
			}
			$packet[] = 'New';
			$packet[] = $asset['created'];
			$packet[] = $asset['updated'];
			fputcsv($handle, $packet);
		}
	}
















	public function importRoads()
	{
		set_time_limit(0);
		if (!file_exists(APPLICATION_PATH . '\..\data\roads.csv'))
		{
			echo 'File not found for import!';
			exit(0);
		}
		$handle = fopen(APPLICATION_PATH . '\..\data\roads.csv', "r");
		$data = fgetcsv($handle);
		$csvToFieldMap = array(
				'asset_type_id' 						=> 3,
				'asset_sub_type_id' 				=> 4,
				'asset_description_id' 			=> 5,
				'asset_sub_description_id' 	=> 7,
				'identifier' 								=> 6,
				'details' 									=> 8,
				'condition_id' 							=> 14,
				'town_id' 									=> 19,
				'lat_start' 								=> 20,
				'long_start' 								=> 21,
				'lat_end' 									=> 22,
				'long_end' 									=> 23,
				'street_id' 								=> 25,
				'building_id' 							=> 27,
				'floor_id' 									=> 28,
				'room_id' 									=> 29,
				'owner_id' 									=> 31
		);
		$data = fgetcsv($handle);
		$oAsset = new Object_Asset();
		while (($data = fgetcsv($handle)) !== false)
		{
			$entry = array();
			foreach ($csvToFieldMap as $field => $csvIndex)
			{
				$entry[$field] = ('N/A' != $data[$csvIndex] && '' != $data[$csvIndex])
					? $data[$csvIndex]
					: null;
			}
			if (is_null($entry['identifier']))
			{
				continue;
			}
			$record = array();

			$record['location_id'] = 1;
			is_null($entry['town_id'])
				&& $entry['town_id'] = 'N/A';
			$record['town_id'] = $this->sourceId('Town', $entry['town_id'], 'name', array(
					'location_id' => 1
			));
			if (!is_null($entry['street_id']))
			{
				$record['street_id'] = $this->sourceId('Street', $entry['street_id'], 'name', array(
						'town_id' => $record['town_id']
				));
			}
			if (!is_null($entry['building_id']))
			{
				$record['building_id'] = $this->sourceId('Building', $entry['building_id'], 'name', array(
						'location_id' => 1
				));
				if (!is_null($entry['floor_id']) || !is_null($entry['room_id']))
				{
					is_null($entry['floor_id'])
						&& $entry['floor_id'] = 'N/A';
					$record['floor_id'] = $this->sourceId('Floor', $entry['floor_id'], 'name', array(
							'building_id' => $record['building_id']
					));
				}
				if (!is_null($entry['room_id']))
				{
					$record['room_id'] = $this->sourceId('Room', $entry['room_id'], 'name', array(
							'floor_id' => $record['floor_id']
					));
				}
			}
			if (!is_null($entry['owner_id']))
			{
				$record['owner_id'] = $this->sourceId('Owner', $entry['owner_id']);
			}
			$gpsLat = !is_null($entry['lat_start'])
				? $entry['lat_start']
				: $entry['lat_end'];
			$gpsLong = !is_null($entry['long_start'])
				? $entry['long_start']
				: $entry['long_end'];
			if (!is_null($gpsLat) && !is_null($gpsLong))
			{
				$latN = strpos($gpsLat, '" S')
					? true
					: false;
				$gpsLat = trim(str_replace(array("?", "�", "'", '" N', '" S'), '', $gpsLat));
				list($deg, $min, $sec) = explode(' ', $gpsLat);
				$gpsLat = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0);
				$latN && $gpsLat *= -1.0;

				$longN = strpos($gpsLong, '" W')
					? true
					: false;
				$gpsLong = trim(str_replace(array("?", "�", "'", '" E', '" W'), '', $gpsLong));
				list($deg, $min, $sec) = explode(' ', $gpsLong);
				$gpsLong = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0);
				$longN && $gpsLong *= -1.0;

				$record['gps_lat']  = $gpsLat;
				$record['gps_long'] = $gpsLong;
			}

			$record['identifier'] = $entry['identifier'];
			$record['asset_type_id'] = $this->sourceId('AssetType', $entry['asset_type_id']);
			$record['asset_sub_type_id'] = $this->sourceId('AssetSubType', $entry['asset_sub_type_id'], 'name', array(
							'asset_type_id' => $record['asset_type_id']
					));
			$record['asset_description_id'] = $this->sourceId('AssetDescription', $entry['asset_description_id'], 'name', array(
							'asset_sub_type_id' => $record['asset_sub_type_id']
					));
			$record['asset_sub_description_id'] = $this->sourceId('AssetSubDescription', $entry['asset_sub_description_id'], 'name', array(
							'asset_description_id' => $record['asset_description_id']
					));
			if (!is_null($entry['details']))
			{
				$items = explode(' , ', $entry['details']);
				if (3 == count($items))
				{
					list($field, $material) = explode(':', $items[1]);
					$record['material_id'] = $this->sourceId('Material', $material);
					$record['details'] = $items[0] . ' , ' . $items[2];
				}
				else
				{
					$record['details'] = $entry['details'];
				}
			}
			$oAsset->save(null, array(), $record);
		}
		echo 'DONE';
	}


	public function importSewer()
	{
		set_time_limit(0);
		if (!file_exists(APPLICATION_PATH . '\..\data\sewer.csv'))
		{
			echo 'File not found for import!';
			exit(0);
		}
		$handle = fopen(APPLICATION_PATH . '\..\data\sewer.csv', "r");
		$data = fgetcsv($handle);
		$csvToFieldMap = array(
				'asset_type_id' 						=> 0,
				'asset_sub_type_id' 				=> 1,
				'asset_description_id' 			=> 2,
				'asset_sub_description_id' 	=> 4,
				'identifier' 								=> 3,
				'details' 									=> 5,
				'condition_id' 							=> 11,
				'town_id' 									=> 16,
				'lat_start' 								=> 17,
				'long_start' 								=> 18,
				'lat_end' 									=> 19,
				'long_end' 									=> 20,
				'street_id' 								=> 22,
				'building_id' 							=> 24,
				'floor_id' 									=> 25,
				'room_id' 									=> 26,
				'owner_id' 									=> 28
		);
		$data = fgetcsv($handle);
		$oAsset = new Object_Asset();
		while (($data = fgetcsv($handle)) !== false)
		{
			$entry = array();
			foreach ($csvToFieldMap as $field => $csvIndex)
			{
				$entry[$field] = ('N/A' != $data[$csvIndex] && '' != $data[$csvIndex])
					? $data[$csvIndex]
					: null;
			}
			if (is_null($entry['identifier']))
			{
				continue;
			}
			$record = array();

			$record['location_id'] = 1;
			is_null($entry['town_id'])
				&& $entry['town_id'] = 'N/A';
			$record['town_id'] = $this->sourceId('Town', $entry['town_id'], 'name', array(
					'location_id' => 1
			));
			if (!is_null($entry['street_id']))
			{
				$record['street_id'] = $this->sourceId('Street', $entry['street_id'], 'name', array(
						'town_id' => $record['town_id']
				));
			}
			if (!is_null($entry['building_id']))
			{
				$record['building_id'] = $this->sourceId('Building', $entry['building_id'], 'name', array(
						'location_id' => 1
				));
				if (!is_null($entry['floor_id']) || !is_null($entry['room_id']))
				{
					is_null($entry['floor_id'])
						&& $entry['floor_id'] = 'N/A';
					$record['floor_id'] = $this->sourceId('Floor', $entry['floor_id'], 'name', array(
							'building_id' => $record['building_id']
					));
				}
				if (!is_null($entry['room_id']))
				{
					$record['room_id'] = $this->sourceId('Room', $entry['room_id'], 'name', array(
							'floor_id' => $record['floor_id']
					));
				}
			}
			if (!is_null($entry['owner_id']))
			{
				$record['owner_id'] = $this->sourceId('Owner', $entry['owner_id']);
			}
			$gpsLat = !is_null($entry['lat_start'])
				? $entry['lat_start']
				: $entry['lat_end'];
			$gpsLong = !is_null($entry['long_start'])
				? $entry['long_start']
				: $entry['long_end'];
			if (!is_null($gpsLat) && !is_null($gpsLong))
			{
				/* $latN = strpos($gpsLat, '" S')
					? true
					: false; */
				$gpsLat = str_replace(array("-", "?", "�", "'", '" N', '" S'), '', $gpsLat);
				/* list($deg, $min, $sec) = explode(' ', $gpsLat);
				$gpsLat = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0); */
				$gpsLat *= -1.0;

				/* $longN = strpos($gpsLong, '" W')
					? true
					: false; */
				$gpsLong = str_replace(array("-", "?", "�", "'", '" E', '" W'), '', $gpsLong);
				/* list($deg, $min, $sec) = explode(' ', $gpsLong);
				$gpsLong = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0); */
				//$longN && $gpsLong *= -1.0;

				$record['gps_lat']  = $gpsLat;
				$record['gps_long'] = $gpsLong;
			}

			$record['identifier'] = $entry['identifier'];
			$record['asset_type_id'] = $this->sourceId('AssetType', $entry['asset_type_id']);
			$record['asset_sub_type_id'] = $this->sourceId('AssetSubType', $entry['asset_sub_type_id'], 'name', array(
							'asset_type_id' => $record['asset_type_id']
					));
			$record['asset_description_id'] = $this->sourceId('AssetDescription', $entry['asset_description_id'], 'name', array(
							'asset_sub_type_id' => $record['asset_sub_type_id']
					));
			$record['asset_sub_description_id'] = $this->sourceId('AssetSubDescription', $entry['asset_sub_description_id'], 'name', array(
							'asset_description_id' => $record['asset_description_id']
					));
			if (!is_null($entry['details']))
			{
				$items = explode(' , ', $entry['details']);
				if (3 == count($items))
				{
					list($field, $material) = explode(':', $items[1]);
					$record['material_id'] = $this->sourceId('Material', $material);
					$record['details'] = $items[0] . ' , ' . $items[2];
				}
				else
				{
					$record['details'] = $entry['details'];
				}
			}
			$oAsset->save(null, array(), $record);
		}
		echo 'DONE';
	}


	public function importWater()
	{
		set_time_limit(0);
		if (!file_exists(APPLICATION_PATH . '\..\data\water.csv'))
		{
			echo 'File not found for import!';
			exit(0);
		}
		$handle = fopen(APPLICATION_PATH . '\..\data\water.csv', "r");
		$data = fgetcsv($handle);
		$csvToFieldMap = array(
				'asset_type_id' 						=> 0,
				'asset_sub_type_id' 				=> 1,
				'asset_description_id' 			=> 2,
				'asset_sub_description_id' 	=> 4,
				'identifier' 								=> 3,
				'details' 									=> 5,
				'condition_id' 							=> 11,
				'town_id' 									=> 16,
				'lat_start' 								=> 17,
				'long_start' 								=> 18,
				'lat_end' 									=> 19,
				'long_end' 									=> 20,
				'street_id' 								=> 22,
				'building_id' 							=> 24,
				'floor_id' 									=> 25,
				'room_id' 									=> 26,
				'owner_id' 									=> 28
		);
		$data = fgetcsv($handle);
		$oAsset = new Object_Asset();
		while (($data = fgetcsv($handle)) !== false)
		{
			$entry = array();
			foreach ($csvToFieldMap as $field => $csvIndex)
			{
				$entry[$field] = ('N/A' != $data[$csvIndex] && '' != $data[$csvIndex])
					? $data[$csvIndex]
					: null;
			}
			if (is_null($entry['identifier']))
			{
				continue;
			}
			$record = array();

			$record['location_id'] = 1;
			is_null($entry['town_id'])
				&& $entry['town_id'] = 'N/A';
			$record['town_id'] = $this->sourceId('Town', $entry['town_id'], 'name', array(
					'location_id' => 1
			));
			if (!is_null($entry['street_id']))
			{
				$record['street_id'] = $this->sourceId('Street', $entry['street_id'], 'name', array(
						'town_id' => $record['town_id']
				));
			}
			if (!is_null($entry['building_id']))
			{
				$record['building_id'] = $this->sourceId('Building', $entry['building_id'], 'name', array(
						'location_id' => 1
				));
				if (!is_null($entry['floor_id']) || !is_null($entry['room_id']))
				{
					is_null($entry['floor_id'])
						&& $entry['floor_id'] = 'N/A';
					$record['floor_id'] = $this->sourceId('Floor', $entry['floor_id'], 'name', array(
							'building_id' => $record['building_id']
					));
				}
				if (!is_null($entry['room_id']))
				{
					$record['room_id'] = $this->sourceId('Room', $entry['room_id'], 'name', array(
							'floor_id' => $record['floor_id']
					));
				}
			}
			if (!is_null($entry['owner_id']))
			{
				$record['owner_id'] = $this->sourceId('Owner', $entry['owner_id']);
			}
			$gpsLat = !is_null($entry['lat_start'])
				? $entry['lat_start']
				: $entry['lat_end'];
			$gpsLong = !is_null($entry['long_start'])
				? $entry['long_start']
				: $entry['long_end'];
			if (!is_null($gpsLat) && !is_null($gpsLong))
			{
				$latN = strpos($gpsLat, '" S')
					? true
					: false;
				$gpsLat = trim(str_replace(array("?", "�", "'", '" N', '" S'), '', $gpsLat));
				list($deg, $min, $sec) = explode(' ', $gpsLat);
				$gpsLat = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0);
				$latN && $gpsLat *= -1.0;

				$longN = strpos($gpsLong, '" W')
					? true
					: false;
				$gpsLong = trim(str_replace(array("?", "�", "'", '" E', '" W', '" N', '" S'), '', $gpsLong));
				list($deg, $min, $sec) = explode(' ', $gpsLong);
				$gpsLong = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0);
				$longN && $gpsLong *= -1.0;

				$record['gps_lat']  = $gpsLat;
				$record['gps_long'] = $gpsLong;
			}

			$record['identifier'] = $entry['identifier'];
			$record['asset_type_id'] = $this->sourceId('AssetType', $entry['asset_type_id']);
			$record['asset_sub_type_id'] = $this->sourceId('AssetSubType', $entry['asset_sub_type_id'], 'name', array(
							'asset_type_id' => $record['asset_type_id']
					));
			$record['asset_description_id'] = $this->sourceId('AssetDescription', $entry['asset_description_id'], 'name', array(
							'asset_sub_type_id' => $record['asset_sub_type_id']
					));
			$record['asset_sub_description_id'] = $this->sourceId('AssetSubDescription', $entry['asset_sub_description_id'], 'name', array(
							'asset_description_id' => $record['asset_description_id']
					));
			if (!is_null($entry['details']))
			{
				$items = explode(' , ', $entry['details']);
				if (3 == count($items))
				{
					list($field, $material) = explode(':', $items[1]);
					$record['material_id'] = $this->sourceId('Material', $material);
					$record['details'] = $items[0] . ' , ' . $items[2];
				}
				else
				{
					$record['details'] = $entry['details'];
				}
			}
			$oAsset->save(null, array(), $record);
		}
		echo 'DONE';
	}


}

