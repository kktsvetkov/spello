<?php

namespace Spello\Source;

use Spello\Assistant;
use Spello\Search;
use Spello\Dictionary;

class StopWords implements SourceInterface
{
	protected $source;
	protected $stopwords;

	function __construct(
		SourceInterface $source,
		Dictionary\DictionaryInterface $stopwords
		)
	{
		$this->source = $source;
		$this->stopwords = $stopwords;
	}

	function fetch()
	{
		if (false !== ($text = $this->source->fetch()))
		{
			if (!$words = Assistant::extract($text))
			{
				return $text;
			}

			foreach ($words as $i => $word)
			{
				if ($this->stopwords->find($word))
				{
					unset($words[$i]);
				}
			}

			return join(' ', $words);
		}

		return false;
	}

	function reset()
	{
		return $this->source->reset();
	}

	static function fromArray(
		array $words,
		Dictionary\DictionaryInterface $stopwords = null
		)
	{
		if (!$stopwords)
		{
			$stopwords = new Dictionary\AsArray;
		}

		foreach ($words as $word)
		{
			$stopwords->set($word, 1);
		}

		return $stopwords;
	}
}
