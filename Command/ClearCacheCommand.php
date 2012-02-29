<?php

namespace Sf2gen\Bundle\ClearCacheBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Finder\Finder;

abstract class ClearCacheCommand extends ContainerAwareCommand
{
    protected function clearCacheFile($env, $cachePath, $cacheFile, $inputArgument)
    {
        $message = sprintf("Environnement '%s' with '%s':\n\n", $env, $inputArgument);

        $finder = new Finder();
        $finder->files()->in($this->getContainer()->getParameter('kernel.root_dir') . '/cache/' . $env . '/' . $cachePath . '/')
                        ->name($cacheFile);

        if (iterator_count($finder) == 0) {
            $message .= sprintf("'%s' does not exist.\n", $cachePath . '/' . $cacheFile);
        }else{
            foreach ($finder as $file) {
                if(unlink($file->getRealpath())){
                    $message .= sprintf("'%s' cleared.\n", $cachePath . '/' . $file->getFilename());
                }else{
                    $message .= sprintf("'%s' can not be deleted.\n", $cachePath . '/' . $file->getFilename());
                }
            }
        }
        
        return sprintf($message, $env, $cacheFile, $inputArgument);
    }
}