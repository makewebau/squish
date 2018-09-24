<?php

namespace MakeWeb\Squish\Commands;

use MakeWeb\Squish\CLI;
use MakeWeb\Squish\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ExportDatabase extends Command
{
    protected $capsule;

    protected $databaseName;

    protected $oldDomain;

    protected $newDomain;

    protected $tablePrefix;

    public function register(CLI $cli)
    {
        $command = $this;

        $this->cli = $cli;

        return $this->cli->command('db:export [path] [exportPath]', function ($path, $exportPath, SymfonyStyle $style, InputInterface $input, OutputInterface $output) use ($command) {
            return $command
                ->setInput($input)
                ->setOutput($output)
                ->setStyle($style)
                ->execute($path, $exportPath);
        });
    }

    public function execute($path, $exportPath = null)
    {
        if (is_null($exportPath)) {
            $exportPath = $path.'/export.sql';
        }

        exec('cd '.$path.'; mysqldump -p -u root kenduncan > '.$exportPath.' --max_allowed_packet=512M');
    }
}
