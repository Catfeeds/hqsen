<?php
    $source_file = $_GET['file'];
    $make_file = $_GET['host_root'] . '/doc/API.html';
    system('/usr/local/bin/markdown_py -o html4 ' . $source_file . ' > ' . $make_file);
    include $make_file;
?>
