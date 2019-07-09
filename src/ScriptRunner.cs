using System;
using System.IO;
using Newtonsoft.Json.Linq;
 
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
        dynamic data = JToken.Parse(File.ReadAllText(@"data.json"));
        dynamic config = JToken.Parse(File.ReadAllText(@"config.json"));
        dynamic output = new JObject();
        Script script = new Script();
        script.Execute(data, config, output);
        File.WriteAllText(@"output.json", output.ToString());
    }
}
