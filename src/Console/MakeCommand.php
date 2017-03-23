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
     * The type of class being generated.
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
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = $this->getClassName($name);

        return $this->laravel['path.database'].'/factories/'.$name.'Factory.php';
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
            [$name, $this->getClassName($name)],
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
        if (! $fillable = $this->getFillable($name)) {
            return str_replace('fields', '//', $stub);
        }

        foreach ($fillable as $column) {
            if (! isset($fields)) {
                $fields = 'return [';
            }

            $fields .= PHP_EOL.str_repeat(' ', 8)."'{$column}' => \$faker->word,";
        }

        return str_replace('fields', $fields.PHP_EOL.str_repeat(' ', 4).'];', $stub);
    }

    /**
     * Get the class name for the given full class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getClassName($name)
    {
        return last(explode('\\', $name));
    }

    /**
     * Get the fillable attributes for the classname model.
     *
     * @param  string  $name
     * @return array
     */
    protected function getFillable($name)
    {
        $model = new $name;

        return $model->getFillable();
    }
}
