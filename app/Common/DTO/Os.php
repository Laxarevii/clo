<?php

namespace App\Common\DTO;

class Os
{
    const UNKNOWN = 'unknown';
    const OSX = 'OS X';
    const IOS = 'iOS';
    const SYMBOS = 'SymbOS';
    const WINDOWS = 'Windows';
    const ANDROID = 'Android';
    const LINUX = 'Linux';
    const NOKIA = 'Nokia';
    const BLACKBERRY = 'BlackBerry';
    const FREEBSD = 'FreeBSD';
    const OPENBSD = 'OpenBSD';
    const NETBSD = 'NetBSD';
    const OPENSOLARIS = 'OpenSolaris';
    const SUNOS = 'SunOS';
    const OS2 = 'OS2';
    const BEOS = 'BeOS';
    const WINDOWS_PHONE = 'Windows Phone';
    const CHROME_OS = 'Chrome OS';

    const VERSION_UNKNOWN = 'unknown';

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
                return new self(self::OSX, str_replace('_', '.', $matches[1]));
            }
        }
        throw new \InvalidArgumentException(self::OSX . ' version is missing');
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
