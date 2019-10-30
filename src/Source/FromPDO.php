<?php

namespace Spello\Source;

class FromPDO implements SourceInterface
{
	protected $query;
	protected $row = [];

	function __construct(\PDOStatement $query)
	{
		$this->query = $query;
		$this->query->execute();
	}

	function fetch()
	{
		if ($text = array_shift($this->row))
		{
			return $text;
		}

		if ($this->row = $this->query->fetch(\PDO::FETCH_NUM))
		{
			return array_shift($this->row);
		}

		$this->row = [];
		return false;
	}

	function reset()
	{
		$this->query->execute();
	}
}
