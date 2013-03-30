<?php

/**
 * Table model for asset
 */
class Table_Asset extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'asset';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array(
        'Location' => array(
            'columns' => 'location_id',
            'refTableClass' => 'Table_Location',
            'refColumns' => 'id'
            ),
        'Town' => array(
            'columns' => 'town_id',
            'refTableClass' => 'Table_Town',
            'refColumns' => 'id'
            ),
        'Street' => array(
            'columns' => 'street_id',
            'refTableClass' => 'Table_Street',
            'refColumns' => 'id'
            ),
        'Building' => array(
            'columns' => 'building_id',
            'refTableClass' => 'Table_Building',
            'refColumns' => 'id'
            ),
        'Floor' => array(
            'columns' => 'floor_id',
            'refTableClass' => 'Table_Floor',
            'refColumns' => 'id'
            ),
        'Room' => array(
            'columns' => 'room_id',
            'refTableClass' => 'Table_Room',
            'refColumns' => 'id'
            ),
        'Owner' => array(
            'columns' => 'owner_id',
            'refTableClass' => 'Table_Owner',
            'refColumns' => 'id'
            ),
        'AssetType' => array(
            'columns' => 'asset_type_id',
            'refTableClass' => 'Table_AssetType',
            'refColumns' => 'id'
            ),
        'AssetSubType' => array(
            'columns' => 'asset_sub_type_id',
            'refTableClass' => 'Table_AssetSubType',
            'refColumns' => 'id'
            ),
        'AssetDescription' => array(
            'columns' => 'asset_description_id',
            'refTableClass' => 'Table_AssetDescription',
            'refColumns' => 'id'
            ),
        'AssetSubDescription' => array(
            'columns' => 'asset_sub_description_id',
            'refTableClass' => 'Table_AssetSubDescription',
            'refColumns' => 'id'
            ),
        'Material' => array(
            'columns' => 'material_id',
            'refTableClass' => 'Table_Material',
            'refColumns' => 'id'
            ),
        'PoleLength' => array(
            'columns' => 'pole_length_id',
            'refTableClass' => 'Table_PoleLength',
            'refColumns' => 'id'
            ),
        'StreetLightType' => array(
            'columns' => 'street_light_type_id',
            'refTableClass' => 'Table_StreetLightType',
            'refColumns' => 'id'
            ),
        'Condition' => array(
            'columns' => 'condition_id',
            'refTableClass' => 'Table_Condition',
            'refColumns' => 'id'
            )
        );

    /**
     * Tables dependant on this one.
     */
    protected $_dependentTables = array();

    /**
     * Data dependancy chain.
     */
    protected $dependancyChain = array();

    /**
     * Field meta data.
     */
    protected $_metadata = array(
        'id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'id',
            'COLUMN_POSITION' => 1,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => true,
            'PRIMARY_POSITION' => 1,
            'IDENTITY' => true,
            'FLAGS' => 2051,
            'FLAG_LIST' => 'FIELD_AUTOKEY | FIELD_REQUIRED'
            ),
        'location_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'location_id',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'town_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'town_id',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'street_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'street_id',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'building_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'building_id',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'floor_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'floor_id',
            'COLUMN_POSITION' => 6,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'room_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'room_id',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'owner_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'owner_id',
            'COLUMN_POSITION' => 8,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'gps_relative' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'gps_relative',
            'COLUMN_POSITION' => 9,
            'DATA_TYPE' => 'tinyint',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'gps_lat' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'gps_lat',
            'COLUMN_POSITION' => 10,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '50',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'gps_long' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'gps_long',
            'COLUMN_POSITION' => 11,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '50',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'identifier' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'identifier',
            'COLUMN_POSITION' => 12,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '100',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'asset_type_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'asset_type_id',
            'COLUMN_POSITION' => 13,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'asset_sub_type_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'asset_sub_type_id',
            'COLUMN_POSITION' => 14,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'asset_description_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'asset_description_id',
            'COLUMN_POSITION' => 15,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'asset_sub_description_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'asset_sub_description_id',
            'COLUMN_POSITION' => 16,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'material_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'material_id',
            'COLUMN_POSITION' => 17,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'pole_length_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'pole_length_id',
            'COLUMN_POSITION' => 18,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'street_light_type_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'street_light_type_id',
            'COLUMN_POSITION' => 19,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'condition_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'asset',
            'COLUMN_NAME' => 'condition_id',
            'COLUMN_POSITION' => 20,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            )
        );

    /**
     * No flags
     */
    protected $tableFlags = 0;

    /**
     * Field used to flag entry as archived.
     */
    protected $archiveField = false;

    /**
     * Field db-name to code-name mapping.
     */
    protected $fieldNames = array(
        'id' => 'Id',
        'location_id' => 'LocationId',
        'town_id' => 'TownId',
        'street_id' => 'StreetId',
        'building_id' => 'BuildingId',
        'floor_id' => 'FloorId',
        'room_id' => 'RoomId',
        'owner_id' => 'OwnerId',
        'gps_relative' => 'GpsRelative',
        'gps_lat' => 'GpsLat',
        'gps_long' => 'GpsLong',
        'identifier' => 'Identifier',
        'asset_type_id' => 'AssetTypeId',
        'asset_sub_type_id' => 'AssetSubTypeId',
        'asset_description_id' => 'AssetDescriptionId',
        'asset_sub_description_id' => 'AssetSubDescriptionId',
        'material_id' => 'MaterialId',
        'pole_length_id' => 'PoleLengthId',
        'street_light_type_id' => 'StreetLightTypeId',
        'condition_id' => 'ConditionId'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'location_id' => null,
        'town_id' => null,
        'street_id' => null,
        'building_id' => null,
        'floor_id' => null,
        'room_id' => null,
        'owner_id' => null,
        'gps_relative' => null,
        'gps_lat' => null,
        'gps_long' => null,
        'identifier' => null,
        'asset_type_id' => null,
        'asset_sub_type_id' => null,
        'asset_description_id' => null,
        'asset_sub_description_id' => null,
        'material_id' => null,
        'pole_length_id' => null,
        'street_light_type_id' => null,
        'condition_id' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[gps_lat]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[asset_gps_lat]';


}

