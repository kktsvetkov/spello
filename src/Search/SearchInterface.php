<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;

interface SearchInterface
{
	function suggest($word, DictionaryInterface $dictionary);

	function prepare($word, DictionaryInterface $dictionary);
}
