<?php

/**
 * DataAccess Value Object for table photo
 */
class Object_Photo extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'Photo';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'photo';

    /**
     * Unique identification field(s).
     */
    protected $_uniqueIdentifier = array();

    /**
     * Validation meta-data.
     */
    protected $_validation = array(
        'id' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'Digits',
                    'params' => array()
                    ))
            ),
        'asset_id' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => 0,
                        'max' => 4294967295
                        )
                    ))
            ),
        'type' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '100')
                    ))
            ),
        'data' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => 65535)
                    ))
            ),
        'archived' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'InArray',
                    'params' => array(
                        '0',
                        '1'
                        )
                    ))
            )
        );


}

