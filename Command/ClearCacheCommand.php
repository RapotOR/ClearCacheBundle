<?php

namespace Sf2gen\Bundle\ClearCacheBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

abstract class ClearCacheCommand extends ContainerAwareCommand
{
    protected function clearCacheFile($env, $cachePath, $cacheFile, $inputArgument)
    {
        $message = '';
        
        $target = $this->getContainer()->getParameter('kernel.root_dir') . '/cache/' . $env . '/' . $cachePath . '/' . $cacheFile;
        if (is_file($target)) {
            if(unlink($target)){
                $message = '["%s"] "%s" cleared. ["%s"]';
            }else{
                $message = '["%s"] "%s" can not be deleted. ["%s"]';
            }
        }else{
            $message = '["%s"] "%s" does not exist. ["%s"]';
        }
        
        return sprintf($message, $env, $cacheFile, $inputArgument);
    }
}