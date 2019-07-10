using Newtonsoft.Json.Linq;
using ProcessMakerSDK.Client;

/**
BaseScript is the base class that a custom script should inherit.  It has one single 
method to execute the script, passing in the data, config as well as a prepared output 
object to populate.
 */
public abstract class BaseScript
{
    public abstract void Execute(dynamic data, dynamic config, dynamic output, Configuration apiConfig);
}