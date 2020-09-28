<?php

// @codingStandardsIgnoreFile

interface KameleoonClient
{
    public function addData($userID, ...$data);
    public function flush($userID);
    public function trackConversion($userID, $goalID, $revenue);
    public function triggerExperiment($userID, $experimentID, $timeOut);
    public function obtainVisitorCode($topLevelDomain, $visitorCode = NULL);
}

