<?php

namespace Tests\Unit\Common\DTO;

use App\Common\DTO\Os;
use PHPUnit\Framework\TestCase;

class OsTest extends TestCase
{
    public function testGetOsX(): void
    {
        $os = Os::getOsX('10.15.7');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::OS_X, $os->getName());
        $this->assertEquals('10.15.7', $os->getVersion());
    }

    public function testGetOsXWithoutVersion(): void
    {
        $os = Os::getOsX();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::OS_X, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetIos(): void
    {
        $os = Os::getIos('14.0');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::IOS, $os->getName());
        $this->assertEquals('14.0', $os->getVersion());
    }

    public function testGetIosWithoutVersion(): void
    {
        $os = Os::getIos();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::IOS, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetSymbOS(): void
    {
        $os = Os::getSymbOS('9.1');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::SYMBOS, $os->getName());
        $this->assertEquals('9.1', $os->getVersion());
    }

    public function testGetSymbOSWithoutVersion(): void
    {
        $os = Os::getSymbOS();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::SYMBOS, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetWindows(): void
    {
        $os = Os::getWindows('10');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::WINDOWS, $os->getName());
        $this->assertEquals('10', $os->getVersion());
    }

    public function testGetWindowsWithoutVersion(): void
    {
        $os = Os::getWindows();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::WINDOWS, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetAndroid(): void
    {
        $os = Os::getAndroid('11.0');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::ANDROID, $os->getName());
        $this->assertEquals('11.0', $os->getVersion());
    }

    public function testGetAndroidWithoutVersion(): void
    {
        $os = Os::getAndroid();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::ANDROID, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetLinux(): void
    {
        $os = Os::getLinux();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::LINUX, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetNokia(): void
    {
        $os = Os::getNokia('7.1');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::NOKIA, $os->getName());
        $this->assertEquals('7.1', $os->getVersion());
    }

    public function testGetNokiaWithoutVersion(): void
    {
        $os = Os::getNokia();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::NOKIA, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetBlackBerry(): void
    {
        $os = Os::getBlackBerry('10.3.3');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::BLACKBERRY, $os->getName());
        $this->assertEquals('10.3.3', $os->getVersion());
    }

    public function testGetBlackBerryWithoutVersion(): void
    {
        $os = Os::getBlackBerry();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::BLACKBERRY, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetFreeBSD(): void
    {
        $os = Os::getFreeBSD('12.2');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::FREEBSD, $os->getName());
        $this->assertEquals('12.2', $os->getVersion());
    }

    public function testGetFreeBSDWithoutVersion(): void
    {
        $os = Os::getFreeBSD();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::FREEBSD, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetOpenBSD(): void
    {
        $os = Os::getOpenBSD('6.8');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::OPENBSD, $os->getName());
        $this->assertEquals('6.8', $os->getVersion());
    }

    public function testGetOpenBSDWithoutVersion(): void
    {
        $os = Os::getOpenBSD();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::OPENBSD, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetNetBSD(): void
    {
        $os = Os::getNetBSD('9.2');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::NETBSD, $os->getName());
        $this->assertEquals('9.2', $os->getVersion());
    }

    public function testGetNetBSDWithoutVersion(): void
    {
        $os = Os::getNetBSD();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::NETBSD, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetOpenSolaris(): void
    {
        $os = Os::getOpenSolaris('11.4');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::OPENSOLARIS, $os->getName());
        $this->assertEquals('11.4', $os->getVersion());
    }

    public function testGetOpenSolarisWithoutVersion(): void
    {
        $os = Os::getOpenSolaris();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::OPENSOLARIS, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetSunOS(): void
    {
        $os = Os::getSunOS('5.11');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::SUNOS, $os->getName());
        $this->assertEquals('5.11', $os->getVersion());
    }

    public function testGetSunOSWithoutVersion(): void
    {
        $os = Os::getSunOS();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::SUNOS, $os->getName());
        $this->assertNull($os->getVersion());
    }

    public function testGetOs2(): void
    {
        $os = Os::getOs2('1.3');

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::OS2, $os->getName());
        $this->assertEquals('1.3', $os->getVersion());
    }

    public function testGetOs2WithoutVersion(): void
    {
        $os = Os::getOs2();

        $this->assertInstanceOf(Os::class, $os);
        $this->assertEquals(Os::OS2, $os->getName());
        $this->assertNull($os->getVersion());
    }
}
