<?php

namespace Spello;

class Assistant
{
	/**
	* Extracts words from the $text
	*
	* @param string $text
	* @return array
	*/
	static function extract($text)
	{
		$text = filter_var($text, FILTER_SANITIZE_STRING);

		if (preg_match_all('~[a-z0-9\-]+~', $text = strtolower($text), $R))
		{
			return $R[0];
		}

		if ($text = trim($text))
		{
			return array($text);
		}

		return array();
	}

	/**
	* Formats $improved text to highlight the changes
	*
	* This method is used when you need to present the suggested changes,
	* e.g. "Do you mean: <strong>champing</strong> at the bit"
	*
	* @param string $text
	* @param string $improved
	* @param callable $format
	* @return array
	*/
	static function html($text, $improved, callable $format = null)
	{
		$words = static::extract($text);
		$c = static::extract($improved);

		if (!$format)
		{
			$format = function($word)
			{
				return "<strong>{$word}</strong>";
			};
		}

		$html = '';
		foreach($words as $i => $word)
		{
			if ($html)
			{
				$html .= ' ';
			}

			if($word != $c[$i])
			{
				$html .= call_user_func($format, $c[$i]);
			} else
			{
				$html .= $word;
			}
		}

		return $html;
	}

	/**
	* Fills in a $dictionary from the $source using the provided $search
	*
	* This method is used to "train" the $dictionary with the words from
	* the $source using the specific $search. Later that same $dictionary
	* can be used by {@link \Spello\Assistant::suggest()} in combination
	* with the same $search.
	*
	* @param \Spello\Source\SourceInterface $source
	* @param \Spello\Dictionary\DictionaryInterface $dictionary
	* @param \Spello\Search\SearchInterface $search
	*/
	static function train(
		Source\SourceInterface $source,
		Dictionary\DictionaryInterface $dictionary,
		Search\SearchInterface $search)
	{
		$source->reset();
		$dictionary->truncate();

		while (false !== ($text = $source->fetch()))
		{

			if (!$words = static::extract($text))
			{
				continue;
			}

			foreach ($words as $word)
			{
				if (!$word)
				{
					continue;
				}

				$search->prepare($word, $dictionary);
			}
		}
	}

	/**
	* Returns improved $text with sugestions from the $dictionary using $search
	*
	* @param string $text
	* @param \Spello\Search\SearchInterface $search
	* @param \Spello\Dictionary\DictionaryInterface $dictionary
	* @param \Spello\Dictionary\DictionaryInterface $word_count optional word count dictionary 
	* @return false|string either FALSE if there are no improvements
	*	suggested, or a string with the suggestions applied
	*/
	static function suggest($text,
		Search\SearchInterface $search,
		Dictionary\DictionaryInterface $dictionary,
		Dictionary\DictionaryInterface $word_count = null)
	{
		$words = static::extract( $text );

		$suggestions = array();
		foreach ($words as $i => $word)
		{
			$improved = $search->suggest($word, $dictionary);
			$suggestions[ $i ] = $improved;
		}

		if (!empty($word_count))
		{
			foreach($suggestions as $i => $improved)
			{
				if (!$improved)
				{
					continue;
				}

				$suggestions[ $i ] = Search\WordCount::sort(
					$improved,
					$word_count
					);
			}
		}

		$result = array();
		foreach($suggestions as $i => $improved)
		{
			$result[] = !empty($improved[0])
				? $improved[0]
				: $words[ $i ];
		}

		return ($result == $words)
			? false
			: join(' ', $result);
	}
}
