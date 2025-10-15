<?php

namespace PolosHermanoz\YoutubeStudio\Tests;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\Example;

class ExampleTest extends TestCase
{
    /**
     * Test the greet method from the Example class.
     */
    public function test_greet_method_returns_correct_string(): void
    {
        $example = new Example();
        $greeting = $example->greet();

        $this->assertSame("Hello from the Example class!", $greeting);
    }
}