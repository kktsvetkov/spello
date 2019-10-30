<?php

namespace Spello\Source;

class FromArray implements SourceInterface
{
	protected $words = [];

	function __construct(array $words)
	{
		$this->words = $words;
		reset($this->words);
	}

	function fetch()
	{
		if ($word = current($this->words))
		{
			next($this->words);
		}
		
		return (NULL === $word)
			? false
			: $word;
	}

	function reset()
	{
		reset($this->words);
	}
}
