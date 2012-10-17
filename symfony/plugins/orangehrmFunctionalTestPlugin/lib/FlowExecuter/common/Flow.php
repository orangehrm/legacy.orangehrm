<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of flow
 *
 * @author Faris
 */
abstract class Flow {

    abstract public function execute($verify);

    //abstract public function verify();
    abstract public function init($dataArray);

    abstract public function verify();
}