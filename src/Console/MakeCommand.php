<?php

namespace Rymanalu\FactoryGenerator\Console;

use Illuminate\Console\GeneratorCommand;

class MakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'make:factory {name : The classname of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model factory file';

    /**
     * The type of file being generated.
     *
     * @var string
     */
    protected $type = 'Factory';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/make.stub';
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        return class_basename($name);
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->laravel->databasePath().'/factories/'.$name.'Factory.php';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceClassAndNamespace($name, $stub)->replaceFields($name, $stub);
    }

    /**
     * Replace the classname and the namespace for the given stub.
     *
     * @param  string  $name
     * @param  string  $stub
     * @return \Rymanalu\FactoryGenerator\Console\MakeCommand
     */
    protected function replaceClassAndNamespace($name, &$stub)
    {
        $stub = str_replace(
            ['DummyClassWithNamespace', 'DummyClass'],
            [$this->argument('name'), $name],
            $stub
        );

        return $this;
    }

    /**
     * Replace the fields of the model factory.
     *
     * @param  string  $name
     * @param  string  $stub
     * @return string
     */
    protected function replaceFields($name, &$stub)
    {
        if (empty($fillable = $this->getFillable())) {
            return str_replace('fields', '//', $stub);
        }

        $fields = 'return [';

        foreach ($fillable as $column) {
            $fields .= PHP_EOL."        '{$column}' => \$faker->word,";
        }

        return str_replace('fields', $fields.PHP_EOL.'    ];', $stub);
    }

    /**
     * Get the fillable attributes for the classname model.
     *
     * @return array
     */
    protected function getFillable()
    {
        $model = $this->argument('name');

        return (new $model)->getFillable();
    }
}
