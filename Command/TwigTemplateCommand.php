<?php

namespace Sf2gen\Bundle\ClearCacheBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TwigTemplateCommand extends ContainerAwareCommand
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
        
        $target = $this->getContainer()->getParameter('kernel.root_dir') . '/cache/'.$options['target-env'].'/twig' .  $twigCache;
        if (is_file($target)) {
            if(unlink($target)){
                $output->writeln(sprintf('["%s"] "%s" cleared. ["%s"]', $options['target-env'], $twigCache, $template));
            }else{
                $output->writeln(sprintf('["%s"] "%s" can not be deleted. ["%s"]', $options['target-env'], $twigCache, $template));
            }
        }else{
            $output->writeln(sprintf('["%s"] "%s" does not exist. ["%s"]', $options['target-env'], $twigCache, $template));
        }        
    }
}