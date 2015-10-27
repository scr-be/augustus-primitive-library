<?php

namespace Scribe\Primitive\Map;

use Scribe\Primitive\Collection\AbstractCollection;

/**
 * Class Map.
 */
class Map extends AbstractCollection implements MapInterface
{
	/**
	 * @param array $elements
	 */
	public function __construct(array $elements = [])
	{
		$this->elements = $elements;
	}
}

/* EOF */
