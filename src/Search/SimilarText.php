<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;

class SimilarText implements SearchInterface
{
	function suggest($word, DictionaryInterface $dictionary)
	{
		$closest = 0;
		$result = array();

		$words = $dictionary->getIterator();
		foreach ($words as $compare => $dummy)
		{
			$s = similar_text($word, $compare);
			if ($s >= $closest)
			{
				if ($s > $closest)
				{
					$closest = $s;
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
