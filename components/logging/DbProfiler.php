<?php

/**
 * CProfileLogRoute class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * ProfileLogRoute displays the profiling results in Web page.
 *
 * The profiling is done by calling {@link YiiBase::beginProfile()} and {@link YiiBase::endProfile()},
 * which marks the begin and end of a code block.
 *
 * CProfileLogRoute supports two types of report by setting the {@link setReport report} property:
 * <ul>
 * <li>summary: list the execution time of every marked code block</li>
 * <li>callstack: list the mark code blocks in a hierarchical view reflecting their calling sequence.</li>
 * </ul>
 *
 * @property string $report The type of the profiling report to display. Defaults to 'summary'.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.logging
 * @since 1.0
 */
class DbProfiler extends CWebLogRoute {

    /**
     * @var boolean whether to aggregate results according to profiling tokens.
     * If false, the results will be aggregated by categories.
     * Defaults to true. Note that this property only affects the summary report
     * that is enabled when {@link report} is 'summary'.
     */
    public $groupByToken = true;

    /**
     * @var string type of profiling report to display
     */
    private $_report = 'summary';

    /**
     * Initializes the route.
     * This method is invoked after the route is created by the route manager.
     */
    public function init() {
        $this->levels = CLogger::LEVEL_PROFILE;
    }

    /**
     * @return string the type of the profiling report to display. Defaults to 'summary'.
     */
    public function getReport() {
        return $this->_report;
    }

    /**
     * @param string $value the type of the profiling report to display. Valid values include 'summary' and 'callstack'.
     * @throws CException if given value is not "summary" or "callstack"
     */
    public function setReport($value) {
        if ($value === 'summary' || $value === 'callstack')
            $this->_report = $value;
        else
            throw new CException(Yii::t('yii', 'CProfileLogRoute.report "{report}" is invalid. Valid values include "summary" and "callstack".', array('{report}' => $value)));
    }

    /**
     * Displays the log messages.
     * @param array $logs list of log messages
     */
    public function processLogs($logs) {
        $app = Yii::app();
        if (!($app instanceof CWebApplication) || $app->getRequest()->getIsAjaxRequest())
            return;

        // vdump(Setting::get('app.'));

        if ($this->getReport() === 'summary')
            $this->displaySummary($logs);
        else
            $this->displayCallstack($logs);
    }

    /**
     * Displays the callstack of the profiling procedures for display.
     * @param array $logs list of logs
     * @throws CException if Yii::beginProfile() and Yii::endProfile() are not matching
     */
    protected function displayCallstack($logs) {
        $stack   = array();
        $results = array();
        $n       = 0;
        foreach ($logs as $log) {
            if ($log[1] !== CLogger::LEVEL_PROFILE)
                continue;
            $message = $log[0];
            if (!strncasecmp($message, 'begin:', 6)) {
                $log[0]  = substr($message, 6);
                $log[4]  = $n;
                $stack[] = $log;
                $n++;
            } elseif (!strncasecmp($message, 'end:', 4)) {
                $token = substr($message, 4);
                if (($last  = array_pop($stack)) !== null && $last[0] === $token) {
                    $delta             = $log[3] - $last[3];
                    $results[$last[4]] = array($token, $delta, count($stack));
                } else
                    throw new CException(Yii::t('yii', 'CProfileLogRoute found a mismatching code block "{token}". Make sure the calls to Yii::beginProfile() and Yii::endProfile() be properly nested.', array('{token}' => $token)));
            }
        }
        // remaining entries should be closed here
        $now               = microtime(true);
        while (($last              = array_pop($stack)) !== null)
            $results[$last[4]] = array($last[0], $now - $last[3], count($stack));
        ksort($results);
        $this->render('profile-callstack', $results);
    }

    /**
     * Displays the summary report of the profiling result.
     * @param array $logs list of logs
     * @throws CException if Yii::beginProfile() and Yii::endProfile() are not matching
     */
    protected function displaySummary($logs) {
        $stack   = array();
        $results = array();
        foreach ($logs as $log) {
            if ($log[1] !== CLogger::LEVEL_PROFILE)
                continue;
            $message = $log[0];
            if (!strncasecmp($message, 'begin:', 6)) {
                $log[0]  = substr($message, 6);
                $stack[] = $log;
            } elseif (!strncasecmp($message, 'end:', 4)) {
                $token = substr($message, 4);
                if (($last  = array_pop($stack)) !== null && $last[0] === $token) {
                    $delta           = $log[3] - $last[3];
                    if (!$this->groupByToken)
                        $token           = $log[2];
                    if (isset($results[$token]))
                        $results[$token] = $this->aggregateResult($results[$token], $delta);
                    else
                        $results[$token] = array($token, 1, $delta, $delta, $delta);
                } else
                    throw new CException(Yii::t('yii', 'CProfileLogRoute found a mismatching code block "{token}". Make sure the calls to Yii::beginProfile() and Yii::endProfile() be properly nested.', array('{token}' => $token)));
            }
        }

        $now  = microtime(true);
        while (($last = array_pop($stack)) !== null) {
            $delta           = $now - $last[3];
            $token           = $this->groupByToken ? $last[0] : $last[2];
            if (isset($results[$token]))
                $results[$token] = $this->aggregateResult($results[$token], $delta);
            else
                $results[$token] = array($token, 1, $delta, $delta, $delta);
        }

        $entries = array_values($results);
        $func    = function ($a, $b) {
                return $a[4] < $b[4] ? 1 : 0;
            };
        usort($entries, $func);

        $this->render('profile-summary', $entries);
    }

    /**
     * Aggregates the report result.
     * @param array $result log result for this code block
     * @param float $delta time spent for this code block
     * @return array
     */
    protected function aggregateResult($result, $delta) {
        list($token, $calls, $min, $max, $total) = $result;
        if ($delta < $min)
            $min = $delta;
        elseif ($delta > $max)
            $max = $delta;
        $calls++;
        $total+=$delta;
        return array($token, $calls, $min, $max, $total);
    }

    /**
     * Renders the view.
     * @param string $view the view name (file name without extension). The file is assumed to be located under framework/data/views.
     * @param array $data data to be passed to the view
     */
    protected function render($view, $data) {
        include('views/db-profiler.php');
    }

}
