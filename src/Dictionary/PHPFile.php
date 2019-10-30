<?php

namespace Spello\Dictionary;

class PHPFile extends AsArray
{
	protected $file = '';

	function __construct($file)
	{
		$this->file = $file;

		if (file_exists($this->file))
		{
			$data = include $this->file;
			if (is_array($data))
			{
				parent::__construct($data);
			}
		}
	}

	function save()
	{
		file_put_contents(
			$this->file,
			'<?php return ' . var_export($this->data, 1) . ';'
		);
	}

	function __destruct()
	{
		$this->save();
	}
}
