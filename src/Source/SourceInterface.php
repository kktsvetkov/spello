<?php

namespace Spello\Source;

interface SourceInterface
{
	function fetch();
	function reset();
}
