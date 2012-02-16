<?php

namespace Sf2gen\Bundle\ClearCacheBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ControllerAnnotationsCommand extends ClearCacheCommand
{
    protected function configure()
    {
        parent::configure();

        $this
                ->setName('cache:clear:action')
                ->setDescription('Clear cache for action\'s annotations.')
                ->addArgument('action', InputArgument::REQUIRED, 'Action name (AcmeMyBundle:Demo:view)')
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
        $action = $input->getArgument('action');
        $options = $input->getOptions();
        
        $path = explode(':', $action);
        if (count($path) == 3) {
            $bundle = $this->getApplication()->getKernel()->getBundle($path[0]);

            //AcmeMyBundle:Demo:view        => Acme-MyBundle-Controller-DemoController#viewAction.cache.php
            //AcmeMyBundle:Sub\Demo:view    => Acme-MyBundle-Controller-Sub-DemoController#viewAction.cache.php
            $actionCache = str_replace('\\','-',$bundle->getNamespace()) . '-Controller-' . str_replace('\\','-',$path[1]) . 'Controller#' . $path[2] . 'Action.cache.php';

            $output->writeln($this->clearCacheFile($options['target-env'], 'annotations', $actionCache, $action));
        }else{
            $output->writeln(sprintf('The action "%s" is not valid. (Format: AcmeMyBundle:Demo:view)', $action));
        }
    }
}