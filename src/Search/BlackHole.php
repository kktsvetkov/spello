<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;

class BlackHole implements SearchInterface
{
	function suggest($word, DictionaryInterface $dictionary)
	{
		return [];
	}

	function prepare($word, DictionaryInterface $dictionary)
	{
		return '';
	}
}
