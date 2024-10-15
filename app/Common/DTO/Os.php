<?php

namespace App\Common\DTO;

use App\Exceptions\UnknownOSException;
use http\Exception\InvalidArgumentException;

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

    public function getVersion(): string
    {
        return $this->version;
    }
}
