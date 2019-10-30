<?php

namespace Spello\Search;

use Spello\Dictionary\DictionaryInterface;
use Spello\Source\SourceInterface;
use Spello\Assistant;

class WordCount implements SearchInterface
{
	function suggest($text, DictionaryInterface $dictionary)
	{
		throw new \RuntimeException(
			__METHOD__ . '() can not be called'
		);
	}

	function prepare($word, DictionaryInterface $dictionary)
	{
		$count = 1 + (int) $dictionary->find($word);
		return $dictionary->set($word, $count);
	}

	/**
	* Sort words by word count, descending
	*
	* @param array $words
	* @param Spello\Dictionary\DictionaryInterface $dictionary
	* @return array
	*/
	static function sort(array $words, DictionaryInterface $dictionary)
	{
		$sorted = array();
		foreach ($words as $i => $word)
		{
			$sorted[ $i ] = $dictionary->find($word);
		}

		arsort($sorted);
		$result = array();
		foreach ($sorted as $i => $count)
		{
			$result[] = $words[ $i ];
		}

		return $result;
	}

	/**
	* Trim words which word count is less than $count from $source into $target
	*
	* @param integer $count
	* @param Spello\Dictionary\DictionaryInterface $source
	* @param Spello\Dictionary\DictionaryInterface $target
	*/
	static function trim($count, DictionaryInterface $source, DictionaryInterface $target)
	{
		$count = (int) $count;
		if ($count < 1)
		{
			throw new \InvalidArgumentException(
				__METHOD__ . '(): $count must be positive is_integer'
			);
		}

		$target->truncate();
		$words = $source->getIterator();
		foreach ($words as $word => $number)
		{
			if ($number >= $count)
			{
				$target->set($word, $number);
			}
		}

		return $target;
	}
}
