<?php

/**
 * Home controller class.
 * @author Aleksandar Todorovic
 * @version 1.0
 * @package controllers
 * @since 1.0
 * @todo Add more methods and properties as needed. (This is just simplified fordemo.)
 */
class Home extends Homes
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Index method of Home controller.
     * @return void
     */
    public function index()
    {

        // array $this->data may be filled in controller or in model.
        // array $this->data will be passed to view automatically.
        $this->hiThere();
    }
}
