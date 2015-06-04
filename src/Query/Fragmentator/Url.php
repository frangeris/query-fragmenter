<?php namespace Query\Fragmentator;

class Url
{
	/**
	 * Set the url to analize
	 *
	 * @param string $url Url to analize
	 * @return Query\Fragmentator\Url Instance
	 */
	public static function load($url)
	{
		self::$url = parse_url($url);

		if (null == self::$instance)
			self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Fragment an url for comparison operators
	 *
	 * @return array
	 */
	public function getComparisons()
	{
		$fragments = [];
		$query = parse_url($this->url, PHP_URL_QUERY);
		$params = explode('&', $query);

		// Filter the fragments
		foreach($params as $param)
			$fragments[] = preg_split("#([<>!]?=)#i", $param, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		return $fragments;
	}
}