<?php

function Collection_Shortcuts_NormalizeItemPerPage($request)
{
    $count = array_key_exists('_px_c', $request->REQUEST) ? intval($request->REQUEST['_px_c']) : 30;
    if ($count > 30)
        $count = 30;
    return $count;
}

/**
 * Returns collection with given name or throws 404 not found exception if such collection does not exist.
 * 
 * @param string $name
 * @throws Pluf_Exception_DoesNotExist
 * @return Collection_Collection
 */
function Collection_Shortcuts_GetCollectionByNameOr404($name)
{
    $collection = Collection_Shortcuts_GetCollectionByName($name);
    if ($collection === null)
        throw new Pluf_Exception_DoesNotExist("Collection not found (Collection name:" . $name . ")");
    return $collection;
}

/**
 * Returns collection with given name and returns null if there is no collection with given name.
 * 
 * @param string $name
 * @return Collection_Collection|NULL
 */
function Collection_Shortcuts_GetCollectionByName($name)
{
    $q = new Pluf_SQL('name=%s', array(
        $name
    ));
    $item = new Collection_Collection();
    $item = $item->getList(array(
        'filter' => $q->gen()
    ));
    if (isset($item) && $item->count() == 1) {
        return $item[0];
    }
    if ($item->count() > 1) {
        Pluf_Log::error(sprintf('more than one collection exist with the name $s', $name));
        return $item[0];
    }
    return null;
}