<?php

namespace ProcessMaker\Package\DockerExecutorCSharp;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ProcessMaker\Models\ScriptExecutor;
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
            $scriptExecutor = ScriptExecutor::install([
                'language' => 'csharp',
                'title' => 'C# Executor',
                'description' => 'Default C# Executor',
            ]);

            // Build the instance image. This is the same as if you were to build it from the admin UI
            \Artisan::call('processmaker:build-script-executor csharp');

            // Restart the workers so they know about the new supported language
            \Artisan::call('horizon:terminate');
        });

        $config = [
            'name' => 'C#',
            'runner' => 'CSharpRunner',
            'mime_type' => 'text/plain',
            'options' => [
                'packageName' => 'ProcessMakerSDK',
            ],
            'init_dockerfile' => [
                'ARG SDK_DIR',
                'COPY $SDK_DIR /opt/sdk-csharp',
                'WORKDIR /opt/sdk-csharp',
                'RUN chmod 755 build.sh',
                'RUN ./build.sh',
                'WORKDIR /opt/executor',
                'RUN mv ../sdk-csharp/bin . && rm -rf ../sdk-csharp',
            ],
            'package_path' => __DIR__ . '/..',
            'package_version' => self::version,
        ];
        config(['script-runners.csharp' => $config]);

        // $this->app['events']->listen(PackageEvent::class, PackageListener::class);

        // Complete the plugin booting
        $this->completePluginBoot();
    }
}
