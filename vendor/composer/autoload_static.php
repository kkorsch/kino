<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit80215b61199647821d8685e52c2b2112
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit80215b61199647821d8685e52c2b2112::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit80215b61199647821d8685e52c2b2112::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
