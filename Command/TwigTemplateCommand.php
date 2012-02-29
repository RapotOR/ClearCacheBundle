<?php

namespace Sf2gen\Bundle\ClearCacheBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TwigTemplateCommand extends ClearCacheCommand
{
    protected function configure()
    {
        parent::configure();

        $this
                ->setName('cache:clear:twig')
                ->setDescription('Clear cache for a targeted template.')
                ->addArgument('template', InputArgument::REQUIRED, 'Template name')
                ->addOption('target-env', 't', InputOption::VALUE_OPTIONAL, 'Environnement', 'prod')
        ;
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $template = $input->getArgument('template');
        $options = $input->getOptions();
        
        $twigEnvironnement = $this->getContainer()->get('twig');
        $twigCache = substr($twigEnvironnement->getCacheFilename($template),strlen($twigEnvironnement->getCache()));
        
        $twigCacheParts = pathinfo($twigCache);
        
        $output->writeln($this->clearCacheFile($options['target-env'], 'twig'.$twigCacheParts['dirname'] , $twigCacheParts['basename'], $template));       
    }
}