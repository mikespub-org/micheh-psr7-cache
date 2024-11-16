<?php
/**
 * PSR-7 Cache Helpers
 *
 * @copyright Copyright (c) 2016, Michel Hunziker <php@michelhunziker.com>
 * @license http://www.opensource.org/licenses/BSD-3-Clause The BSD-3-Clause License
 */

namespace MichehTest\Cache\Header;

#[\PHPUnit\Framework\Attributes\CoversClass(\Micheh\Cache\Header\CacheControl::class)]
class CacheControlTest extends CacheControlTestCase
{
    /**
     * @var CacheControlStub
     */
    protected $cacheControl;

    /**
     * @var string
     */
    protected $controlClass = \MichehTest\Cache\Header\CacheControlStub::class;

    public function testWithFlag()
    {
        $clone = $this->cacheControl->withDirective('foo', true);
        $this->assertInstanceOf($this->controlClass, $clone);

        $this->assertSameDirectives([], $this->cacheControl);
        $this->assertSameDirectives(['foo' => true], $clone);
    }

    public function testWithFlagAndFalse()
    {
        $clone = $this->cacheControl->withDirective('foo', false);
        $this->assertSameDirectives([], $clone);
    }

    public function testWithFlagRemovesFlag()
    {
        $clone = $this->cacheControl->withDirective('foo', true)->withDirective('foo', false);
        $this->assertSameDirectives([], $clone);
    }

    public function testHasFlag()
    {
        $clone = $this->cacheControl->withDirective('foo', true);
        $this->assertTrue($clone->hasDirective('foo'));
    }

    public function testHasFlagWithoutValue()
    {
        $this->assertFalse($this->cacheControl->hasDirective('foo'));
    }

    public function testWithDirective()
    {
        $clone = $this->cacheControl->withDirective('foo', 'bar');
        $this->assertInstanceOf($this->controlClass, $clone);

        $this->assertSameDirectives([], $this->cacheControl);
        $this->assertSameDirectives(['foo' => 'bar'], $clone);
    }

    public function testWithDirectiveWithNegativeInt()
    {
        $clone = $this->cacheControl->withDirective('foo', -200);
        $this->assertSameDirectives(['foo' => 0], $clone);
    }

    public function testWithDirectiveWithNull()
    {
        $clone = $this->cacheControl->withDirective('foo', 'bar')->withDirective('foo', null);
        $this->assertSameDirectives([], $clone);
    }

    public function testGetDirective()
    {
        $clone = $this->cacheControl->withDirective('foo', 'bar');
        $this->assertSame('bar', $clone->getDirective('foo'));
    }

    public function testGetDirectiveWithoutValue()
    {
        $this->assertNull($this->cacheControl->getDirective('foo'));
    }

    public function testFromStringWithFlag()
    {
        $control = CacheControlStub::createFromString('no-transform');
        $this->assertSameDirectives(['no-transform' => true], $control);
    }

    public function testFromStringWithToken()
    {
        $control = CacheControlStub::createFromString('max-age=60');
        $this->assertSameDirectives(['max-age' => 60], $control);
    }

    public function testFromStringWithMultiple()
    {
        $control = CacheControlStub::createFromString('no-transform, max-age=100');
        $this->assertSameDirectives(['no-transform' => true, 'max-age' => 100], $control);
    }

    public function testFromStringWithOverrideMethod()
    {
        $this->assertSame('123', CacheControlStub::createFromString('custom=123'));
    }

    public function testFromStringWithUnknownDirective()
    {
        $control = CacheControlStub::createFromString('foo="bar"');
        $this->assertSameDirectives(['foo' => 'bar'], $control);
    }

    public function testFromStringWithUnknownDirectiveFlag()
    {
        $control = CacheControlStub::createFromString('foo');
        $this->assertSameDirectives([], $control);
    }

    public function testWithMaxAge()
    {
        $control = $this->getControlWithDirective('max-age', 5);
        $this->assertReturn($control->withMaxAge(5));
    }

    public function testGetMaxAge()
    {
        $control = $this->getControlWithGetDirective('max-age');
        $this->assertReturn($control->getMaxAge());
    }

    public function testWithNoCache()
    {
        $control = $this->getControlWithDirective('no-cache', true);
        $this->assertReturn($control->withNoCache(true));
    }

    public function testHasNoCache()
    {
        $control = $this->getControlWithHasFlag('no-cache');
        $this->assertReturn($control->hasNoCache());
    }

    public function testWithNoStore()
    {
        $control = $this->getControlWithDirective('no-store', true);
        $this->assertReturn($control->withNoStore(true));
    }

    public function testHasNoStore()
    {
        $control = $this->getControlWithHasFlag('no-store');
        $this->assertReturn($control->hasNoStore());
    }

    public function testWithNoTransform()
    {
        $control = $this->getControlWithDirective('no-transform', true);
        $this->assertReturn($control->withNoTransform(true));
    }

    public function testHasNoTransform()
    {
        $control = $this->getControlWithHasFlag('no-transform');
        $this->assertReturn($control->hasNoTransform());
    }

    public function testWithExtension()
    {
        $control = $this->getControlWithDirective('foo', 'bar');
        $this->assertReturn($control->withExtension('foo', '"bar"'));
    }

    public function testWithExtensionInvalidType()
    {
        $this->expectExceptionMessage(
            //'InvalidArgumentException',
            'Name and value of the extension have to be a string.'
        );
        /** @phpstan-ignore-next-line */
        $this->cacheControl->withExtension('foo', true);
    }

    public function testGetExtension()
    {
        $control = $this->getControlWithGetDirective('foo');
        $this->assertReturn($control->getExtension('foo'));
    }

    public function testToStringWithFlag()
    {
        $clone = $this->cacheControl->withDirective('foo', true);
        $this->assertSame('foo', (string) $clone);
    }

    public function testToStringWithToken()
    {
        $clone = $this->cacheControl->withDirective('foo', 30);
        $this->assertSame('foo=30', (string) $clone);
    }

    public function testToStringWithExtension()
    {
        $clone = $this->cacheControl->withDirective('foo', 'bar');
        $this->assertSame('foo="bar"', (string) $clone);
    }

    public function testToStringWithMultiple()
    {
        $clone = $this->cacheControl->withDirective('public', true)->withDirective('foo', 20);
        $this->assertSame('public, foo=20', (string) $clone);
    }

    public function testToStringWithEmpty()
    {
        $this->assertSame('', (string) $this->cacheControl);
    }
}
