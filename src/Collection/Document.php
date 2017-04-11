<?php

class Collection_Document extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'collection_document';
        $this->_a['verbose'] = 'Collection Document';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => false,
                'editable' => false,
                'readable' => true
            ),
            // relations
            'collection' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Collection_Collection',
                'blank' => false,
                'relate_name' => 'collection',
                'editable' => true,
                'readable' => true
            ),
        );
        
        $this->_a['idx'] = array(
            'document_idx' => array(
                'col' => 'id, collection',
                'type' => 'unique', // normal, unique, fulltext, spatial
                'index_type' => '', // hash, btree
                'index_option' => '',
                'algorithm_option' => '',
                'lock_option' => ''
            )
        );
    }

}