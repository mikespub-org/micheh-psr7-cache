<?php
/**
 * PSR-7 Cache Helpers
 *
 * @copyright Copyright (c) 2016, Michel Hunziker <php@michelhunziker.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD-3-Clause License
 */

namespace MichehTest\Cache\Header;

use Micheh\Cache\Header\CacheControl;
use Micheh\Cache\Header\RequestCacheControl;
use Micheh\Cache\Header\ResponseCacheControl;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CacheControlTestCase extends TestCase
{
    /**
     * @var CacheControl
     */
    protected $cacheControl;

    /**
     * @var string
     */
    protected $controlClass = 'Micheh\Cache\Header\CacheControl';

    protected function setUp(): void
    {
        $this->cacheControl = new $this->controlClass();
    }

    /**
     * @param string|int|bool|null $value
     */
    protected function assertReturn($value)
    {
        $this->assertEquals('phpunit', $value, 'Method did not return the value');
    }

    /**
     * @param array<string, mixed> $directives
     * @param MockObject|CacheControl $control
     */
    protected function assertSameDirectives($directives, $control)
    {
        if (empty($directives)) {
            $this->assertSame('', (string) $control);
            return;
        }
        foreach ($directives as $name => $value) {
            $this->assertSame($value, $control->getExtension($name));
        }
    }

    /**
     * @param string $name
     * @param string|int|bool|null $value
     * @return MockObject|CacheControl|RequestCacheControl|ResponseCacheControl
     */
    protected function getControlWithDirective($name, $value)
    {
        $control = $this->getMockBuilder($this->controlClass)->onlyMethods(['withDirective'])->getMock();
        $control->expects($this->once())->method('withDirective')
            ->with($name, $value)->willReturn('phpunit');

        return $control;
    }

    /**
     * @param string $name
     * @return MockObject|CacheControl|RequestCacheControl|ResponseCacheControl
     */
    protected function getControlWithGetDirective($name)
    {
        $control = $this->getMockBuilder($this->controlClass)->onlyMethods(['getDirective'])->getMock();
        $control->expects($this->once())->method('getDirective')
            ->with($name)->willReturn('phpunit');

        return $control;
    }

    /**
     * @param string $name
     * @return MockObject|CacheControl|RequestCacheControl|ResponseCacheControl
     */
    protected function getControlWithHasFlag($name)
    {
        $control = $this->getMockBuilder($this->controlClass)->onlyMethods(['hasDirective'])->getMock();
        $control->expects($this->once())->method('hasDirective')
            ->with($name)->willReturn('phpunit');

        return $control;
    }
}
