<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;

class BlackHole implements SearchInterface
{
	protected $suggest;
	protected $prepare;

	function __construct(callable $suggest, callable $prepare)
	{
		$this->suggest = $suggest;
		$this->prepare = $prepare;
	}

	function suggest($word, DictionaryInterface $dictionary)
	{
		return call_user_func($this->suggest, $word, $dictionary);
	}

	function prepare($word, DictionaryInterface $dictionary)
	{
		return call_user_func($this->prepare, $word, $dictionary);
	}
}
