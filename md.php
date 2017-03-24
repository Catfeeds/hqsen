<?php
    $source_file = $_GET['file'];
    $make_file_name = '/cache/' . 'md_to_html' . '.html';
    $make_file = $_GET['host_root'] . $make_file_name;
    system('/usr/local/bin/markdown_py -o html4 ' . $source_file . ' > ' . $make_file);
    include $make_file;
?>
