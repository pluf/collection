<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
return array(
    // ************************************************************* Collection
    array( // Find
        'regex' => '#^/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Collection_Collection',
            'listFilters' => array(
                'id',
                'name',
                'title'
            ),
            'searchFields' => array(
                'name',
                'title',
                'description'
            ),
            'sortFields' => array(
                'id',
                'name',
                'title'
            )
        )
    ),
    array( // Create
        'regex' => '#^/new$#',
        'model' => 'Pluf_Views',
        'method' => 'createObject',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Collection_Collection'
        )
    ),
    array( // Get info
        'regex' => '#^/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Collection_Collection'
        )
    ),
    array( // Get info (by name)
        'regex' => '#^/(?P<name>[^/]+)$#',
        'model' => 'Collection_Views_Collection',
        'method' => 'getByName',
        'http-method' => 'GET'
    ),
    array( // Delete
        'regex' => '#^/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'params' => array(
            'model' => 'Collection_Collection',
            'permanently' => true
        )
    ),
    array( // Update
        'regex' => '#^/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Collection_Collection'
        )
    ),
    // ************************************************************* Document of Collection
    array( // Find Document of Collection
        'regex' => '#^/(?P<collectionId>\d+)/document/find$#',
        'model' => 'Collection_Views_Document',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array( // Create new Document in Collection
        'regex' => '#^/(?P<collectionId>\d+)/document/new$#',
        'model' => 'Collection_Views_Document',
        'method' => 'create',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Collection_Document'
        ),
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'Pluf_Precondition::memberRequired'
        )
    ),
    array( // Get information
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)$#',
        'model' => 'Collection_Views_Document',
        'method' => 'getMap',
        'http-method' => 'GET'
    ),
    array( // Update a Document of Collection
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)$#',
        'model' => 'Collection_Views_Document',
        'method' => 'putMap',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Collection_Document'
        ),
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'Pluf_Precondition::memberRequired'
        )
    ),
    array( // Remove a Document
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)$#',
        'model' => 'Collection_Views_Document',
        'method' => 'remove',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'Pluf_Precondition::memberRequired'
        )
    ),
    array( // Remove a Document
        'regex' => '#^/(?P<collectionId>\d+)/document$#',
        'model' => 'Collection_Views_Document',
        'method' => 'remove',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'Pluf_Precondition::memberRequired'
        )
    ),
    array( // Get Document Value
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)/map$#',
        'model' => 'Collection_Views_Document',
        'method' => 'getMap',
        'http-method' => 'GET'
    ),
    array( // Update/Add Document Value
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)/map$#',
        'model' => 'Collection_Views_Document',
        'method' => 'putMap',
        'http-method' => 'POST'
    ),
    // ************************************************************* Attributes of Document
    array( // Find Attributes
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)/attribute/find$#',
        'model' => 'Collection_Views_Attribute',
        'method' => 'find',
        'http-method' => 'GET'
    ),
    array( // Create new Attribute in Document
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)/attribute/new$#',
        'model' => 'Collection_Views_Attribute',
        'method' => 'create',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Collection_Attribute'
        ),
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'Pluf_Precondition::memberRequired'
        )
    ),
    array( // Get Attribute
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)/attribute/(?P<attributeId>\d+)$#',
        'model' => 'Collection_Views_Attribute',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array( // Update Attribute
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)/attribute/(?P<modelId>\d+)$#',
        'model' => 'Collection_Views_Attribute',
        'method' => 'update',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Collection_Attribute'
        ),
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'Pluf_Precondition::memberRequired'
        )
    ),
    array( // Delete Attribute
        'regex' => '#^/(?P<collectionId>\d+)/document/(?P<documentId>\d+)/attribute/(?P<attributeId>\d+)$#',
        'model' => 'Collection_Views_Attribute',
        'method' => 'remove',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::loginRequired',
            'Pluf_Precondition::memberRequired'
        )
    )
);

