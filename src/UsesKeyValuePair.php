<?php

namespace Marahuyo\Tuple;

use Marahuyo\Tuple\Eloquent\Collection as EloquentCollection;
use Marahuyo\Tuple\Eloquent\UsesKeyValuePairScope;

trait UsesKeyValuePair {

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootUsesKeyValuePair()
    {

        static::addGlobalScope(new UsesKeyValuePairScope);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new EloquentCollection($models);
    }

    /**
     * Convert results to key value pair
     *
     * @return array
     */
    public function toArrayKeyValuePair()
    {
        if( !$this->isDirty() ) {
            $item = new static;
            $item->{ $this->{ $this->getMetaKeyColumn() } } = $this->{ $this->getMetaValueColumn() };
            return $item->toArray();
        }

        return $this->toArray();

    }

    /**
     * Get the name of the "meta key" column.
     *
     * @return string
     */
    public function getMetaKeyColumn()
    {
        return defined('static::META_KEY') ? static::META_KEY : 'meta_key';
    }

    /**
     * Get the name of the "meta value" column.
     *
     * @return string
     */
    public function getMetaValueColumn()
    {
        return defined('static::META_VALUE') ? static::META_VALUE : 'meta_value';
    }

    /**
     * Get the fully qualified "meta key" column.
     *
     * @return string
     */
    public function getQualifiedMetaKeyColumn()
    {
        return $this->getTable().'.'.$this->getMetaKeyColumn();
    }

    /**
     * Get the fully qualified meta value" column.
     *
     * @return string
     */
    public function getQualifiedMetaValueColumn()
    {
        return $this->getTable().'.'.$this->getMetaValueColumn();
    }
}
