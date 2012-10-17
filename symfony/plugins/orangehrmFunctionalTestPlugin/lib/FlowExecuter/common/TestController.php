<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestController
 *
 * @author Faris
 */
class TestController {

    //put your code here

    public $queueObject;
    private $selenium;
    private $flowMapper;

    public function __construct($selenium, $flowData, $flowMapper) {

        $this->selenium = $selenium;
        $this->queueObject = new TestQueue();
        $this->flowMapper = $flowMapper;

        $this->addToTheQueue($flowData);
    }

    private function getFlowObjectMapped($string) {

        return $this->flowMapper->getFlowObject($string);
    }

    private function addToTheQueue($flowData) {
        //print_r($flowData['flow']);
        $flow = $flowData['flow'];
        for ($i = 0; $i < count($flow); $i++) {
            $objectTag = $flow[key($flow)];

            $mappedObject = $this->getFlowObjectMapped($objectTag);
            $mappedObject->init($flowData[key($flow)]);
            $this->queueObject->pushAction($mappedObject);
            next($flow);
        }
        //$this->queueObject->makingQueue();
        // According to the data we have to make the object
        // it will be a mapping... it will send the data as well..
        //so that data would be set inside the init() method of the specofied class..
    }

    /*
     * Test controller returns false if any of the item in the queue returns false.
     * Test cases should not be written for assertFalse as we don't know at which point the false is returned.
     * Eg: May be something went wrong, and false is returned at the login itself.
     */

    public function execute() {
        //$this->queueObject->makingQueue();
        $count = count($this->queueObject->queue);

        while (!$this->queueObject->isEmpty()) {

            $flowObject = $this->queueObject->popAction();

            if (!$flowObject->execute()) {
                return FALSE;
            }
        }
        return TRUE;
    }

}