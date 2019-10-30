<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;

class Metaphone implements SearchInterface
{
	function suggest($word, DictionaryInterface $dictionary)
	{
		$metaphone = metaphone($word);
		$result = $dictionary->find($metaphone);

		return is_scalar($result)
			? array($result)
			: $result;
	}

	function prepare($word, DictionaryInterface $dictionary)
	{
		$metaphone = metaphone($word);
		$result = (array) $dictionary->find($metaphone);

		if (!in_array($word, $result))
		{
			$result[] = $word;
		}

		return $dictionary->set($metaphone, $result);
	}
}
