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
			if (is_null($entry['identifier']) || is_null($entry['asset_type_id'])
				|| is_null($entry['asset_sub_type_id']) || is_null($entry['town_id']))
			{
				error_log("Skipping: $i");
				$this->addLine('', $data);
				continue;
			}

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
				$gpsLat = trim(str_replace(array("?", "°", "'", '" N', '" S'), '', $gpsLat));
				list($deg, $min, $sec) = explode(' ', $gpsLat);
				$gpsLat = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0);
				$latN && $gpsLat *= -1.0;

				$longN = strpos($gpsLong, '" W')
					? true
					: false;
				$gpsLong = trim(str_replace(array("?", "°", "'", '" E', '" W'), '', $gpsLong));
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
			$assetRes = $oAsset->save(null, array(), $record);
			if (!isset($assetRes->data['id']))
			{
				exit();
			}

			$this->addLine($assetRes->data['id'], $data);
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
				$gpsLat = trim(str_replace(array("?", "°", "'", '" N', '" S'), '', $gpsLat));
				list($deg, $min, $sec) = explode(' ', $gpsLat);
				$gpsLat = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0);
				$latN && $gpsLat *= -1.0;

				$longN = strpos($gpsLong, '" W')
					? true
					: false;
				$gpsLong = trim(str_replace(array("?", "°", "'", '" E', '" W'), '', $gpsLong));
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
				$gpsLat = str_replace(array("-", "?", "°", "'", '" N', '" S'), '', $gpsLat);
				/* list($deg, $min, $sec) = explode(' ', $gpsLat);
				$gpsLat = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0); */
				$gpsLat *= -1.0;

				/* $longN = strpos($gpsLong, '" W')
					? true
					: false; */
				$gpsLong = str_replace(array("-", "?", "°", "'", '" E', '" W'), '', $gpsLong);
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
				$gpsLat = trim(str_replace(array("?", "°", "'", '" N', '" S'), '', $gpsLat));
				list($deg, $min, $sec) = explode(' ', $gpsLat);
				$gpsLat = $deg * 1.0 + (($min * 1.0 + ($sec / 60.0)) / 60.0);
				$latN && $gpsLat *= -1.0;

				$longN = strpos($gpsLong, '" W')
					? true
					: false;
				$gpsLong = trim(str_replace(array("?", "°", "'", '" E', '" W', '" N', '" S'), '', $gpsLong));
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

