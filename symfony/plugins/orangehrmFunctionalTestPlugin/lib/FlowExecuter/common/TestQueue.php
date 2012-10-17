<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestQueue
 *
 * @author Faris
 */
class TestQueue {

    //put your code here

    public $queue = array();

    //pushes the flow objects into stack
    public function pushAction($flowObject) {
        //array_push($this->queue, $flowObject);

        $this->queue[] = $flowObject;
    }

    //pops flowobjects out of the queue
    public function popAction() {

        $flowObject = array_shift($this->queue);
        return $flowObject;
    }

    public function isEmpty() {
        if (count($this->queue) == 0) {
            return true;
        }
        else
            return false;
    }

}