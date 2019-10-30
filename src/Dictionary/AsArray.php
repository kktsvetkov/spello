<?php

namespace Spello\Dictionary;

class AsArray implements DictionaryInterface
{
	protected $data = [];

	function __construct(array $data = [])
	{
		$this->data = $data;
	}

	function set($key, $value)
	{
		$key = filter_var($key, FILTER_SANITIZE_STRING);
		return $this->data[$key] = $value;
	}

	function find($key)
	{
		$key = filter_var($key, FILTER_SANITIZE_STRING);

		return isset($this->data[$key])
			? $this->data[$key]
			: null;
	}

	function truncate()
	{
		unset($this->data);
		$this->data = [];
	}

	function getIterator()
	{
		return new \ArrayIterator($this->data);
	}
}
