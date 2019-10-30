<?php

namespace Spello\Dictionary;

class JSONFile extends AsArray
{
	protected $file = '';

	function __construct($file)
	{
		$this->file = $file;

		if (file_exists($this->file))
		{
			if ($json = file_get_contents($this->file))
			{
				$data = json_decode($json, true);
				if (is_array($data))
				{
					parent::__construct($data);
				}
			}
		}
	}

	function save()
	{
		file_put_contents(
			$this->file,
			json_encode($this->data, JSON_NUMERIC_CHECK)
		);
	}

	function __destruct()
	{
		$this->save();
	}
}
