<?php namespace Marahuyo\Tuple\Eloquent;

use Marahuyo\Tuple\Eloquent\Relationship\HasMeta;

trait WithMetaKeyValuePair {

    /**
	 *
	 * @param  string  $related
	 * @param  string  $foreignKey
	 * @param  string  $localKey
	 * @return \Marahuyo\Tuple\Eloquent\Relations\HasMeta
	 */
	public function hasMeta($related, $foreignKey = null, $localKey = null)
	{
		$foreignKey = $foreignKey ?: $this->getForeignKey();

		$instance = new $related;

		$localKey = $localKey ?: $this->getKeyName();

		return new HasMeta($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
	}
}
