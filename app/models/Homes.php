<?php

/**
 * Home controller class.
 * @author Aleksandar Todorovic
 * @version 1.0
 * @package models
 * @since 1.0
 * @todo Add more methods and properties as needed. (This is just simplified fordemo.)
 */
class Homes extends Lib
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index method of Home Model which uses independent CLI php class.
     * All data can be passed trought $this->data array and will be automatically passed to view.
     * @return void
     */
    protected function hiThere()
    {
        // Store to var to pass to view.
        $result = new Demo();

        $this->data = ['card-title' => 'Demo', 'message' => $result->hiThere()];
    }
}
