<?php

namespace Spello;

class Project
{
	protected $search;
	protected $dictionary;

	function __construct(
		Search\SearchInterface $search,
		Dictionary\DictionaryInterface $dictionary
		)
	{
		$this->search = $search;
		$this->dictionary = $dictionary;
	}

	function train(Source\SourceInterface $source)
	{
		if ($this->word_count)
		{
			Assistant::train(
				$source,
				$this->word_count,
				new Search\WordCount
				);
		}

		return Assistant::train(
			$source,
			$this->dictionary,
			$this->search
			);
	}

	protected $word_count;

	function useWordCount(Dictionary\DictionaryInterface $word_count)
	{
		$this->word_count = $word_count;
	}

	function suggest($text)
	{
		return Assistant::suggest($text,
			$this->search,
			$this->dictionary,
			$this->word_count
			);
	}
}
