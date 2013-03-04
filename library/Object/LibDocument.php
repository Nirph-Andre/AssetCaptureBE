<?php

/**
 * DataAccess Value Object for table lib_document
 */
class Object_LibDocument extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'LibDocument';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'lib_document';

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
        'document' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => 4294967295)
                    ))
            ),
        'mime_type' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '200')
                    ))
            )
        );


}

