# executor-php
Script Task Executor Engine with Mono Runtime to support C#

This docker image provides a sandboxed protected environment to run custom C# "scripts" that are written in ProcessMaker BPM.
User created script tasks should be isolated however have utilities available to them in order to get most common tasks done. This 
.net core environment has a C# Solution Skeleton that includes the following libraries:

- Newtonsoft.Json 12.0.2
- TODO : Identify most common libraries used for existing script tasks

## How to use
The execution requires a data.json, config.json and an output.json file be present on the host system. The data.json represents the 
Request instance data.  The config.json represents configuration specific for this Script Task. And the output.json should be a blank 
file that will be populated by the successful output of the script task. The script task is represented by a Script.cs file.
It is the responsibility of the caller to have these files prepared before executing the engine via command line (or docker API).

## Script Task design
When writing a Script Task, three variables are available.  They are:

- data - A dynamic object that represents the current Request data
- config - A dynamic object that represents the current Script Task configuration
- output - A dynamic object you can populate with your desired JSON changes. See: https://www.newtonsoft.com/json/help/html/CreateJsonDynamic.htm

Your script must extend the BaseScript class. The method signature is:
```csharp
public abstract void Execute(dynamic data, dynamic config, dynamic output)
```
Your script can read JSON properties from data aond config. Then it can populate the dynamic output object with whatever JSON data you wish to output.
 Once the script is complete, the final output to console will be used and converted to JSON which
will be stored in the output.json file.  Once the docker execution is complete, you should evaluate the return code of the docker execution. 
If the code is 0, then the script task executed successfully and you can read output.json for the valid output.  If it is non-zero,
then you should review STDERR to see the error that was displayed during execution.

### Example data.json
```json
{
  "firstname": "Taylor"
}
```

### Example Script Task
```csharp
public class Script : BaseScript
{
    public override void Execute(dynamic data, dynamic config, dynamic output)
    {
        output.firstname = ((string)data.firstname).ToUpper();
    }
}
```

### Example output.json
```json
{"firstname":"TAYLOR"}
```

## Command Line Usage
```bash
$ docker run -v <path to local data.json>:/opt/executor/data.json \
  -v <path to local config.json>:/opt/executor/config.json \
  -v <path to local Script.cs>:/opt/executor/Script.cs \
  -v <path to local output.json>:/opt/executor/output.json \
  processmaker/executor:csharp \
  "dotnet run" 
```
