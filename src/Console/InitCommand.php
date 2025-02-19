<?php namespace Jigsaw\Jigsaw\Console;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    private $files;
    private $base;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Scaffold a new Jigsaw project.')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Where should we initialize this project?'
            );
    }

    protected function fire()
    {
        if ($base = $this->input->getArgument('name')) {
            $this->base = getcwd() . '/' . $base;
            $this->createBaseDirectory();
        }
        $this->createSourceFolder();
        $this->createBaseConfig();
        $this->info('Site initialized successfully!');
    }

    private function createBaseDirectory()
    {
        if (! $this->files->isDirectory($this->base)) {
            $this->files->makeDirectory($this->base);
        }
    }

    private function createSourceFolder()
    {
        $this->files->makeDirectory($this->base . '/source');
    }

    private function createBaseConfig()
    {
        $this->files->put($this->base . '/config.php', <<<EOT
<?php

return [
    'production' => false,
];
EOT
        );
    }
}
