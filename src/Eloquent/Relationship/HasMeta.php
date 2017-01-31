<?php namespace Marahuyo\Tuple\Eloquent\Relationship;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class HasMeta extends HasOneOrMany {

	/**
	 * Get the results of the relationship.
	 *
	 * @return mixed
	 */
	public function getResults()
	{
		return $this->query->getAsAttribute();
	}

	/**
	 * Initialize the relation on a set of models.
	 *
	 * @param  array   $models
	 * @param  string  $relation
	 * @return array
	 */
	public function initRelation(array $models, $relation)
	{

		foreach ($models as $model)
		{
			$model->setRelation($relation, $this->related->newCollection());
		}

		return $models;
	}

	/**
	 * Match the eagerly loaded results to their parents.
	 *
	 * @param  array   $models
	 * @param  \Illuminate\Database\Eloquent\Collection  $results
	 * @param  string  $relation
	 * @return array
	 */
	public function match(array $models, Collection $results, $relation)
	{
		return $this->matchMany($models, $results, $relation);
	}

	public function matchMany(array $models, Collection $results, $relation)
	{
		if(method_exists($results, 'toArrayKeyValuePair'))
			$dictionary = $this->buildDictionaryAsAttribute($results);
		else
			$dictionary = $this->buildDictionary($results);

		foreach ($models as $model)
		{
			$key = $model->getAttribute($this->localKey);

			if (isset($dictionary[$key]))
			{
				$value = $this->getRelationValue($dictionary, $key, 'many');
				$model->setRelation($relation, $value);
			}
		}

		return $models;
	}

	/**
	 * Build model dictionary keyed by the relation's foreign key.
	 *
	 * @param  \Illuminate\Database\Eloquent\Collection  $results
	 * @return array
	 */
	protected function buildDictionaryAsAttribute(Collection $results)
	{
		$dictionary = array();

		$foreign = $this->getPlainForeignKey();

		foreach ($results as $result)
		{
			$dictionary[$result->{$foreign}] = array_merge(
				//[$foreign => $result->{$foreign}],
				$result->toArrayKeyValuePair() ,
				isset($dictionary[$result->{$foreign}]) ? $dictionary[$result->{$foreign}] : []
			);
		}

		return $dictionary;
	}

}
