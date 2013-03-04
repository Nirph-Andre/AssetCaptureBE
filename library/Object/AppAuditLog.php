<?php

/**
 * DataAccess Value Object for table app_audit_log
 */
class Object_AppAuditLog extends Struct_Abstract_DataAccess
{

    /**
     * Namespace used for raising events.
     */
    protected $_eventNamespace = 'AppAuditLog';

    /**
     * Table this value object owns and may directly modify.
     */
    protected $_table = 'app_audit_log';

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
        'customer_context' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '30')
                    ))
            ),
        'customer_id' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => -2147483648,
                        'max' => 2147483647
                        )
                    ))
            ),
        'action' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'InArray',
                    'params' => array(
                        'Add',
                        'Update',
                        'Delete'
                        )
                    ))
            ),
        'table_name' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => '50')
                    ))
            ),
        'record_id' => array(
            'required' => true,
            'validators' => array(array(
                    'type' => 'Between',
                    'params' => array(
                        'min' => -2147483648,
                        'max' => 2147483647
                        )
                    ))
            ),
        'data_packet' => array(
            'required' => false,
            'validators' => array(array(
                    'type' => 'StringLength',
                    'params' => array('max' => 16777215)
                    ))
            )
        );


}

