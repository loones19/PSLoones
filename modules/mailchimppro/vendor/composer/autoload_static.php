<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcb8c53e76502be44c9efe5ca10505d79
{
    public static $files = array (
        'd4b3877d06f9b76941adbfe5d3cb2fbf' => __DIR__ . '/../..' . '/src/LinkHelper.php',
        'b230e1fbf7ff4907477dbbf4766a9d49' => __DIR__ . '/../..' . '/src/MailchimpProConfig.php',
    );

    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DrewM\\MailChimp\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DrewM\\MailChimp\\' => 
        array (
            0 => __DIR__ . '/..' . '/drewm/mailchimp-api/src',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/src',
    );

    public static $prefixesPsr0 = array (
        'J' => 
        array (
            'JasonGrimes' => 
            array (
                0 => __DIR__ . '/..' . '/jasongrimes/paginator/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcb8c53e76502be44c9efe5ca10505d79::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcb8c53e76502be44c9efe5ca10505d79::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInitcb8c53e76502be44c9efe5ca10505d79::$fallbackDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitcb8c53e76502be44c9efe5ca10505d79::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
