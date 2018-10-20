<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年6月28日
*/
namespace CoreBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 更新表结构
 * @author Administrator
 *
 */
class UpdateTableCommand extends Command
{
    protected function configure()
    {
        $this
        // the name of the command (the part after "app/console")
        ->setName('core:updatetable')

        // the short description shown while running "php app/console list"
        ->setDescription('Update All Tables.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to Update All Tables...');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln(array(
            'Update All Tables',
            '========================',
            '',
        ));
        
        //$container = $this->getApplication()->getKernel()->getContainer();
        //$info = $container->get('db.users')->findOneBY(array('id'=>1));
        //var_dump($info);
        // outputs a message without adding a "\n" at the end of the line
        $output->write('update success.');
    }
}