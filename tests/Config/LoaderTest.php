<?php
namespace Nogo\Framework\Tests\Config;

use Nogo\Framework\Config\Loader;

require dirname(__FILE__) . '/../../Config/Loader.php';
require dirname(__FILE__) . '/../../Config/FileNotFoundException.php';

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testMerge()
    {
        $config = new Loader(null);
        $config->import(dirname(__FILE__) . '/test.yml');
        $config->merge([
            'test' => 'test1',
            'test2' => 'test2'
        ]);
        $this->assertTrue(isset($config['test']));
        $this->assertEquals('test1', $config['test']);
        $this->assertTrue(isset($config['test2']));
        $this->assertEquals('test2', $config['test2']);
    }

    public function testResolve()
    {
        $loader = new Loader();
        $config = [
          'root_dir' => '/tmp',
          'path' => '%root_dir%/test'
        ];
        $result = $loader->resolve($config);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('path', $config);
        $this->assertEquals('/tmp/test', $result['path']);
    }

    public function testImportSingleFile()
    {
        $config = new Loader();
        $config->import(dirname(__FILE__) . '/test.yml');
        $this->assertCount(1, $config);
        $this->assertTrue(isset($config['test']));
        
    }

    public function testImportSingleFileWithInternalImport()
    {
        $config = new Loader();
        $config->import(dirname(__FILE__) . '/test2.yml');
        $this->assertCount(2, $config);
        $this->assertTrue(isset($config['test']));
        $this->assertTrue(isset($config['test2']));
        
    }
}
