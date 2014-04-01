<?php namespace LabtechSoftware\ConnectwisePsaSdk\Support;

use LabtechSoftware\ConnectwisePsaSdk\Support\ApiException;

/**
 * Configuration
 *
 * @package ConnectwisePsaSdk
 */
class Config
{
    /**
     * Config values
     *
     * @var array
     */
    private $config = array();

    /**
     * Sets up the config array
     * Either a path or a multidimensional array can be passed in
     *
     * @return void
     **/
    public function __construct($config)
    {
        // Is this a path to a config?
        if (is_string($config)) {
            // Validate path and parse config ini to an array
            // Throws exception on failure
            $configArray = $this->isConfigValid($config);
        } elseif (is_array($config)) {
            // Since this is already the multidimensional array we just set $configArray to it
            $configArray = $config;
        } else {
            // Throw an error because the variable passed is probably not the right thing anyways
            throw new ApiException('Variable type passed to Config not supported');
        }

        // Bind config array to property
        $this->set($configArray);
    }

    /**
     * Bind each config key => value item to the config property
     *
     * @param array $configArray
     * @return void
     */
    public function set(array $configArray = array())
    {
        // Loop through config items and bind each to config array
        foreach ($configArray as $key => $val) {
            $this->config[$key] = $val;
        }
    }

    /**
     * Get a specific item from the stored config array
     *
     * @throws LabtechSoftware\ConnectwisePsaSdk\Support\ApiException
     * @param mixed (string|numeric)
     * @return mixed
     **/
    public function get($key)
    {
        // Throw exception if the item (key) does not exist in config array
        if (array_key_exists($key, $this->config) === false) {
            throw new ApiException('Item not found in config.');
        }

        return $this->config[$key];
    }

    /**
     * Return the entire config array
     *
     * @return array
     **/
    public function all()
    {
        return $this->config;
    }

    /**
     * Make sure we can use the config file!
     *
     * @throws LabtechSoftware\ConnectwisePsaSdk\Support\ApiException
     * @param string $pathToConfigFile
     * @return array
     **/
    protected function isConfigValid($fullPath)
    {
        // First, check that the config file is there
        if (is_file($fullPath) === false) {
            throw new ApiException('Config file not found.');
        }

        // Parse the config ini to a multidimensional array
        // parse_ini_file() returns boolean false if unsuccessful
        $configToArray = parse_ini_file($fullPath, true);

        // If return data is not an array, the parse failed
        if (is_array($configToArray) === false) {
            throw new ApiException('Failed to parse config file.');
        }

        // All good if we got this far (no exceptions), return config array
        return $configToArray;
    }
}
