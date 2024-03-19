<?php

use MODX\Revolution\Processors\Resource\GetList as BaseGetList;
use MODX\Revolution\modResource;
use MODX\Revolution\modContext;
use xPDO\Om\xPDOQuery;

class SearchResource extends BaseGetList
{
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin(modContext::class, 'Context', 'modResource.context_key = Context.key');
        
        $query = $this->getProperty('query');
        $current = $this->getProperty('current');
        
        if (!empty($query)) {
            $c->where([
                'modResource.pagetitle:LIKE' => "%{$query}%",
                'OR:modResource.id:LIKE'     => "%{$query}%",
                'OR:modResource.longtitle:LIKE' => "%{$query}%",
                'OR:modResource.uri:LIKE'    => "%{$query}%",
            ]);
        }
        
        if (!empty($current)) {
            $c->where([
                'modResource.id:!=' => $current,
            ]);
        }

        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns(modResource::class, 'modResource'));
        $c->select(['context_name' => 'Context.name']);
        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        $array = parent::prepareRow($object);
        if (!$array) {
            return false;
        }
        
        return $array;
    }
}

return SearchResource::class;
