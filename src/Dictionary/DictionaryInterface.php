<?php

namespace Spello\Dictionary;

interface DictionaryInterface extends \IteratorAggregate
{
	function set($key, $value);

	function find($key);

	function truncate();
}
