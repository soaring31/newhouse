<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年6月28日
 */

namespace HouseBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 更新表结构
 * @author Administrator
 *
 */
class deleteSessionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('delete:session')
            // the short description shown while running "php app/console list"
            ->setDescription('删除 session')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('定时删除数据库 session');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $begin = microtime();
        $output->writeln(sprintf('开始时间 %s', $begin));
        $connect = $this->getContainer()->get('doctrine.dbal.house_connection');
        $sql     = sprintf('DELETE FROM `cms_sessions` WHERE `sess_lifetime` + `sess_time` < %s', time());
        $num     = $connect->exec($sql);
        $end = microtime();
        $output->writeln(sprintf('结束时间 %s', $end));

        $output->writeln(sprintf('耗时 %s', ($end - $begin)));

        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln(array(
            'Delete Session Success',
            '========================',
            '删除数量' . $num,
            '',
        ));

        //$container = $this->getApplication()->getKernel()->getContainer();
        //$info = $container->get('db.users')->findOneBY(array('id'=>1));
        //var_dump($info);
        // outputs a message without adding a "\n" at the end of the line
        $output->write('update success.');
    }
}