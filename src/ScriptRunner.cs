using System;
using System.IO;
using Newtonsoft.Json.Linq;
using ProcessMakerSDK.Client;
 
/*
Our quick and simple .net c sharp script runner that prepares reading 
json data from a local file as well as config. Prepares a dynamic object 
to be utilized for dynamic json population then writes the contents of the 
object to stdout.
 */
public class ScriptRunner
{
    static public void Main ()
    {
        string apiHost = Environment.GetEnvironmentVariable("API_HOST");
        string apiToken = Environment.GetEnvironmentVariable("API_TOKEN");

        Configuration apiConfig = Configuration.Default;
        apiConfig.BasePath = apiHost;
        apiConfig.AccessToken = apiToken;

        dynamic data = JToken.Parse(File.ReadAllText(@"data.json"));
        dynamic config = JToken.Parse(File.ReadAllText(@"config.json"));
        dynamic output = new JObject();
        Script script = new Script();
        script.Execute(data, config, output, apiConfig);
        File.WriteAllText(@"output.json", output.ToString());
    }
}
