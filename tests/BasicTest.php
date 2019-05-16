<?php

namespace Stichoza\GeorgianTextGenerator\Tests;

use PHPUnit\Framework\TestCase;
use Stichoza\GeorgianTextGenerator\Generator;

class BasicTest extends TestCase
{
    /**
     * @var \Stichoza\GeorgianTextGenerator\Generator
     */
    public $generator;

    public function setUp()
    {
        $this->generator = new Generator();
    }

    public function testWordLength()
    {
        $count = 5;

        $word = $this->generator->generateWord($count);

        $this->assertEquals($count, mb_strlen($word));
    }

    public function testSentenceLength()
    {
        $count = 5;

        $word = $this->generator->generateSentence($count);

        $this->assertEquals($count, count(explode(' ', $word)));
    }

}