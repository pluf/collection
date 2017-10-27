<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Collection_Shortcuts_NormalizeItemPerPage');

class Collection_Views_Attribute
{
    // *******************************************************************
    // Attributes of Document
    // *******************************************************************
    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @param array $p            
     * @return Pluf_HTTP_Response
     */
    public static function create($request, $match, $p)
    {
        // check document
        if (isset($match['documentId'])) {
            $documentId = $match['documentId'];
            $request->REQUEST['document'] = $documentId;
        } else {
            $documentId = $request->REQUEST['document'];
        }
        Pluf_Shortcuts_GetObjectOr404('Collection_Document', $documentId);
        // create attribute
        $plufService = new Pluf_Views();
        return $plufService->createObject($request, $match, $p);
    }

    public static function get($request, $match){
        if (isset($match['documentId'])) {
            $documentId = $match['documentId'];
        } else {
            $documentId = $request->REQUEST['documentId'];
        }
        $document = Pluf_Shortcuts_GetObjectOr404('Collection_Document', $documentId);
        $attribute = Pluf_Shortcuts_GetObjectOr404('Collection_Attribute', $match['attributeId']);
        if ($attribute->document !== $document->id) {
            throw new Pluf_Exception_DoesNotExist('Attribute with id (' . $attribute->id . ') does not exist in document with id (' . $document->id . ')');
        }
        return new Pluf_HTTP_Response_Json($attribute);
    }
    
    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @return Pluf_HTTP_Response_Json
     */
    public static function find($request, $match)
    {
        // check for document
        if (isset($match['documentId'])) {
            $documentId = $match['documentId'];
        } elseif (isset($request->REQUEST['documentId'])) {
            $documentId = $request->REQUEST['documentId'];
        }
        
        $attribute = new Collection_Attribute();
        $paginator = new Pluf_Paginator($attribute);
        if (isset($documentId)) {
            $sql = new Pluf_SQL('document=%s', array(
                $documentId
            ));
            $paginator->forced_where = $sql;
        }
        $paginator->list_filters = array(
            'id',
            'key'
        );
        $search_fields = array(
            'key',
            'value'
        );
        $sort_fields = array(
            'id',
            'key',
            'value',
            'domain'
        );
        $paginator->configure(array(), $search_fields, $sort_fields);
        $paginator->items_per_page = Collection_Shortcuts_NormalizeItemPerPage($request);
        $paginator->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($paginator->render_object());
    }

    public static function remove($request, $match)
    {
        if (isset($match['documentId'])) {
            $documentId = $match['documentId'];
        } else {
            $documentId = $request->REQUEST['documentId'];
        }
        $document = Pluf_Shortcuts_GetObjectOr404('Collection_Document', $documentId);
        if (isset($match['attributeId'])) {
            $attributeId = $match['attributeId'];
        } else {
            $attributeId = $request->REQUEST['attributeId'];
        }
        $attribute = Pluf_Shortcuts_GetObjectOr404('Collection_Attribute', $attributeId);
        
        if ($attribute->document !== $document->id) {
            throw new Pluf_Exception_DoesNotExist('Attribute with id (' . $attributeId . ') does not exist in document with id (' . $documentId . ')');
        }
        $attributeCopy = Pluf_Shortcuts_GetObjectOr404('Collection_Attribute', $attributeId);
        $attribute->delete();
        return new Pluf_HTTP_Response_Json($attributeCopy);
    }
    
    public static function update($request, $match, $p)
    {
        // check document
        if (isset($match['documentId'])) {
            $documentId = $match['documentId'];
            $request->REQUEST['document'] = $documentId;
        } else {
            $documentId = $request->REQUEST['document'];
        }
        $document = Pluf_Shortcuts_GetObjectOr404('Collection_Document', $documentId);
        $attribute = Pluf_Shortcuts_GetObjectOr404('Collection_Attribute', $match['modelId']);
        if ($attribute->document !== $document->id) {
            throw new Pluf_Exception_DoesNotExist('Attribute with id (' . $attribute->id . ') does not exist in document with id (' . $document->id . ')');
        }
        // update attribute
        $plufService = new Pluf_Views();
        return $plufService->updateObject($request, $match, $p);
    }
}
