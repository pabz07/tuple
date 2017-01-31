<?php

namespace Marahuyo\Tuple\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class UsesKeyValuePairScope implements ScopeInterface {

     /**
      * All of the extensions to be added to the builder.
      *
      * @var array
      */
    protected $extensions = [ 'GetAsAttribute' ];

    /**
	 * Apply the scope to a given Eloquent query builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	public function apply(Builder $builder)
    {
        $this->extend($builder);
    }

    /**
	 * Remove the scope from the given Eloquent query builder.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $builder
	 * @return void
	 */
	public function remove(Builder $builder)
    {

    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

    }

    /**
     * Get the "meta key" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return string
     */
    protected function getMetaKeyColumn(Builder $builder)
    {
        if (count($builder->getQuery()->joins) > 0)
            return $builder->getModel()->getQualifiedMetaKeyColumn();

        return $builder->getModel()->getMetaKeyColumn();
    }

    /**
     * Get the "meta value" column for the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return string
     */
    protected function getMetaValueColumn(Builder $builder)
    {
        if (count($builder->getQuery()->joins) > 0)
            return $builder->getModel()->getQualifiedMetaValueColumn();

        return $builder->getModel()->getMetaValueColumn();
    }

    /**
     * Add the force keyValuePair extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addGetAsAttribute(Builder $builder)
    {
        $builder->macro('getAsAttribute', function (Builder $builder) {
            $item    = clone $builder->getModel();
            $results = $builder->get();

            foreach ($results as $result)
            {
                if(!array_key_exists($this->getMetaKeyColumn($builder), $result->getAttributes()))
                    throw new \Exception("Column " . $this->getMetaKeyColumn($builder) . " does not exist!");

                if(!array_key_exists($this->getMetaValueColumn($builder), $result->getAttributes()))
                    throw new \Exception("Column " . $this->getMetaValueColumn($builder) . " does not exist!");

                $item->{ $result->{ $this->getMetaKeyColumn($builder) } } = $result->{ $this->getMetaValueColumn($builder) };
            }

            return $builder->setModel($item)->getModel();
        });
    }

}
