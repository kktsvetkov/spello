<?php

namespace Spello\Source;

use Spello\Dictionary\DictionaryInterface;

class ShortWords implements SourceInterface
{
	protected $source;
	protected $length;

	function __construct(SourceInterface $source, $length = 2)
	{
		if ($length < 1)
		{
			throw new \InvalidArgumentException(
				__METHOD__ . '() $length argument must be a positive integer'
			);
		}

		$this->source = $source;
		$this->length = (int) $length;
	}

	function fetch()
	{
		if (false !== ($text = $this->source->fetch()))
		{
			if (!$words = \Spello\Assistant::extract($text))
			{
				return $text;
			}

			foreach ($words as $i => $word)
			{
				if (strlen($word) < $this->length)
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
}
