<?php

namespace Stichoza\GeorgianTextGenerator;

/**
 * Generator
 *
 * Generate georgian words and sentences
 */
class Generator
{

    /**
     * Probability of certain functionalities
     *
     * Large number - less probability
     * n    - 1/n+1
     * 0    - 1
     * 1    - 1/2
     * inf    - 1/inf
     */
    private $probability = [
        'punctuation' => 5,
        'prefixes' => 4,
        'suffixes' => 6,
        'letter_skip' => 8,
    ];

    /**
     * Array of processed chars
     */
    private $preparedData = [];

    /**
     * Basic array of characters
     *
     * Large number - hight priority
     * This array is deleted at the end of construction
     */
    private $characterBase = [
        'punctuation' => [
            'middle' => [
                ',' => 8,
                '-' => 1
            ],
            'ending' => [
                '.' => 35,
                '...' => 1,
                '?' => 3,
                '!' => 3,
                '?!' => 2
            ]
        ],
        'letters' => [
            'vowels' => [
                'ა' => 6,
                'ე' => 3,
                'ი' => 4,
                'ო' => 5,
                'უ' => 1
            ],
            'consonants' => [
                'ბ' => 37,
                'გ' => 28,
                'დ' => 56,
                'ვ' => 46,
                'ზ' => 11,
                'თ' => 31,
                'კ' => 17,
                'ლ' => 47,
                'მ' => 64,
                'ნ' => 48,
                'პ' => 4,
                'ჟ' => 1,
                'რ' => 67,
                'ს' => 77,
                'ტ' => 13,
                'ფ' => 10,
                'ქ' => 9,
                'ღ' => 7,
                'ყ' => 10,
                'შ' => 18,
                'ჩ' => 5,
                'ც' => 19,
                'ძ' => 7,
                'წ' => 10,
                'ჭ' => 3,
                'ხ' => 25,
                'ჯ' => 3,
                'ჰ' => 2
            ]
        ],
        'groups' => [
            'prefixes' => [
                'მი' => 3,
                'მო' => 3,
                'მიმო' => 1,
                'და' => 4,
                'ჩა' => 4,
                'შე' => 6,
                'გა' => 4,
                'გადმო' => 1,
                'წა' => 3
            ],
            'suffixes' => [
                'მა' => 5,
                'ით' => 6,
                'ქნა' => 1,
                'ები' => 10,
                'ოვა' => 3,
                '-მეთქი' => 1,
                '-თქო' => 1
            ]
        ]
    ];

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->prepareData($this->characterBase);
    }

    /**
     * Prepare characterBase[] and push to preparedArray[]
     *
     * @param    array $array Array to iterate
     * @return    void
     *
     */
    private function prepareData(array $array)
    {
        foreach ($array as $key => $value) {
            if (!$this->hasArray($value)) {
                $this->preparedData[$key] = $this->prepareArray($value);
            } else {
                foreach ($value as $key2 => $value2) {
                    $this->preparedData[$key][$key2] = $this->prepareArray($value[$key2]);
                }
            }
        }
    }

    /**
     * Return prepared array (keys multiplied by it's priority value)
     *
     * @param    array $array Array to prepare
     * @return    array    Prepared array
     */
    private function prepareArray(array $array)
    {
        $preparedArray = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            for ($i = 0; $i < $value; $i++) {
                $preparedArray[] = $key;
            }
        }

        return $preparedArray;
    }

    /**
     * Generate random word
     *
     * @param    integer $length Word length
     * @param    boolean $pre Use prefixes
     * @param    boolean $suf Use suffixes
     * @param    boolean $strict Generate in strict mode (no length incrementing)
     * @return    string    Generated word
     */
    public function generateWord($length, $pre = true, $suf = true, $strict = false)
    {
        $word = '';
        $offset = ($strict) ? 0 : rand(0, 1);

        if (($offset && $length % 2 != 0) || (!$offset && $length % 2 == 0)) $length++;

        for ($i = 0; $i < $length; $i++) {

            $isVowel = (($i + $offset) % 2 == 0);

            if ($isVowel && $this->randomBool($this->probability['letter_skip']) && !$strict && $length > 4) {
                continue;
            }

            $letters = $isVowel
                ? $this->preparedData['letters']['vowels']
                : $this->preparedData['letters']['consonants'];

            $word .= $this->randomFrom($letters);
        }

        // Append suffix
        if ($suf && $this->randomBool($this->probability['suffixes'])) {
            $word .= $this->randomFrom($this->preparedData['groups']['suffixes']);
        }

        // Prerend suffix
        if ($pre && $this->randomBool($this->probability['prefixes'])) {
            $word = $this->randomFrom($this->preparedData['groups']['prefixes']) . $word;
        }

        return $word;
    }

    /**
     * Generate sentence, string containig random words
     *
     * @param    integer $n Number of words in sentence
     * @param    boolean $pre Use prefixes
     * @param    boolean $suf Use suffixes
     * @return    string    Generated sentence
     */
    public function generateSentence($n, $pre = true, $suf = true)
    {
        $sentence = '';

        for ($i = 0; $i < $n; $i++) {

            $sentence .= $this->generateWord(rand(3, 8), $pre, $suf);

            if ($i == $n - 1) {
                $sentence .= $this->randomFrom($this->preparedData['punctuation']['ending']);
                break;
            } else if ($this->randomBool($this->probability['punctuation'])) {
                $sentence .= $this->randomFrom($this->preparedData['punctuation']['middle']);
            }

            $sentence .= ' ';
        }

        return $sentence;
    }

    /**
     * Check if array is multidimensional
     *
     * @param array $array Array to chech for sub-arrays
     * @return boolean If the array is multidimensional
     */
    private function hasArray(array $array)
    {
        foreach ($array as $item) {
            if (is_array($item)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get random boolean based on probability
     *
     * @param $probability
     * @return bool
     */
    private function randomBool($probability)
    {
        return !rand(0, $probability);
    }

    /**
     * Get random item from array
     *
     * @param array $array
     * @return mixed
     */
    private function randomFrom(array $array)
    {
        return $array[array_rand($array)];
    }
}
