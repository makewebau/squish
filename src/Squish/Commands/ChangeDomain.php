<?php

namespace MakeWeb\Squish\Commands;

use Illuminate\Database\Capsule\Manager as Capsule;
use MakeWeb\Squish\CLI;
use MakeWeb\Squish\Command;
use MakeWeb\Squish\Models\Option;
use MakeWeb\Squish\Models\Post;
use MakeWeb\Squish\Models\PostMeta;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ChangeDomain extends Command
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

        return $this->cli->command('domain:change [path]', function ($path, SymfonyStyle $style, InputInterface $input, OutputInterface $output) use ($command) {
            return $command
                ->setInput($input)
                ->setOutput($output)
                ->setStyle($style)
                ->execute($path);
        });
    }

    public function execute($path)
    {
        $this->getInput();

        $this->updateDomainNameInDatabase();

        // TODO Update database constanstts set in wp_config
        // $this->updateDatabaseCredentials();
        //
        // TODO Update domain constants set in wp_config.php
        // $this->updateDomainConstant()
    }

    protected function getInput()
    {
        $this->oldDomain = $this->ask('What is the old domain? (without protocol)');

        $this->newDomain = $this->ask('What is the new domain? (without protocol)', str_replace('.com', '.dev', $this->oldDomain));

        $this->databaseName = $this->ask('What is the name of the database?', explode('.', $this->oldDomain)[0]);

        $this->tablePrefix = $this->ask('What is the table prefix of the database? (without underscore)', 'mw');
    }

    protected function updateDomainNameInDatabase()
    {
        $this->bootCapsule();

        $this->outputYellow('Updating site url settings');
        Option::where('option_name', 'home')->first()->update(['option_value' => 'http://'.$this->newDomain]);
        Option::where('option_name', 'siteurl')->first()->update(['option_value' => 'http://'.$this->newDomain]);
        $this->outputGreen('DONE');

        $this->outputYellow('Updating urls in posts table');
        $posts = Post::all()->each(function ($post) {
            $post->update([
                'guid' => str_replace($this->oldDomain, $this->newDomain, $post->guid),
                'post_content' => str_replace($this->oldDomain, $this->newDomain, $post->post_content),
            ]);
        });
        $this->outputGreen('DONE');

        $this->outputYellow('Updating urls in postmeta table');
        $postmeta = PostMeta::all()->each(function ($postMeta) {
            $postMeta->update([
                'meta_value' => str_replace($this->oldDomain, $this->newDomain, $postMeta->meta_value)
            ]);
        });
        $this->outputGreen('DONE');
    }

    protected function bootCapsule()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => $this->databaseName,
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => $this->tablePrefix.'_',
        ]);


        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();

        $this->capsule = $capsule;
    }
}
