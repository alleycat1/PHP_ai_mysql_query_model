<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit41aa0250235038abafb49a108540ac4a
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Orhanerday\\OpenAi\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Orhanerday\\OpenAi\\' => 
        array (
            0 => __DIR__ . '/..' . '/orhanerday/open-ai/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit41aa0250235038abafb49a108540ac4a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit41aa0250235038abafb49a108540ac4a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit41aa0250235038abafb49a108540ac4a::$classMap;

        }, null, ClassLoader::class);
    }
}