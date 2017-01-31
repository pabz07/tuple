<?php

namespace Marahuyo\Tuple\Eloquent;

use Illuminate\Database\Eloquent\Collection as BaseCollection;

class Collection extends BaseCollection{


    public function toArrayKeyValuePair()
    {
        $results = array();

        foreach ($this->items as $item) {
            if(method_exists ( $item, 'toArrayKeyValuePair' )){
                $results = array_merge($results, $item->toArrayKeyValuePair());
            }
        }
        return $results;
    }
}
