<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Runner;

use File_Iterator_Facade;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestSuite;
use ReflectionClass;
use ReflectionException;

/**
 * Base class for all test runners.
 */
abstract class BaseTestRunner
{
    public const STATUS_PASSED = 0;
    public const STATUS_SKIPPED = 1;
    public const STATUS_INCOMPLETE = 2;
    public const STATUS_FAILURE = 3;
    public const STATUS_ERROR = 4;
    public const STATUS_RISKY = 5;
    public const STATUS_WARNING = 6;
    public const SUITE_METHODNAME = 'suite';

    /**
     * Returns the loader to be used.
     *
     * @return TestSuiteLoader
     */
    public function getLoader(): TestSuiteLoader
    {
        return new StandardTestSuiteLoader;
    }

    /**
     * Returns the Test corresponding to the given suite.
     * This is a template method, subclasses override
     * the runFailed() and clearStatus() methods.
     *
     * @param string $suiteClassName
     * @param string $suiteClassFile
     * @param mixed  $suffixes
     *
     * @throws Exception
     *
     * @return null|Test
     */
    public function getTest(
        $suiteClassName,
        $suiteClassFile = '',
        $suffixes = ''
    ): ?Test {
        if (\is_dir($suiteClassName) && !\is_file($suiteClassName.'.php')
            && empty($suiteClassFile)
        ) {
            $facade = new File_Iterator_Facade;
            $files = $facade->getFilesAsArray(
                $suiteClassName,
                $suffixes
            );

            $suite = new TestSuite($suiteClassName);
            $suite->addTestFiles($files);

            return $suite;
        }

        try {
            $testClass = $this->loadSuiteClass(
                $suiteClassName,
                $suiteClassFile
            );
        } catch (Exception $e) {
            $this->runFailed($e->getMessage());

            return null;
        }

        try {
            $suiteMethod = $testClass->getMethod(self::SUITE_METHODNAME);

            if (!$suiteMethod->isStatic()) {
                $this->runFailed(
                    'suite() method must be static.'
                );

                return null;
            }

            try {
                $test = $suiteMethod->invoke(null, $testClass->getName());
            } catch (ReflectionException $e) {
                $this->runFailed(
                    \sprintf(
                        "Failed to invoke suite() method.\n%s",
                        $e->getMessage()
                    )
                );

                return null;
            }
        } catch (ReflectionException $e) {
            try {
                $test = new TestSuite($testClass);
            } catch (Exception $e) {
                $test = new TestSuite;
                $test->setName($suiteClassName);
            }
        }

        $this->clearStatus();

        return $test;
    }

    /**
     * Returns the loaded ReflectionClass for a suite name.
     *
     * @param string $suiteClassName
     * @param string $suiteClassFile
     *
     * @return ReflectionClass
     */
    protected function loadSuiteClass(
        $suiteClassName,
        $suiteClassFile = ''
    ): ReflectionClass {
        $loader = $this->getLoader();

        return $loader->load($suiteClassName, $suiteClassFile);
    }

    /**
     * Clears the status message.
     */
    protected function clearStatus(): void
    {
    }

    /**
     * Override to define how to handle a failed loading of
     * a test suite.
     *
     * @param string $message
     */
    abstract protected function runFailed($message);
}
