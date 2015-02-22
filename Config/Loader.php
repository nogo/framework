<?php
namespace Nogo\Framework\Config;

use Slim\Slim;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Loader - Configuration loader.
 *
 * @author Danilo Kühn <dk@nogo-software.de>
 * @package Config
 */
class Loader implements \ArrayAccess
{
    /**
     * @var Slim
     */
    protected $app;

    /**
     * @var array
     */
    protected $configFiles = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * Constructor.
     *
     * @param Slim $app
     */
    public function __construct(Slim $app)
    {
        $this->app = $app;
    }

    /**
     * Load more than one file into config.
     * 
     * @param *args files to load
     * @return Loader
     */
    public function import()
    {
        if (func_num_args() > 0) {
            foreach (func_get_args() as $file) {
                try {
                    $this->load($file);
                } catch (\Exception $e) {
                    $this->app->log->debug($e);
                }
            }
        }
        return $this;
    }

    /**
     * Merge array into config array.
     *
     * @param array $config array to merge
     * @param bool $first true, this config will be first, parameter array will be second
     */
    public function merge(array $config)
    {
        $this->config = array_merge_recursive($this->config, $config);
        $this->pathConvert();
    }

    /**
     * Load config into slim configuration
     * @param Slim $app
     */
    public function refresh(Slim $app = null)
    {
        if ($app != null) {
            $this->app = $app;
        }
        $this->app->config($this->config);
    }

    /**
     * Load file.
     * 
     * @param type $file
     * @throws \Exception
     */
    protected function load($file)
    {
        if (file_exists($file)) {
            $hash = md5($file);
            if (!isset($this->configFiles[$hash])) {
                $this->configFiles[$hash] = $file;

                try {
                    $values = Yaml::parse($file);
                    $this->merge($values);
                } catch (ParseException $e) {
                    $this->app->log->debug($e);
                }

                if (isset($this->config['import'])) {
                    $import = $this->config['import'];
                    unset($this->config['import']);
                    foreach ($import as $value) {
                        $this->load($value);
                    }
                }
            }
        } else {
            throw new \Exception('File [' . $file . '] not found.');
        }
    }

    /**
     * Convert "%.*_dir%" into real path
     */
    protected function pathConvert()
    {
        array_walk_recursive(
            $this->config,
            function (&$item, $key) {
                if (preg_match('/%(.*_dir)%/', $item, $matches)) {
                    $constName = strtoupper($matches[1]);
                    if (defined($constName)) {
                        $value = constant($constName);
                        $item = str_replace('%' . $matches[1] . '%', $value, $item);
                    } else {
                        if (array_key_exists($matches[1], $this->config)) {
                            $item = str_replace('%' . $matches[1] . '%', $this->config[$matches[1]], $item);
                        }
                    }
                }
            }
        );
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->config[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }
}
