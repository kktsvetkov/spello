<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;

/**
* Proxy cache
*
* This class will cache search matches from $search into the $cache dictionary
*/
class Cache implements SearchInterface
{
	protected $search;
	protected $cache;

	function __construct(
		SearchInterface $search,
		DictionaryInterface $cache
		)
	{
		$this->search = $search;
		$this->cache = $cache;
	}

	function suggest($word, DictionaryInterface $dictionary)
	{
		if ($improved = $this->cache->find($word))
		{
			return $improved;
		}

		$improved = $this->search->suggest($word, $dictionary);
		$this->cache->set($word, $improved);

		return $improved;
	}

	function prepare($word, DictionaryInterface $dictionary)
	{
		return $this->search->prepare($word, $dictionary);
	}
}
