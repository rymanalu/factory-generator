<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\ArrayInput;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\Console\Output\NullOutput;
use Rymanalu\FactoryGenerator\Console\MakeCommand;

class MakeCommandTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_create_a_new_model_factory()
    {
        $filesystem = Mockery::mock(Filesystem::class);
        $filesystem->shouldReceive('put');

        $laravel = Mockery::mock(Application::class);
        $laravel->shouldReceive('getNamespace')->andReturn('');
        $laravel->shouldReceive('call');

        $command = new MakeCommand($filesystem);
        $command->setLaravel($laravel);

        $command->run(
            new ArrayInput(['name' => EmptyFillable::class]), new NullOutput
        );

        $command->fire();

        $container->shouldHaveReceived('call')->with([$command, 'fire']);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}

class EmptyFillable extends Model
{
    //
}

class NotEmptyFillable extends Model
{
    protected $fillable = ['text'];
}
