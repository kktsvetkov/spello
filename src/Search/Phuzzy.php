<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;

class Phuzzy implements SearchInterface
{
	function suggest($word, DictionaryInterface $dictionary)
	{
		return [];
	}

	function prepare($word, DictionaryInterface $dictionary)
	{
		throw new \RuntimeException(
			'This class uses dictionaries prepared by \Spello\Search\WordCount'
		);
	}
}
