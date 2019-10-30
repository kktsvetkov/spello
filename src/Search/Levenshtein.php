<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;

class Levenshtein implements SearchInterface
{
	function suggest($word, DictionaryInterface $dictionary)
	{
		$closest = PHP_INT_MAX;
		$result = array();

		$words = $dictionary->getIterator();
		foreach ($words as $compare => $dummy)
		{
			$l = levenshtein($word, $compare);
			if ($l <= $closest)
			{
				if ($l < $closest)
				{
					$closest = $l;
					$result = array();
				}

				$result[] = $compare;
			}
		}

		return $result;
	}

	function prepare($word, DictionaryInterface $dictionary)
	{
		throw new \RuntimeException(
			'This class uses dictionaries prepared by \Spello\Search\WordCount'
		);
	}
}
