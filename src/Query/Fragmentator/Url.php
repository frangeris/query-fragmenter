<?php namespace Query\Fragmentator;

class Url
{
	/**
	 * Instance of the class
	 *
	 * @var string
	 */
	private static $instance;

	/**
	 * Url to analize
	 *
	 * @var string
	 */
	protected static $url;

	/**
	 * Set the url to analize
	 *
	 * @param string $url Url to analize
	 * @return Query\Fragmentator\Url Instance
	 */
	public static function load($url)
	{
		self::$url = $url;

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
		$query = parse_url(self::$url, PHP_URL_QUERY);
		$params = explode('&', $query);

		// Filter the fragments
		foreach($params as $param)
			$fragments[] = preg_split("#([<>!]?=)#i", urldecode($param), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		return $fragments;
	}

	/**
	 * Fragment sort param for get order by
	 *
	 * @return array
	 */
	public function getSorters()
	{
		$fragments = [];
		$query = parse_url(self::$url, PHP_URL_QUERY);
		parse_str($query, $params);

		if (isset($params['sort']))
		{
			$vars = explode(',', $params['sort']);
			foreach ($vars as $var)
			{
				$symbol = $var[0];
				$field = preg_replace("/[^a-z0-9]/", null, $var);
				$fragments[$field] = ('+' === $symbol)?'ASC' :'DESC';
			}

			return $fragments;
		}

		return false;
	}
}