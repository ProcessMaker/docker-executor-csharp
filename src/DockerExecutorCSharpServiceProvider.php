<?php
namespace ProcessMaker\Package\DockerExecutorCSharp;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ProcessMaker\Traits\PluginServiceProviderTrait;

class DockerExecutorCSharpServiceProvider extends ServiceProvider
{
    use PluginServiceProviderTrait;

    const version = '1.0.0'; // Required for PluginServiceProviderTrait

    public function register()
    {
    }

    /**
     * After all service provider's register methods have been called, your boot method
     * will be called. You can perform any initialization code that is dependent on
     * other service providers at this time.  We've included some example behavior
     * to get you started.
     *
     * See: https://laravel.com/docs/5.6/providers#the-boot-method
     */
    public function boot()
    {
        \Artisan::command('docker-executor-csharp:install', function () {
            // Copy the default custom dockerfile to the storage folder
            copy(
                __DIR__ . '/../storage/docker-build-config/Dockerfile-csharp',
                storage_path("docker-build-config/Dockerfile-csharp")
            );

            // Restart the workers so they know about the new supported language
            \Artisan::call('horizon:terminate');

            // Build the base image that `executor-instance-csharp` inherits from
            system("docker build -t processmaker4/executor-csharp:latest " . __DIR__ . '/..');

            // Build the instance image. This is the same as if you were to build it from the admin UI
            \Artisan::call('processmaker:build-script-executor csharp');
        });
        
        $config = [
            'name' => 'C#',
            'runner' => 'CSharpRunner',
            'mime_type' => 'text/plain',
            'image' => env('SCRIPTS_CSHARP_IMAGE', 'processmaker4/executor-csharp'),
            'options' => [
                'packageName' => "ProcessMakerSDK",
            ],
            'init_dockerfile' => "FROM processmaker4/executor-csharp:latest\nARG SDK_DIR\n",
        ];
        config(['script-runners.csharp' => $config]);

        // $this->app['events']->listen(PackageEvent::class, PackageListener::class);

        // Complete the plugin booting
        $this->completePluginBoot();
    }
}
