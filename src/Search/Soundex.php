<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;

class Soundex implements SearchInterface
{
	function suggest($word, DictionaryInterface $dictionary)
	{
		$soundex = soundex($word);
		$result = $dictionary->find($soundex);

		return is_scalar($result)
			? array($result)
			: $result;
	}

	function prepare($word, DictionaryInterface $dictionary)
	{
		$soundex = soundex($word);
		$result = (array) $dictionary->find($soundex);

		if (!in_array($word, $result))
		{
			$result[] = $word;
		}

		return $dictionary->set($soundex, $result);
	}
}
