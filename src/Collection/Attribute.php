<?php

class Collection_Attribute extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'collection_attribute';
        $this->_a['verbose'] = 'Collection Attribute';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            'key' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 250,
                'editable' => true,
                'readable' => true
            ),
            'value' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 500,
                'editable' => true,
                'readable' => true
            ),
            // relations
            'document' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Collection_Document',
                'blank' => false,
                'relate_name' => 'document',
                'editable' => true,
                'readable' => true
            ),
        );
        
        $this->_a['idx'] = array(
            'attribute_idx' => array(
                'col' => 'key, document',
                'type' => 'unique', // normal, unique, fulltext, spatial
                'index_type' => '', // hash, btree
                'index_option' => '',
                'algorithm_option' => '',
                'lock_option' => ''
            )
        );
    }

}