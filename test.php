<?php
    $line_count = 0;
    if ($handle = opendir('../transport/')) {
        while (false !== ($entry = readdir($handle))) {
            if (is_file($entry)) {
                $line_count += count(file($entry));
            }
        }
        closedir($handle);
    }
    
    var_dump($line_count);
?>