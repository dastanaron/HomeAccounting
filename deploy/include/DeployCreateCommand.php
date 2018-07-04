<?php

class DeployCreateCommand
{

    /**
     * @var array
     */
    public $params = [];

    /**
     * @var array
     */
    protected $commandsOutputs = [];

    /**
     * @var array
     */
    protected $commandPull = [];

    /**
     * DeployCreateCommand constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->params = $args;

    }

    /**
     * @param $args
     * @return DeployCreateCommand
     */
    public static function init($args)
    {
        return new self($args);
    }

    /**
     *
     */
    public function buildCommands()
    {

        if(isset($this->params['branch'])) {
            $this->gitCommand();
        }

        if(isset($this->params['commands']['composer'])) {
            $this->composerCommand();
        }

        if(isset($this->params['commands']['migration'])) {
            $this->push('php artisan migrate');
        }

        if(isset($this->params['script_generate'])) {
            echo 'Записан файл релиза: ' . $this->scriptGenerate();
        }

        return $this;
    }

    /**
     *
     */
    public function run()
    {
        foreach($this->commandPull as $command) {
            exec('cd ' . PROJECT_PATH . ' && '.$command.' 2>&1', $output);

            echo '<pre>Команда: '. $command . ' вывод: '.var_export($output, true). '</pre>' .PHP_EOL;

            $this->commandsOutputs[] = $output;

            unset($output);
        }
    }

    /**
     * @return $this
     */
    public function gitCommand()
    {
        $this->push('git pull origin master')
        ->push('git fetch')
        ->push('git checkout ' . $this->params['branch']);

        return $this;
    }

    protected function scriptGenerate()
    {
        $releaseNumberFile = __DIR__.'/release';

        if(!file_exists($releaseNumberFile)) {
            file_put_contents($releaseNumberFile, 1);
            $release = 0;
        }
        else {
            $release = (int) file_get_contents($releaseNumberFile);
        }

        $string = '#!/bin/bash'.PHP_EOL.PHP_EOL;

        $string .= 'cd '.PROJECT_PATH.PHP_EOL;

        foreach ($this->commandPull as $command) {
            $string .= $command . PHP_EOL;
        }

        $release_v = $release +1;
        file_put_contents($releaseNumberFile, $release_v);

        return file_put_contents(__DIR__.'/../releases/'.time().'_r_0'.$release_v.'.sh', $string);

    }

    public function composerCommand()
    {
        $this->push('composer install');

        return $this;
    }

    public function getPullCommands()
    {
        return $this->commandPull;
    }

    public function getOutputArray()
    {
        return $this->commandsOutputs;
    }

    public function push($command)
    {
        $this->commandPull[] = $command;

        return $this;
    }

}