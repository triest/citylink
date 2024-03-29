<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPUnit\Util\Log;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExceptionWrapper;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestFailure;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\TextUI\ResultPrinter;
use PHPUnit\Util\Filter;
use ReflectionClass;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * A TestListener that generates a logfile of the test execution using the
 * TeamCity format (for use with PhpStorm, for instance).
 */
class TeamCity extends ResultPrinter
{
    /**
     * @var bool
     */
    private $isSummaryTestCountPrinted = false;

    /**
     * @var string
     */
    private $startedTestName;

    /**
     * @var false|int
     */
    private $flowId;

    /**
     * @param TestResult $result
     */
    public function printResult(TestResult $result): void
    {
        $this->printHeader();
        $this->printFooter($result);
    }

    /**
     * An error occurred.
     *
     * @param Test       $test
     * @param \Throwable $t
     * @param float      $time
     *
     * @throws \InvalidArgumentException
     */
    public function addError(Test $test, \Throwable $t, float $time): void
    {
        $this->printEvent(
            'testFailed',
            [
                'name'    => $test->getName(),
                'message' => self::getMessage($t),
                'details' => self::getDetails($t),
            ]
        );
    }

    /**
     * A warning occurred.
     *
     * @param Test    $test
     * @param Warning $e
     * @param float   $time
     *
     * @throws \InvalidArgumentException
     */
    public function addWarning(Test $test, Warning $e, float $time): void
    {
        $this->printEvent(
            'testFailed',
            [
                'name'    => $test->getName(),
                'message' => self::getMessage($e),
                'details' => self::getDetails($e),
            ]
        );
    }

    /**
     * A failure occurred.
     *
     * @param Test                 $test
     * @param AssertionFailedError $e
     * @param float                $time
     *
     * @throws \InvalidArgumentException
     */
    public function addFailure(
        Test $test,
        AssertionFailedError $e,
        float $time
    ): void {
        $parameters = [
            'name'    => $test->getName(),
            'message' => self::getMessage($e),
            'details' => self::getDetails($e),
        ];

        if ($e instanceof ExpectationFailedException) {
            $comparisonFailure = $e->getComparisonFailure();

            if ($comparisonFailure instanceof ComparisonFailure) {
                $expectedString = $comparisonFailure->getExpectedAsString();

                if ($expectedString === null || empty($expectedString)) {
                    $expectedString
                        = self::getPrimitiveValueAsString($comparisonFailure->getExpected());
                }

                $actualString = $comparisonFailure->getActualAsString();

                if ($actualString === null || empty($actualString)) {
                    $actualString
                        = self::getPrimitiveValueAsString($comparisonFailure->getActual());
                }

                if ($actualString !== null && $expectedString !== null) {
                    $parameters['type'] = 'comparisonFailure';
                    $parameters['actual'] = $actualString;
                    $parameters['expected'] = $expectedString;
                }
            }
        }

        $this->printEvent('testFailed', $parameters);
    }

    /**
     * Incomplete test.
     *
     * @param Test       $test
     * @param \Throwable $t
     * @param float      $time
     */
    public function addIncompleteTest(
        Test $test,
        \Throwable $t,
        float $time
    ): void {
        $this->printIgnoredTest($test->getName(), $t);
    }

    /**
     * Risky test.
     *
     * @param Test       $test
     * @param \Throwable $t
     * @param float      $time
     *
     * @throws \InvalidArgumentException
     */
    public function addRiskyTest(Test $test, \Throwable $t, float $time): void
    {
        $this->addError($test, $t, $time);
    }

    /**
     * Skipped test.
     *
     * @param Test       $test
     * @param \Throwable $t
     * @param float      $time
     *
     * @throws \ReflectionException
     */
    public function addSkippedTest(Test $test, \Throwable $t, float $time): void
    {
        $testName = $test->getName();

        if ($this->startedTestName !== $testName) {
            $this->startTest($test);
            $this->printIgnoredTest($testName, $t);
            $this->endTest($test, $time);
        } else {
            $this->printIgnoredTest($testName, $t);
        }
    }

    public function printIgnoredTest($testName, \Throwable $t): void
    {
        $this->printEvent(
            'testIgnored',
            [
                'name'    => $testName,
                'message' => self::getMessage($t),
                'details' => self::getDetails($t),
            ]
        );
    }

    /**
     * A testsuite started.
     *
     * @param TestSuite $suite
     *
     * @throws \ReflectionException
     */
    public function startTestSuite(TestSuite $suite): void
    {
        if (\stripos(\ini_get('disable_functions'), 'getmypid') === false) {
            $this->flowId = \getmypid();
        } else {
            $this->flowId = false;
        }

        if (!$this->isSummaryTestCountPrinted) {
            $this->isSummaryTestCountPrinted = true;

            $this->printEvent(
                'testCount',
                ['count' => \count($suite)]
            );
        }

        $suiteName = $suite->getName();

        if (empty($suiteName)) {
            return;
        }

        $parameters = ['name' => $suiteName];

        if (\class_exists($suiteName, false)) {
            $fileName = self::getFileName($suiteName);
            $parameters['locationHint'] = "php_qn://$fileName::\\$suiteName";
        } else {
            $split = \explode('::', $suiteName);

            if (\count($split) === 2 && \method_exists($split[0], $split[1])) {
                $fileName = self::getFileName($split[0]);
                $parameters['locationHint']
                    = "php_qn://$fileName::\\$suiteName";
                $parameters['name'] = $split[1];
            }
        }

        $this->printEvent('testSuiteStarted', $parameters);
    }

    /**
     * A testsuite ended.
     *
     * @param TestSuite $suite
     */
    public function endTestSuite(TestSuite $suite): void
    {
        $suiteName = $suite->getName();

        if (empty($suiteName)) {
            return;
        }

        $parameters = ['name' => $suiteName];

        if (!\class_exists($suiteName, false)) {
            $split = \explode('::', $suiteName);

            if (\count($split) === 2 && \method_exists($split[0], $split[1])) {
                $parameters['name'] = $split[1];
            }
        }

        $this->printEvent('testSuiteFinished', $parameters);
    }

    /**
     * A test started.
     *
     * @param Test $test
     *
     * @throws \ReflectionException
     */
    public function startTest(Test $test): void
    {
        $testName = $test->getName();
        $this->startedTestName = $testName;
        $params = ['name' => $testName];

        if ($test instanceof TestCase) {
            $className = \get_class($test);
            $fileName = self::getFileName($className);
            $params['locationHint']
                = "php_qn://$fileName::\\$className::$testName";
        }

        $this->printEvent('testStarted', $params);
    }

    /**
     * A test ended.
     *
     * @param Test  $test
     * @param float $time
     */
    public function endTest(Test $test, float $time): void
    {
        parent::endTest($test, $time);

        $this->printEvent(
            'testFinished',
            [
                'name'     => $test->getName(),
                'duration' => (int)(\round($time, 2) * 1000),
            ]
        );
    }

    /**
     * @param string $progress
     */
    protected function writeProgress($progress): void
    {
    }

    /**
     * @param string $eventName
     * @param array  $params
     */
    private function printEvent($eventName, $params = []): void
    {
        $this->write("\n##teamcity[$eventName");

        if ($this->flowId) {
            $params['flowId'] = $this->flowId;
        }

        foreach ($params as $key => $value) {
            $escapedValue = self::escapeValue($value);
            $this->write(" $key='$escapedValue'");
        }

        $this->write("]\n");
    }

    /**
     * @param \Throwable $t
     *
     * @return string
     */
    private static function getMessage(\Throwable $t): string
    {
        $message = '';

        if ($t instanceof ExceptionWrapper) {
            if ($t->getClassName() !== '') {
                $message .= $t->getClassName();
            }

            if ($message !== '' && $t->getMessage() !== '') {
                $message .= ' : ';
            }
        }

        return $message.$t->getMessage();
    }

    /**
     * @param \Throwable $t
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private static function getDetails(\Throwable $t): string
    {
        $stackTrace = Filter::getFilteredStacktrace($t);
        $previous = $t instanceof ExceptionWrapper ? $t->getPreviousWrapped()
            : $t->getPrevious();

        while ($previous) {
            $stackTrace .= "\nCaused by\n".
                TestFailure::exceptionToString($previous)."\n".
                Filter::getFilteredStacktrace($previous);

            $previous = $previous instanceof ExceptionWrapper ?
                $previous->getPreviousWrapped() : $previous->getPrevious();
        }

        return ' '.\str_replace("\n", "\n ", $stackTrace);
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    private static function getPrimitiveValueAsString($value): ?string
    {
        if ($value === null) {
            return 'null';
        }

        if (\is_bool($value)) {
            return $value === true ? 'true' : 'false';
        }

        if (\is_scalar($value)) {
            return \print_r($value, true);
        }

        return null;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    private static function escapeValue(string $text): string
    {
        return \str_replace(
            ['|', "'", "\n", "\r", ']', '['],
            ['||', "|'", '|n', '|r', '|]', '|['],
            $text
        );
    }

    /**
     * @param string $className
     *
     * @throws \ReflectionException
     *
     * @return string
     */
    private static function getFileName($className): string
    {
        $reflectionClass = new ReflectionClass($className);

        return $reflectionClass->getFileName();
    }
}
