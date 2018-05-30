<?php
/**
 * Created by PhpStorm.
 * User: FelixMac
 * Date: 28/05/2018
 * Time: 16:03"
 */


namespace App\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;
use App\Server\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class ChatServerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:chat-server:start')
            ->setDescription('Start chat server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = IoServer::factory(
            new HttpServer(new WsServer(new Chat())),
            8080,
            '127.0.0.1'
        );
        try {
            $this->getApplication()->find("server:start")->run(new ArrayInput([]), $output);
        } catch (\Exception $e) {
            $output->writeln("<error>".$e->getMessage()."</error>");
        }
        $output->writeln("<info> [OK] Serveur lancé sur le port 8080</info>");
        $server->run();
        $output->writeln("Fermeture du serveur");
    }
}