<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function testGenDiffStylish(): void
    {
        $expected = trim(file_get_contents($this->getFixtureFullPath('expectedStylish.txt')));

        $this->assertEquals($expected, genDiff(
            $this->getFixtureFullPath('file1.json'),
            $this->getFixtureFullPath('file2.yml'),
            'stylish'
        ));
    }

    public function testGenDiffPlain(): void
    {
        $expected = trim(file_get_contents($this->getFixtureFullPath('expectedPlain.txt')));

        $this->assertEquals($expected, genDiff(
            $this->getFixtureFullPath('file1.json'),
            $this->getFixtureFullPath('file2.yml'),
            'plain'
        ));
    }

    public function testGenDiffJson(): void
    {
        $expected = trim(file_get_contents($this->getFixtureFullPath('expectedJson.txt')));

        $this->assertEquals($expected, genDiff(
            $this->getFixtureFullPath('file1.json'),
            $this->getFixtureFullPath('file2.yml'),
            'json'
        ));
    }
}
