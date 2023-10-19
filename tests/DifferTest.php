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

    /**
    * @dataProvider provideFormatData
    */
    public function testGenDiffStylish($format): void
    {
        $this->assertStringEqualsFile(
            $this->getFixtureFullPath('expectedStylish.txt'),
            genDiff(
                $this->getFixtureFullPath("file1.$format"),
                $this->getFixtureFullPath("file2.$format"),
                'stylish'
            )
        );
    }

    /**
    * @dataProvider provideFormatData
    */
    public function testGenDiffPlain($format): void
    {
        $this->assertStringEqualsFile(
            $this->getFixtureFullPath('expectedPlain.txt'),
            genDiff(
                $this->getFixtureFullPath("file1.$format"),
                $this->getFixtureFullPath("file2.$format"),
                'plain'
            )
        );
    }

    /**
    * @dataProvider provideFormatData
    */
    public function testGenDiffJson($format): void
    {
        $this->assertStringEqualsFile(
            $this->getFixtureFullPath('expectedJson.txt'),
            genDiff(
                $this->getFixtureFullPath("file1.$format"),
                $this->getFixtureFullPath("file2.$format"),
                'json'
            )
        );
    }

    public static function provideFormatData(): array
    {
        return [
            ['json'],
            ['yml']
        ];
    }
}
