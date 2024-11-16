<?php
/**
 * PSR-7 Cache Helpers
 *
 * @copyright Copyright (c) 2016, Michel Hunziker <php@michelhunziker.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD-3-Clause License
 */

namespace MichehTest\Cache\Header;

use Micheh\Cache\Header\ResponseCacheControl;

#[\PHPUnit\Framework\Attributes\CoversClass(\Micheh\Cache\Header\ResponseCacheControl::class)]
class ResponseCacheControlTest extends CacheControlTestCase
{
    /**
     * @var ResponseCacheControl
     */
    protected $cacheControl;

    /**
     * @var string
     */
    protected $controlClass = \Micheh\Cache\Header\ResponseCacheControl::class;

    public function testFromString()
    {
        $control = ResponseCacheControl::fromString('max-age=100');
        $this->assertInstanceOf($this->controlClass, $control);
    }

    public function testWithPublic()
    {
        $clone = $this->cacheControl->withPublic();
        $this->assertSameDirectives(['public' => true], $clone);
    }

    public function testWithPrivate()
    {
        $clone = $this->cacheControl->withPrivate();
        $this->assertSameDirectives(['private' => true], $clone);
    }

    public function testWithPublicOverridesPrivate()
    {
        $clone = $this->cacheControl->withPrivate()->withPublic();
        $this->assertSameDirectives(['public' => true], $clone);
    }

    public function testWithPrivateOverridesPublic()
    {
        $clone = $this->cacheControl->withPublic()->withPrivate();
        $this->assertSameDirectives(['private' => true], $clone);
    }

    public function testWithPublicDoesNotOverwriteFalse()
    {
        $clone = $this->cacheControl->withPrivate()->withPublic(false);
        $this->assertSameDirectives(['private' => true], $clone);
    }

    public function testIsPublic()
    {
        $control = $this->getControlWithHasFlag('public');
        $this->assertReturn($control->isPublic());
    }

    public function testIsPrivate()
    {
        $control = $this->getControlWithHasFlag('private');
        $this->assertReturn($control->isPrivate());
    }

    public function testWithSharedMaxAge()
    {
        $control = $this->getControlWithDirective('s-maxage', 10);
        $this->assertReturn($control->withSharedMaxAge(10));
    }

    public function testGetSharedMaxAge()
    {
        $control = $this->getControlWithGetDirective('s-maxage');
        $this->assertReturn($control->getSharedMaxAge());
    }

    public function testGetLifetimeWithNormal()
    {
        $control = $this->cacheControl->withMaxAge(20);
        $this->assertSame(20, $control->getLifetime());
    }

    public function testGetLifetimeWithShared()
    {
        $control = $this->cacheControl->withSharedMaxAge(60);
        $this->assertSame(60, $control->getLifetime());
    }

    public function testGetLifetimeWithBoth()
    {
        $control = $this->cacheControl->withSharedMaxAge(60)->withMaxAge(20);
        $this->assertSame(60, $control->getLifetime());
    }

    public function testGetLifetimeWithoutDirective()
    {
        $this->assertNull($this->cacheControl->getLifetime());
    }

    public function testWithStaleWhileRevalidate()
    {
        $control = $this->getControlWithDirective('stale-while-revalidate', 10);
        $this->assertReturn($control->withStaleWhileRevalidate(10));
    }

    public function testGetStaleWhileRevalidate()
    {
        $control = $this->getControlWithGetDirective('stale-while-revalidate');
        $this->assertReturn($control->getStaleWhileRevalidate());
    }

    public function testWithStaleIfError()
    {
        $control = $this->getControlWithDirective('stale-if-error', 10);
        $this->assertReturn($control->withStaleIfError(10));
    }

    public function testGetStaleIfError()
    {
        $control = $this->getControlWithGetDirective('stale-if-error');
        $this->assertReturn($control->getStaleIfError());
    }

    public function testWithMustRevalidate()
    {
        $control = $this->getControlWithDirective('must-revalidate', true);
        $this->assertReturn($control->withMustRevalidate(true));
    }

    public function testHasMustRevalidate()
    {
        $control = $this->getControlWithHasFlag('must-revalidate');
        $this->assertReturn($control->hasMustRevalidate());
    }

    public function testWithProxyRevalidate()
    {
        $control = $this->getControlWithDirective('proxy-revalidate', true);
        $this->assertReturn($control->withProxyRevalidate(true));
    }

    public function testHasProxyRevalidate()
    {
        $control = $this->getControlWithHasFlag('proxy-revalidate');
        $this->assertReturn($control->hasProxyRevalidate());
    }

    public function testWithCachePrevention()
    {
        $control = $this->cacheControl->withCachePrevention();
        $directives = ['no-cache' => true, 'no-store' => true, 'must-revalidate' => true];

        $this->assertSameDirectives($directives, $control);
    }
}
