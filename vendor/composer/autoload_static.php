<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc30405294865dafbbe0f0e565b0b4909
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc30405294865dafbbe0f0e565b0b4909::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc30405294865dafbbe0f0e565b0b4909::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc30405294865dafbbe0f0e565b0b4909::$classMap;

        }, null, ClassLoader::class);
    }
}
