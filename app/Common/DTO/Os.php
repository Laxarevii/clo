<?php

namespace App\Common\DTO;

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

    public function __construct(
        private string $value,
        private ?string $version = null
    ) {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function createOSX(UserAgent $userAgent): Os
    {
        if (preg_match('/OS X ([\d\._]*)/i', $userAgent->getValue(), $matches)) {
            if (isset($matches[1])) {
                return new self(self::OS_X, str_replace('_', '.', $matches[1]));
            }
        }
        throw new \InvalidArgumentException(self::OS_X . ' version is missing');
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public static function createChrome(UserAgent $userAgent): self
    {
        if (preg_match('/Chrome\/([\d\.]*)/i', $userAgent->getValue(), $matches)) {
            return new self(self::CHROME_OS, $matches[1]);
        }
        throw new \InvalidArgumentException(self::CHROME_OS . ' version is missing');
    }

    public static function createIOS(UserAgent $userAgent): self
    {
        if (preg_match('/CPU( iPhone)? OS ([\d_]*)/i', $userAgent->getValue(), $matches)) {
            return new self(self::IOS, str_replace('_', '.', $matches[2]));
        }
        //todo  $os->setIsMobile(true);
        throw new \InvalidArgumentException(self::IOS . ' version is missing');
    }
}
