<?php
/**
 * PSR-7 Cache Helpers
 *
 * @copyright Copyright (c) 2016, Michel Hunziker <php@michelhunziker.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD-3-Clause License
 */

namespace MichehTest\Cache\Header;

use Micheh\Cache\Header\RequestCacheControl;

#[\PHPUnit\Framework\Attributes\CoversClass(\Micheh\Cache\Header\RequestCacheControl::class)]
class RequestCacheControlTest extends CacheControlTestCase
{
    /**
     * @var string
     */
    protected $controlClass = \Micheh\Cache\Header\RequestCacheControl::class;

    public function testFromString()
    {
        $control = RequestCacheControl::fromString('max-age=100');
        $this->assertInstanceOf($this->controlClass, $control);
    }

    public function testWithMaxStale()
    {
        $control = $this->getControlWithDirective('max-stale', 10);
        $this->assertReturn($control->withMaxStale(10));
    }

    public function testGetMaxStale()
    {
        $control = $this->getControlWithGetDirective('max-stale');
        $this->assertReturn($control->getMaxStale());
    }

    public function testWithMinFresh()
    {
        $control = $this->getControlWithDirective('min-fresh', 10);
        $this->assertReturn($control->withMinFresh(10));
    }

    public function testGetMinFresh()
    {
        $control = $this->getControlWithGetDirective('min-fresh');
        $this->assertReturn($control->getMinFresh());
    }

    public function testWithOnlyIfCached()
    {
        $control = $this->getControlWithDirective('only-if-cached', true);
        $this->assertReturn($control->withOnlyIfCached(true));
    }

    public function testHasOnlyIfCached()
    {
        $control = $this->getControlWithHasFlag('only-if-cached');
        $this->assertReturn($control->hasOnlyIfCached());
    }
}
