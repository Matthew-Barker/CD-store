<?php

/**
 * Created by PhpStorm.
 * User: w14027233
 * Date: 04/10/2017
 * Time: 09:29
 */

/**
 *
 * This is a skeleton class for a Webpage class.  We have given you the method names
 * and a very brief comment on what those methods need to do.  You will need to implement the class yourself.
 *
 * Also, included with this file is a file that is a 'client' or 'user' of the class.
 * If you look at the way the client is using the class it'll give you some more clues about how
 * you might need to implement the methods that the client calls.
 *
 * addToBody
 * a method called 'addToBody' that will add text to the body attribute of the webpage.  See the client to see how this method
 * will be used - it'll give you a clue as to how to implement it.
 *
 * getPage
 * a getPage method which has as a return value the various sections, head, body and footer, of the webpage concatenated together.
 *
 * Consider carefully the scope of all attributes and methods/functions.  Remember to make scope as restrictive
 * as possible while still being consonant with the class working.
 *
 */

class Webpage {
    /**
     * The class should be called: Webpage
     * you will need attributes to hold at least the main sections of a page: the head, body and footer
     */

    protected $head;
    protected $body;
    protected $footer;
    public $title;
    public $css;
    public $context;

    /** Methods:
     * A constructor
     * The constructor for the class should accept at least two arguments: the title of the page, and an array of
     * css filenames that it will use to create the appropriate code in the head section to link to those stylesheets
     * The constructor should create the head section and footer section and give a default value for the body section
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
    * a method called 'addToBody' that will add text to the body attribute of the webpage.  See the client to see how this method
    * will be used - it'll give you a clue as to how to implement it.
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

