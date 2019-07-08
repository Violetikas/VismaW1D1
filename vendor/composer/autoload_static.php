<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit28336864616a8349b117012391e37377
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Fikusas\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Fikusas\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit28336864616a8349b117012391e37377::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit28336864616a8349b117012391e37377::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
