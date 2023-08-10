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

    public function testGenDiffJsonJson(): void
    {
        $expected = file_get_contents($this->getFixtureFullPath('nested.txt'));

        $this->assertEquals($expected, genDiff(
            $this->getFixtureFullPath('file1.json'),
            $this->getFixtureFullPath('file2.json')
        ));
    }

    public function testGenDiffYmlYml(): void
    {
        $expected = file_get_contents($this->getFixtureFullPath('nested.txt'));

        $this->assertEquals($expected, genDiff(
            $this->getFixtureFullPath('file1.yml'),
            $this->getFixtureFullPath('file2.yml')
        ));
    }
}
