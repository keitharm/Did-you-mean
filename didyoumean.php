<?php

// 80 percent is a pretty good number. Don't make it any lower because it'll risk
// revealing a directory that you don't want visitors seeing.

// How much percent similarity does there have to be in order for a match to be given?
$threshold = 80;

function didyoumean($input) {
    
    global $threshold;
    
    // If user's url is something like /directory///////// for some reason, this will remove the last slashes
    while ($input[strlen($input)-1] == "/") {
        $input = substr($input, 0, strlen($input)-1);
    }
    
    // If the user's url had $_GET data in it, this will add it back to the didyoumean url.
    if (strpos($input, "?") == true) {
        $get = substr($input, strpos($input, "?"));
        $input = substr($input, 0, strpos($input, "?"));
    }
    
    // Gets rid of slash at beginning of $input
    $input = substr($input, 1);
    
    // Retrieves indexed file and converts it into array
    $file = file_get_contents("index");
    $files = explode(",", $file);
    $shortest = -1;
    
    // Loop through words to find the closest
    foreach ($files as $file) {
    
        // calculate the distance between the input word,
        // and the current word
        $lev = levenshtein($input, $file);
    
        // check for an exact match
        if ($lev == 0) {
    
            // closest word is this one (exact match)
            $closest = $file;
            $shortest = 0;
    
            // break out of the loop; we've found an exact match
            break;
        }
    
        // if this distance is less than the next found shortest
        // distance, OR if a next shortest word has not yet been found
        if ($lev <= $shortest || $shortest < 0) {
            // set the closest match, and shortest distance
            $closest  = $file;
            $shortest = $lev;
        }
    }
    
    // Stores the directories and sub directores of the user's url into an array
    $user_explode = explode("/", $input);
    
    // Stores the directories and sub directors of the closest matching result into an array.
    $closest_explode = explode("/", $closest);
    
    // Gets rid of the 1st elements since it is blank
    array_shift($closest_explode);
    
    // If the number of directories in the match doesn't equal
    // the number of directories in the user's url, they are
    // obviously different URLs so we return false preventing
    // the user from seeing the closest URL match.
    if (count($user_explode) != count($closest_explode)) {
        return false;
        die;
    } else {
        // Does a similar_text check with the threshold to see how similar
        // the directories and subdirectories are with the closest match
        $percent = array();
        for ($a=0; $a<count($user_explode); $a++) {
            similar_text($user_explode[$a], $closest_explode[$a], $percent[$a]);
        }
        $percent = array_reverse($percent);
        foreach ($percent as $per) {
            if ($per < $threshold) {
                // Don't show match if threshold is not met
                return false;
            }
        }
        return "http://" . $_SERVER['SERVER_NAME'] . $closest . $get;
    }
}
?>