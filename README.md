# Laravel 5 Model Factory Generator

This package offers a lazy way to create a new model factory files, since Laravel has no Artisan command to generate it.

## Installation
First, install this package via the Composer package manager:
```
composer require rymanalu/factory-generator
```

Next, you should add the `FactoryGeneratorServiceProvider` to the `providers` array of your `config/app.php` configuration file:
```php
Rymanalu\FactoryGenerator\FactoryGeneratorServiceProvider::class,
```
Now, you should be able to generate a new model factory file by executing `php artisan make:factory` command.

## Usage
`php artisan make:factory` accept one argument: the model class name with the namespace. Make sure the model is already exists before execute this command.

Example:

```
php artisan make:factory "App\Post"
```
The command will generate a file named `PostFactory.php` in `/path/to/your-laravel-project/database/factories` directory:

```php
<?php

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    //
});
```
This command also using the fillable array of the model and pair all of fillable values to `$faker->word` as default (you can change it to the proper [Faker](https://github.com/fzaninotto/Faker) Formatters or other value later) in the generated model factory.

For example, if the `App\Post` has fillable array like this:
```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
    ];
}
```
Then, the command will generate the `PostFactory.php` like this:
```php
<?php

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'text' => $faker->word,
    ];
});
```
