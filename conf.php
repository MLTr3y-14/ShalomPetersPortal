<?php

/*
 * B Configuration information is stored here.
 * deally you should read these vaules from an
 * xternal properties file.
 */

class AAConf {

    private $databaseURL = "XpertProCombined";
    private $databaseUName = "bloosom1";
    private $databasePWord = "Bloosomhill1#";
    private $databaseName = "blossomhill";

    function get_databaseURL() {
        return $this->databaseURL;
    }

    function get_databaseUName() {
        return $this->databaseUName;
    }

    function get_databasePWord() {
        return $this->databasePWord;
    }

    function get_databaseName() {
        return $this->databaseName;
    }

}
?>