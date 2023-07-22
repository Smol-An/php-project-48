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

    public function testGenDiff(): void
    {
        $expected1 = '{

}
';
        $this->assertEquals($expected1, genDiff(
            $this->getFixtureFullPath('jsonEmpty1.json'),
            $this->getFixtureFullPath('jsonEmpty2.json')
        ));

        $expected2 = '{
   follow: false
   proxy: 123.234.53.22
   timeout: 50
}
';
        $this->assertEquals($expected2, genDiff(
            $this->getFixtureFullPath('jsonSame1.json'),
            $this->getFixtureFullPath('jsonSame2.json')
        ));

        $expected3 = '{
 - follow: false
 - host: hexlet.io
 - proxy: 123.234.53.22
 + timeout: 20
 + verbose: true
}
';
        $this->assertEquals($expected3, genDiff(
            $this->getFixtureFullPath('jsonDifferent1.json'),
            $this->getFixtureFullPath('jsonDifferent2.json')
        ));
    }
}
