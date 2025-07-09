<?php

/**
 * Library class extending database functionality with utility methods.
 * 
 * Provides helper methods for string manipulation and email notifications
 * for RESTful API applications. Inherits database connection and CRUD
 * operations from the Db class.
 * 
 * @package core
 * @author Aleksandar Todorovic
 * @version 1.0
 * @since 1.0
 */ class Lib extends Db
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Convert string with - or/and _ to cammel case.
     * Usuallu for generating class names.
     * @param string $string
     * @return string
     */
    protected function toCamelCase(string $string): string
    {
        $string = str_replace(
            ' ',
            '',
            ucwords(
                str_replace(
                    ['_', '-'],
                    ' ',
                    $string
                )
            )
        );

        return $string;
    }

    /**
     * Send spec mail to user or to yourself.
     * Replace noreplay@your-domain.com with your email address.
     * Replace: email-by-your-choce@gmail.com with your email address.
     * 
     * @param string $msg
     * @return void
     */
    public function sendSpecMail(string $msg = "")
    {
        $from = 'noreplay@your-domain.com';
        $replay = 'noreplay@your-domain.com';
        $to = 'email-by-your-choce@gmail.com';
        $subject = 'Restfull API - no-framework';

        $headers = "From: " . $from . "\r\nReply-To: " . $replay . "\r\nX-Mailer: PHP/" . phpversion();
        if (!mail($to, $subject, $msg, $headers)) {
            $errorMessage = error_get_last()['message'];
            // TODO: Log message.
        }
    }
}
