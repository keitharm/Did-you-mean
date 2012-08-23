<?php
# Base Directory constant. Change this to your server's configuration.
define(BASE_DIR, "public_html");

// Gets the path for the base directory
$base_directory = substr(getcwd(), 0, strpos(getcwd(), BASE_DIR)+strlen(BASE_DIR));

$files = directoryToArray($base_directory);

// This is a custom function that I found online that
// converts a directory into an array.
// We will use it to convert the entire website into
// an easy index file.
function directoryToArray($directory, $recursive = true, $listDirs = true, $listFiles = true, $exclude = '') {
        $arrayItems = array();
        $skipByExclude = false;
        $handle = opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
            preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
            if ($exclude){
                preg_match($exclude, $file, $skipByExclude);
            }
            if (!$skip && !$skipByExclude) {
                if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
                    if ($recursive) {
                        $arrayItems = array_merge($arrayItems, directoryToArray($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
                    }
                    if ($listDirs){
                        $file = $directory . DIRECTORY_SEPARATOR . $file;
                        $arrayItems[] = $file;
                    }
                } else {
                    if ($listFiles){
                        $file = $directory . DIRECTORY_SEPARATOR . $file;
                        $arrayItems[] = $file;
                    }
                }
            }
        }
        closedir($handle);
        }
        return $arrayItems;
}

// Gets rid of the base path from all of our results
// to decrease file size and increase efficiency for 404 page.
foreach ($files as $file) {
        $file = str_replace($base_directory, "", $file);
        $new .= $file . ",";
}

// Writes to a file called index
$myFile = "index";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $new);
fclose($fh);
?>