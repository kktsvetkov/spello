<?php

namespace Spello\Tests;

use PHPUnit\Framework\TestCase;
use Spello\Assistant;

class Assistant_Test extends TestCase
{
	/**
	* @dataProvider provider_extract
	* @covers Spello\Assistant::extract()
	*/
	function test_extract($string, $result)
	{
		$this->assertEquals(
			Assistant::extract($string),
			$result
			);
	}

	function provider_extract()
	{
		return array(
			array('', []),
			array(' ', []),
			array('-1234', ['-1234']),
			array('proba ', ['proba']),
			array(' proba ', ['proba']),
			array(' proba', ['proba']),
			array(' Proba? ', ['proba']),
			array('proba na probata', ['proba', 'na', 'probata']),
			array(' proba na	probata. ', ['proba', 'na', 'probata']),
			array('Proba NA pRoBaTa', ['proba', 'na', 'probata']),
			array("PROBA	NA \n \r PROBATA", ['proba', 'na', 'probata']),
		);
	}

	/**
	* @covers Spello\Assistant::html()
	*/
	function test_html()
	{
		$this->assertEquals(
			Assistant::html('ONE TWU', 'one two'),
			'one <strong>two</strong>'
			);
		$this->assertEquals(
			Assistant::html('One Two', 'one two'),
			'one two'
			);
		$this->assertEquals(
			Assistant::html('ONE TWU', 'one two', function($a) {return "*{$a}*";}),
			'one *two*'
			);
	}

	/**
	* @covers Spello\Assistant::train()
	*/
	function test_train()
	{
		$dictionary = new \Spello\Dictionary\AsArray;

		$source = new \Spello\Source\FromArray(
			include __DIR__ . '/tyger.php'
		);

		$search = new \Spello\Search\SoundEx;
		Assistant::train($source, $dictionary, $search);

		$this->assertEquals( ['tyger'], $search->suggest('tiger', $dictionary) );
		$this->assertEquals( ['the', 'thy'], $search->suggest('tha', $dictionary) );
		$this->assertEquals( ['skies', 'seize'], $search->suggest('skiez', $dictionary));

		$word_count = new \Spello\Search\WordCount;
		Assistant::train($source, $dictionary, $word_count);

		$this->assertEquals($dictionary->find('tyger', $dictionary), 2 );
		$this->assertEquals($dictionary->find('burning', $dictionary), 1 );
		$this->assertEquals($dictionary->find('bright', $dictionary), 1 );
		$this->assertEquals($dictionary->find('in', $dictionary), 2 );
		$this->assertEquals($dictionary->find('the', $dictionary), 5 );
		$this->assertEquals($dictionary->find('forests', $dictionary), 1 );
		$this->assertEquals($dictionary->find('of', $dictionary), 2 );
		$this->assertEquals($dictionary->find('night', $dictionary), 1 );
		$this->assertEquals($dictionary->find('what', $dictionary), 4 );
		$this->assertEquals($dictionary->find('immortal', $dictionary), 1 );
		$this->assertEquals($dictionary->find('hand', $dictionary), 2 );
		$this->assertEquals($dictionary->find('or', $dictionary), 2 );
		$this->assertEquals($dictionary->find('eye', $dictionary), 1 );
		$this->assertEquals($dictionary->find('could', $dictionary), 1 );
		$this->assertEquals($dictionary->find('frame', $dictionary), 1 );
		$this->assertEquals($dictionary->find('thy', $dictionary), 1 );
		$this->assertEquals($dictionary->find('fearful', $dictionary), 1 );
		$this->assertEquals($dictionary->find('symmetry', $dictionary), 1 );
		$this->assertEquals($dictionary->find('distant', $dictionary), 1 );
		$this->assertEquals($dictionary->find('deeps', $dictionary), 1 );
		$this->assertEquals($dictionary->find('skies', $dictionary), 1 );
		$this->assertEquals($dictionary->find('burnt', $dictionary), 1 );
		$this->assertEquals($dictionary->find('fire', $dictionary), 2 );
		$this->assertEquals($dictionary->find('thine', $dictionary), 1 );
		$this->assertEquals($dictionary->find('eyes', $dictionary), 1 );
		$this->assertEquals($dictionary->find('on', $dictionary), 1 );
		$this->assertEquals($dictionary->find('wings', $dictionary), 1 );
		$this->assertEquals($dictionary->find('dare', $dictionary), 2 );
		$this->assertEquals($dictionary->find('he', $dictionary), 1 );
		$this->assertEquals($dictionary->find('aspire', $dictionary), 1 );
		$this->assertEquals($dictionary->find('seize', $dictionary), 1 );
	}


	/**
	* @covers Spello\Assistant::suggest()
	*/
	function test_suggest()
	{
		$dictionary = new \Spello\Dictionary\AsArray;

		$source = new \Spello\Source\FromArray(
			include __DIR__ . '/tyger.php'
			);

		$search = new \Spello\Search\SoundEx;
		Assistant::train($source, $dictionary, $search);

		$this->assertEquals(
			'tyger fire',
			Assistant::suggest('tiger fyre', $search, $dictionary)
			);
		$this->assertEquals(
			false,
			Assistant::suggest('tyger fire', $search, $dictionary)
			);

		$this->assertEquals(
			'could wings',
			Assistant::suggest(' Cuold WINKS ', $search, $dictionary)
			);
	}
}
