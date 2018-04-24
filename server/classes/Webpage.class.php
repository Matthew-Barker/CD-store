<?php

/**
 * Created by PhpStorm.
 * User: w14027233
 * Date: 04/10/2017
 * Time: 09:29
 */

/*
 *
 * addToBody
 * a method called 'addToBody' that will add text to the body attribute of the webpage.  
 *
 * getPage
 * a getPage method which has as a return value the various sections, head, body and footer, of the webpage concatenated together.
 *
 */

class Webpage {

    protected $head;
    protected $body;
    protected $footer;
    public $title;
    public $css;
    public $context;

    /** Methods:
     * A constructor
     * The constructor creates the head section and footer section and gives a default value for the body section
     */

    public function __construct($title = null, array $css = null) {
        $this->head = $this->makeHeader($title, $css);
        $this->footer = $this->makeFooter();

    }

    protected function makeHeader ($title, $css) {
        $cssFileList = '';
        if (is_array($css)) {
            foreach ($css as $filename) {
                $cssFileList .= "<link rel='stylesheet' href='$filename' />";
            }
        }
        $head = <<<HEAD
<!DOCTYPE HTML>
<html>
<head>
    $cssFileList
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title>$title</title>
</head>
<body>
HEAD;
        return $head;

    }

    protected function makeFooter () {
        $footer= <<<FOOTER
</body>
<footer>
</footer>
</html>

FOOTER;

        return $footer;

    }

    /** addToBody
    * a method called 'addToBody' that will add text to the body attribute of the webpage. 
    */

    public function addToBody ($text) {
       $this->body .= $text;
    }

    /** getPage
    * a getPage method which has as a return value the various sections, head, body and footer, of the webpage concatenated together.
    */

    public function getPage () {
        return $this->head . $this->body . $this->footer;
    }
}

