<?php

namespace App\Entity;

class Os
{
    public const OS_X = 'OS X';
    public const IOS = 'iOS';
    public const SYMBOS = 'SymbOS';
    public const WINDOWS = 'Windows';
    public const ANDROID = 'Android';
    public const LINUX = 'Linux';
    public const NOKIA = 'Nokia';
    public const BLACKBERRY = 'BlackBerry';
    public const FREEBSD = 'FreeBSD';
    public const OPENBSD = 'OpenBSD';
    public const NETBSD = 'NetBSD';
    public const OPENSOLARIS = 'OpenSolaris';
    public const SUNOS = 'SunOS';
    public const OS2 = 'OS2';
    public const BEOS = 'BeOS';
    public const WINDOWS_PHONE = 'Windows Phone';
    public const CHROME_OS = 'Chrome OS';
    public const VERSION_UNKNOWN = 'unknown';

    private function __construct(
        private string $name,
        private ?string $version = null
    ) {
        //TODO check empty value
    }

    public static function getOsX(?string $version = null): self
    {
        return new self(self::OS_X, $version);
    }

    public static function getIos(?string $version = null): self
    {
        return new self(self::IOS, $version);
    }

    public static function getSymbOS(?string $version = null): self
    {
        return new self(self::SYMBOS, $version);
    }

    public static function getWindows(?string $version = null): self
    {
        return new self(self::WINDOWS, $version);
    }

    public static function getAndroid(?string $version = null): self
    {
        return new self(self::ANDROID, $version);
    }

    public static function getLinux(?string $version = null): self
    {
        return new self(self::LINUX, $version);
    }

    public static function getNokia(?string $version = null): self
    {
        return new self(self::NOKIA, $version);
    }

    public static function getBlackBerry(?string $version = null): self
    {
        return new self(self::BLACKBERRY, $version);
    }

    public static function getFreeBSD(?string $version = null): self
    {
        return new self(self::FREEBSD, $version);
    }

    public static function getOpenBSD(?string $version = null): self
    {
        return new self(self::OPENBSD, $version);
    }

    public static function getNetBSD(?string $version = null): self
    {
        return new self(self::NETBSD, $version);
    }

    public static function getOpenSolaris(?string $version = null): self
    {
        return new self(self::OPENSOLARIS, $version);
    }

    public static function getSunOS(?string $version = null): self
    {
        return new self(self::SUNOS, $version);
    }

    public static function getOs2(?string $version = null): self
    {
        return new self(self::OS2, $version);
    }

    public static function getBeOS(?string $version = null): self
    {
        return new self(self::BEOS, $version);
    }

    public static function getWindowsPhone(?string $version = null): self
    {
        return new self(self::WINDOWS_PHONE, $version);
    }

    public static function getChromeOs(?string $version = null): self
    {
        return new self(self::CHROME_OS, $version);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }
}
