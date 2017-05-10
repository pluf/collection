<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
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
    public static function create ($request, $match, $p)
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
        $plufService = new Pluf_Views();
        return $plufService->createObject($request, $match, $p);
    }

    /**
     * Gets a document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response_Json
     */
    public static function get ($request, $match)
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
    public static function find ($request, $match)
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
        return new Pluf_HTTP_Response_Json($paginator->render_object());
    }

    /**
     * Removes a document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response_Json
     */
    public static function remove ($request, $match)
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
    public static function update ($request, $match, $p)
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
    public static function getMap ($request, $match)
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
        
        $attr = new Collection_Attribute();
        $map = $attr->getList(
                array(
                        'filter' => 'document=' . $documentId
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
        
        return new Pluf_HTTP_Response_Json($result);
    }

    /**
     * Puts attributes of a document
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public static function putMap ($request, $match)
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
        
        $result = array();
        $attrModel = new Collection_Attribute();
        foreach ($request->REQUEST as $key => $value) {
            // Ignore main attributes
            if ($key === 'id' || $key === 'collection') {
                continue;
            }
            $attr = $attrModel->getOne(
                    array(
                            'filter' => array(
                                    '`document`=' . $documentId,
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
            $result[$key] = $value;
        }
        return new Pluf_HTTP_Response_Json($result);
    }
}
