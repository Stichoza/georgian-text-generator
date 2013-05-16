<?php

/*
 * @author	Levan Velijanashvili <stichoza@gmail.com>
 * @version	2.0.0
 *
 */

class WordGenerator {

	private $preparedData = array();
	private $characterBase = array(
		"punctuation" => array(
			"middle" => array(
				"," =>	5,
				" -" =>	1
			),
			"ending" => array(
				"." =>	20,
				"..." =>2,
				"?" =>	1,
				"!" =>	1,
				"?!" =>	1
			)
		),
		"letters" => array(
			"vowels" => array(
				"ა" =>	1,
				"ე" =>	1,
				"ი" =>	1,
				"ო" =>	1,
				"უ" =>	1
			),
			"consonants" => array(
				"ბ" =>	1,		"გ" =>	1,		"დ" =>	1,
				"ვ" =>	1,		"ზ" =>	1,		"თ" =>	1,
				"კ" =>	1,		"ლ" =>	1,		"მ" =>	1,
				"ნ" =>	1,		"პ" =>	1,		"ჟ" =>	1,
				"რ" =>	1,		"ს" =>	1,		"ტ" =>	1,
				"ფ" =>	1,		"ქ" =>	1,		"ღ" =>	1,
				"ყ" =>	1,		"შ" =>	1,		"ჩ" =>	1,
				"ც" =>	1,		"ძ" =>	1,		"წ" =>	1,
				"ჭ" =>	1,		"ხ" =>	1,		"ჯ" =>	1,
				"ჰ" =>	1
			)
		),
		"groups" => array(
			"prefixes" => array(
				"მი" =>		1,
				"მო" =>		1,
				"მიმო" =>	1,
				"და" =>		1,
				"ჩა" =>		1,
				"შე" =>		1,
				"გა" =>		1,
				"გადმო" =>	1,
				"წა" =>		1
			),
			"suffixes" => array(
				"მა" =>		1,
				"ით" =>		1,
				"ქნა" =>		1,
				"ები" =>		1,
				"ოვა" =>		1,
				"-მეთქი" =>	1,
				"-თქო" =>	1
			)
		)
	);

	/*
	 * @param int
	 *
	 */
	public function __construct() {
		try {
			$this->prepareData($this->characterBase);
		} catch (Exception $e) {
			echo "Caught Exception: " . $e.getMessage();
		}
	}

	/*
	 * @param array
	 * @return void
	 *
	 */
	private function prepareData($array) {
		foreach ($array as $key => $value) {
			if (is_array($value) && !WordGenerator::has_array($value)) {
				try {
					$this->preparedData[$key] = $this->prepareArray($value);
				} catch (Exception $e) {
					echo "Exception in first loop: " . $e.getMessage();
				}
			} elseif (is_array($value)) {
				foreach ($value as $key2 => $value2) {
					try {
						$this->preparedData[$key][$key2] = $this->prepareArray($value[$key2]);
					} catch (Exception $e) {
						echo "Exception in inner loop: " . $e.getMessage();
					}
				}
			} else {
				throw new Exception('Expected array.');
			}
		}
	}

	/*
	 * @param array
	 * @return array
	 *
	 */
	private function prepareArray($array) {
		if (!is_array($array)) {
			throw new Exception('Expected array.');
			return null;
		}
		$preparedArray = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				throw new Exception('Array contains sub-array.');
				continue;
			}
			for ($i=0; $i < $value; $i++) {
				$preparedArray[] = $key;
			}
		}
		return $preparedArray;
	}

	/*
	 * @param int
	 * @param boolean
	 * @return string
	 *
	 */
	public function generateWord($length, $pre = true, $suf = true, $strict = false) {
		$word = "";
		$offset = ($strict) ? 0 : rand(0, 1);
		if (($offset && $length%2!=0) || (!$offset && $length%2==0)) $length++;
		for ($i=0; $i<$length; $i++) {
			$letters = (($i+$offset)%2 == 0) ? $this->preparedData["letters"]["vowels"] : $this->preparedData["letters"]["consonants"];
			$word .= $letters[rand(0, count($letters)-1)];
		}
		if (!rand(0, 4) && $suf) $word .= $this->preparedData["groups"]["suffixes"][rand(0, count($this->preparedData["groups"]["suffixes"])-1)];
		if (!rand(0, 4) && $pre) $word = $this->preparedData["groups"]["prefixes"][rand(0, count($this->preparedData["groups"]["prefixes"])-1)] . $word;
		return $word;
	}

	/*
	 * @param int
	 * @return string
	 *
	 */
	public function generateSentence($n, $pre, $suf) {
		$sentence = "";
		for ($i=0; $i<$n; $i++) {
			$sentence .= $this->generateWord(rand(3, 8), $pre, $suf);
			if ($i == $n-1) {
				$sentence .= $this->preparedData["punctuation"]["ending"][rand(0, count($this->preparedData["punctuation"]["ending"])-1)];
				break;
			} elseif (!rand(0,4)) {
				$sentence .= $this->preparedData["punctuation"]["middle"][rand(0, count($this->preparedData["punctuation"]["middle"])-1)];
			}
			$sentence .= " ";
		}
		return $sentence;
	}

	/*
	 * @param array
	 * @return boolean
	 *
	 */
	public static function has_array($a) {
		foreach ($a as $v) {
			if (is_array($v)) return true;
		}
		return false;
	}

}
?>
