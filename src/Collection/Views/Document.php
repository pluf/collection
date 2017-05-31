<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('Collection_Shortcuts_NormalizeItemPerPage');

/**
 * Document views
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 * @author hadi<mohammad.hadi.mansouri@dpq.co.ir>
 */
class Collection_Views_Document
{

    /**
     * Creates new instance of a document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @param array $p            
     * @return Pluf_HTTP_Response
     */
    public function create ($request, $match, $p)
    {
        // check collection
        if (isset($match['collectionId'])) {
            $collectionId = $match['collectionId'];
            $request->REQUEST['collection'] = $collectionId;
        } else {
            $collectionId = $request->REQUEST['collection'];
        }
        Pluf_Shortcuts_GetObjectOr404('Collection_Collection', $collectionId);
        // create document
        $object = new Collection_Document();
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST, array());
        $object = $form->save();
        $this->putDocumentMap($object, $request->REQUEST);
        return new Pluf_HTTP_Response_Json($this->getDocumentMap($object));
    }

    /**
     * Gets a document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response_Json
     */
    public function get ($request, $match)
    {
        if (isset($match['collectionId'])) {
            $collectionId = $match['collectionId'];
        } else {
            $collectionId = $request->REQUEST['collectionId'];
        }
        $collection = Pluf_Shortcuts_GetObjectOr404('Collection_Collection', 
                $collectionId);
        $document = Pluf_Shortcuts_GetObjectOr404('Collection_Document', 
                $match['documentId']);
        if ($document->collection !== $collection->id) {
            throw new Pluf_Exception_DoesNotExist(
                    'Document with id (' . $document->id .
                             ') does not exist in collection with id (' .
                             $collection->id . ')');
        }
        return new Pluf_HTTP_Response_Json($document);
    }

    /**
     * Search for an document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function find ($request, $match)
    {
        // check for collection
        if (isset($match['collectionId'])) {
            $collectionId = $match['collectionId'];
        } elseif (isset($request->REQUEST['collectionId'])) {
            $collectionId = $request->REQUEST['collectionId'];
        }
        
        $document = new Collection_Document();
        $paginator = new Pluf_Paginator($document);
        if (isset($collectionId)) {
            $sql = new Pluf_SQL('collection=%s', 
                    array(
                            $collectionId
                    ));
            $paginator->forced_where = $sql;
        }
        $paginator->list_filters = array(
                'id'
        );
        $search_fields = array(
                'id'
        );
        $sort_fields = array(
                'id',
                'collection'
        );
        $paginator->configure(array(), $search_fields, $sort_fields);
        $paginator->items_per_page = Collection_Shortcuts_NormalizeItemPerPage(
                $request);
        $paginator->setFromRequest($request);
        $docs = $paginator->render_object();
        // TODO: maso, 2017: pass list of attributes
        foreach ($docs['items'] as $key => $value) {
            $docs['items'][$key] = $this->getDocumentMap($value);
        }
        return new Pluf_HTTP_Response_Json($docs);
    }

    /**
     * Removes a document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response_Json
     */
    public function remove ($request, $match)
    {
        if (isset($match['collectionId'])) {
            $collectionId = $match['collectionId'];
        } else {
            $collectionId = $request->REQUEST['collectionId'];
        }
        $collection = Pluf_Shortcuts_GetObjectOr404('Collection_Collection', 
                $collectionId);
        if (isset($match['documentId'])) {
            $documentId = $match['documentId'];
        } else {
            $documentId = $request->REQUEST['documentId'];
        }
        $document = Pluf_Shortcuts_GetObjectOr404('Collection_Document', 
                $documentId);
        
        if ($document->collection !== $collection->id) {
            throw new Pluf_Exception_DoesNotExist(
                    'Document with id (' . $documentId .
                             ') does not exist in collection with id (' .
                             $collectionId . ')');
        }
        $documentCopy = Pluf_Shortcuts_GetObjectOr404('Collection_Document', 
                $documentId);
        $document->delete();
        return new Pluf_HTTP_Response_Json($documentCopy);
    }

    /**
     * Updates a document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @param array $p            
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response
     */
    public function update ($request, $match, $p)
    {
        // check collection
        if (isset($match['collectionId'])) {
            $collectionId = $match['collectionId'];
            $request->REQUEST['collection'] = $collectionId;
        } else {
            $collectionId = $request->REQUEST['collection'];
        }
        $collection = Pluf_Shortcuts_GetObjectOr404('Collection_Collection', 
                $collectionId);
        $document = Pluf_Shortcuts_GetObjectOr404('Collection_Document', 
                $match['modelId']);
        if ($document->collection !== $collection->id) {
            throw new Pluf_Exception_DoesNotExist(
                    'Document with id (' . $document->id .
                             ') does not exist in collection with id (' .
                             $collection->id . ')');
        }
        // update document
        $plufService = new Pluf_Views();
        return $plufService->updateObject($request, $match, $p);
    }

    /**
     * Gets all attributes of a document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function getMap ($request, $match)
    {
        if (isset($match['collectionId'])) {
            $collectionId = $match['collectionId'];
        } else {
            $collectionId = $request->REQUEST['collectionId'];
        }
        // $collection = Pluf_Shortcuts_GetObjectOr404('Collection_Collection',
        // $collectionId);
        if (isset($match['documentId'])) {
            $documentId = $match['documentId'];
        } else {
            $documentId = $request->REQUEST['documentId'];
        }
        $document = Pluf_Shortcuts_GetObjectOr404('Collection_Document', 
                $documentId);
        
        if ($document->collection != $collectionId) {
            throw new Pluf_Exception_DoesNotExist(
                    'Document with id (' . $documentId .
                             ') does not exist in collection with id (' .
                             $collectionId . ')');
        }
        
        return new Pluf_HTTP_Response_Json($this->getDocumentMap($document));
    }

    /**
     * Puts attributes of a document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function putMap ($request, $match)
    {
        if (isset($match['collectionId'])) {
            $collectionId = $match['collectionId'];
        } else {
            $collectionId = $request->REQUEST['collectionId'];
        }
        // $collection = Pluf_Shortcuts_GetObjectOr404('Collection_Collection',
        // $collectionId);
        if (isset($match['documentId'])) {
            $documentId = $match['documentId'];
        } else {
            $documentId = $request->REQUEST['documentId'];
        }
        $document = Pluf_Shortcuts_GetObjectOr404('Collection_Document', 
                $documentId);
        
        if ($document->collection != $collectionId) {
            throw new Pluf_Exception_DoesNotExist(
                    'Document with id (' . $documentId .
                             ') does not exist in collection with id (' .
                             $collectionId . ')');
        }
        
        $this->putDocumentMap($document, $request->REQUEST);
        return new Pluf_HTTP_Response_Json($this->getDocumentMap($document));
    }
    
    /**
     * Gets all attributes of a document and return as map
     * 
     * @param $document 
     * @return maps of attributes
     */
    private function getDocumentMap($document){
        $attr = new Collection_Attribute();
        $map = $attr->getList(
                array(
                        'filter' => 'document=' . $document->id
                ));
        $result = array();
        $iterator = $map->getIterator();
        while ($iterator->valid()) {
            $attr = $iterator->current();
            $result[$attr->key] = $attr->value;
            $iterator->next();
        }
        
        $result['id'] = $document->id;
        $result['collection'] = $document->collection;
        return $result;
    }
    
    private function putDocumentMap($document, $map){
        $attrModel = new Collection_Attribute();
        foreach ($map as $key => $value) {
            // Ignore main attributes
            if ($key === 'id' || $key === 'collection') {
                continue;
            }
            $attr = $attrModel->getOne(
                    array(
                            'filter' => array(
                                    '`document`=' . $document->id,
                                    "`key`='" . $key . "'"
                            )
                    ));
            // FIXME: maso, 2017: remove key if value is empty
            if ($attr === null) {
                $attr2 = new Collection_Attribute();
                $attr2->document = $document;
                $attr2->key = $key;
                $attr2->value = $value;
                $attr2->create();
            } else {
                $attr->value = $value;
                $attr->update();
            }
        }
    }
}
