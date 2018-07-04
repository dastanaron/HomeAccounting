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

    }

    /**
     *
     */
    public function run()
    {
        foreach($this->commandPull as $command) {
            exec($command.' 2>&1', $output);

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
        $this->push('cd '.PROJECT_PATH.'/')
            ->push('pwd')
            ->push('git pull origin master')
            ->push('git fetch')
            ->push('git checkout ' . $this->params['branch']);

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

    protected function push($command)
    {
        $this->commandPull[] = $command;

        return $this;
    }

}