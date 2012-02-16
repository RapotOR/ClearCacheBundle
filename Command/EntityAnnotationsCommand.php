<?php

namespace Sf2gen\Bundle\ClearCacheBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EntityAnnotationsCommand extends ClearCacheCommand
{
    protected function configure()
    {
        parent::configure();

        $this
                ->setName('cache:clear:entity')
                ->setDescription('Clear cache for entity\'s annotations.')
                ->addArgument('entity', InputArgument::REQUIRED, 'Action name (AcmeMyBundle:User)')
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
        $entity = $input->getArgument('entity');
        $options = $input->getOptions();
        
        $path = explode(':', $entity);
        if (count($path) == 2) {
            $bundle = $this->getApplication()->getKernel()->getBundle($path[0]);

            //AcmeMyBundle:User        => [Acme-MyBundle-Entity-User.cache.php, Acme-MyBundle-Entity-User#id.cache.php, Acme-MyBundle-Entity-User#name.cache.php, ...]
            $entityCache = str_replace('\\','-',$bundle->getNamespace()) . '-Entity-' . str_replace('\\','-',$path[1]) . '*.cache.php';

            $output->writeln($this->clearCacheFile($options['target-env'], 'annotations', $entityCache, $entity));
        }else{
            $output->writeln(sprintf('The entity "%s" is not valid. (Format: AcmeMyBundle:User)', $action));
        }
    }
}