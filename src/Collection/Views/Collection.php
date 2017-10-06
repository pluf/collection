<?php
Pluf::loadFunction('Collection_Shortcuts_GetCollectionByNameOr404');

class Collection_Views_Collection
{

    public static function getByName($request, $match)
    {
        $collection = Collection_Shortcuts_GetCollectionByNameOr404($match['name']);
        // TODO: hadi 1396-07-10: بررسی حق دسترسی
        // اجرای درخواست
        return new Pluf_HTTP_Response_Json($collection);
    }

}