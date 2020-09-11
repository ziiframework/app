<?php

declare(strict_types=1);

namespace Zept\unit;

use Codeception\Test\Unit as CodeceptionUnit;
use ReflectionClass;
use ReflectionException;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Application as CliApplication;

class CeptCase extends CodeceptionUnit
{
    /**
     * {@inheritdoc}
     */
    protected function _before(): void
    {
        $this->mockApplication();

        // $this->truncateAll();
    }

    /**
     * {@inheritdoc}
     */
    protected function _after(): void
    {
        $this->destroyApplication();
    }

    /**
     * Mock console application
     * @return void
     */
    protected function mockApplication(): void
    {
        $config = require __DIR__ . '/../../config/test.php';

        if (isset($config['language'])) {
            $this->assertSame('en-US', $config['language']);
        }
        if (isset($config['timeZone'])) {
            $this->assertSame('Asia/Shanghai', $config['timeZone']);
        }
        if (isset($config['components']['mailer']['useFileTransport'])) {
            $this->assertTrue($config['components']['mailer']['useFileTransport']);
        }
        if (isset($config['controllerNamespace'])) {
            $this->assertSame('app\\commands', $config['controllerNamespace']);
        }

        if (isset($config['class'])) {
            $this->assertSame(CliApplication::class, $config['class']);
            unset($config['class']);
        }

        try {
            new CliApplication($config);
        } catch (InvalidConfigException $e) {
            echo $e->getMessage() . "\n";
            exit(1);
        }
    }

    /**
     * Destroy mocked application.
     * @return void
     */
    protected function destroyApplication(): void
    {
        Yii::$app = null;
    }

    /**
     * Invokes object method, even if it is private or protected.
     * @param object $object object.
     * @param string $method method name.
     * @param array $args method arguments
     * @return mixed method result
     * @throws ReflectionException
     */
    protected function invoke(object $object, string $method, array $args = [])
    {
        $rClass = new ReflectionClass(get_class($object));

        $rMethod = $rClass->getMethod($method);

        $rMethod->setAccessible(true);
        $invoked = $rMethod->invokeArgs($object, $args);
        $rMethod->setAccessible(false);

        return $invoked;
    }

    protected function truncateAll(): void
    {
//        Yii::$app->db->createCommand()->checkIntegrity(false)->execute();
//        Yii::$app->db->createCommand()->truncateTable('auth_item')->execute();
//        Yii::$app->db->createCommand()->truncateTable('auth_item_child')->execute();
//        Yii::$app->db->createCommand()->truncateTable('auth_assignment')->execute();
//        Yii::$app->db->createCommand()->truncateTable('auth_rule')->execute();
//        Yii::$app->db->createCommand()->checkIntegrity(true)->execute();
    }
}
