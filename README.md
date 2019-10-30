# Spello

Simple basic spelling suggestions, a.k.a. "Did you mean?"

## How it works ?

## What is inside ?

### Search

### Dictionary

### Source

## Advanced use

### Caching

### Exclude short words
When training a dictionary, you can use the `\Spello\Source\ShortWords` class as
proxy source and filter out all the words which length is too small. To do that
you need to "nest" the real source into `\Spello\Source\ShortWords`. Here is an
example of how to use to skip all words which length is less than 3 characters:
```php
$source = new \Spello\Source\FromArray(['I am a teapot', 'What do you mean ?']);
$short_words = new \Spello\Source\ShortWords($source, 3);
while ($text = $short_words->fetch())
{
	echo $text, "\n";
}
```
This is what the result looks like:
```
teapot
what you mean
```

### Exclude "stop" words


### Non-english use

## Extending Spello

For most of your projects you are probably going to need to create new **source** classes and new **dictionary** classes. You can also create new **search** classes, but that would be a rare occasion.

### New Dictionary Classes

As previously mentioned, **dictionary** classes are used to store the words used by ``Spello``. There are several such classes that are available out of the box and they are ok to be used with smaller word sets. For larger word volume it is best to implement your own dictionary that stores the data in a database or some other external storage service such as Memcache or Redis.

All dictionaries must implement the `\Spello\Dictionary\DictionaryInterface` interface. One tricky part is that the values stored in a dictionary can be both scalars and arrays, so  you need to account for that in your implementation.

Here is an example of creating your own **Memcached**-based dictionary.
```php
use \Spello\Dictionary\DictionaryInterface;
class Memcached_Dictionary implements DictionaryInterface
{
	protected $memcached;

	function __construct(\Memcached $memcached)
	{
		$this->memcached = $memcached;
	}

	function set($key, $value)
	{
		return $this->memcached->set($key, $value);
	}

	function find($key)
	{
		return $this->memcached->get($key);
	}

	function truncate()
	{
		return $this->memcached->flush();
	}

	function getIterator()
	{
		// It is a *BAD* idea to dump all the words into
		// an array and eat up all that memory; it is OK
		// for an example though ;-)
		//
		// If you really want to do something like this,
		// please create an \Iterator-based class that
		// will fetch the words one at a time from Memcached
		//
		$keys = $this->memcached->getAllKeys();
		$this->memcached->getDelayed($keys);
		$words = $this->memcached->fetchAll();

		return new \ArrayIterator($words);
	}
}
```
