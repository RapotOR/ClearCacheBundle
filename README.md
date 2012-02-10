Introduction
============

Sf2genCacheClearBundle give you the possibility to clear a part of the cache.
The basic "cache:clear" is too expensive in production for a big application.
So, clearing the whole cache for modifying few things is too much!

Supported bundles
=================

  * TwigBundle

Use it
======

    php app/console cache:clear:twig MyBundle::layout.html.twig

Installation
============

  1. Add this bundle to your vendor/ dir:

        $ git submodule add git://github.com/RapotOR/ClearCacheBundle.git vendor/bundles/Sf2gen/Bundle/ClearCacheBundle

  2. Add the Sf2gen namespace to your autoloader:

        // app/autoload.php
        $loader->registerNamespaces(array(
            'Sf2gen' => __DIR__.'/../vendor/bundles',
            // other namespaces
        ));

  3. Add this bundle to your application's kernel, in the debug section:

        // app/ApplicationKernel.php
        public function registerBundles()
        {
            $bundles = array(
                // all bundles            
            );

            if (in_array($this->getEnvironment(), array('dev', 'test'))) {
                // previous bundles like WebProfilerBundle
                $bundles[] = new Sf2gen\Bundle\ConsoleBundle\Sf2genClearCacheBundle();
            }

            return $bundles;
        }
