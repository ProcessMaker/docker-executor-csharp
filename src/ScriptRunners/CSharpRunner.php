<?php

namespace ProcessMaker\ScriptRunners;

class CSharpRunner extends Base
{
    /**
     * Configure docker with C# executor
     *
     * @param string $code
     * @param array $dockerConfig
     *
     * @return array
     */
    public function config($code, array $dockerConfig)
    {
        $dockerConfig['image'] = config('script-runners.csharp.image');
        $dockerConfig['command'] = 'dotnet run';
        $dockerConfig['inputs']['/opt/executor/Script.cs'] = $code;

        return $dockerConfig;
    }
}
