<?php
    $source_file = $_GET['file'];
    $make_file_name = '/doc/' . md5($source_file) . '.html';
//    $make_file = $_GET['host_root'] . '/doc/API.html';
    $make_file = $_GET['host_root'] . $make_file_name;
    system('/usr/local/bin/markdown_py -o html4 ' . $source_file . ' > ' . $make_file);
    include $make_file;
?>
