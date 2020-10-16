<?php
namespace Kameleoon;

interface KameleoonClient
{
    public function addData($userID, ...$data);
    public function flush($userID = NULL);
    public function trackConversion($userID, $goalID, $revenue = 0.0);
    public function triggerExperiment($userID, $experimentID, $timeOut = 2000);
    public function obtainVisitorCode($topLevelDomain, $visitorCode = NULL);
}

?>
