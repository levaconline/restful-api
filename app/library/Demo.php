<?php

/*
 * Just inswpwnswnt demo class
 * @author Aleksandar Todorovic
 * @version 1.0
 * @package library
 * @since 1.0
 * @todo Add more methods and properties as needed. (This is just simplified fordemo.)
 */
class Demo
{
    /**
     * Just inswpwnswnt demo method (demo to show how can be used CLI class both 
     * inside MVC or as indepencednt CLI php class.)
     * @return string
     */
    public function hiThere(): string
    {
        return "Hi There! This is just demo. (message from independent Library: '" . __CLASS__ . "')";
    }
}
