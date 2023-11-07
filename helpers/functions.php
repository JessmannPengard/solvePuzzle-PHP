<?php

function showArray($array, $title = "")
{
    echo "<h5>" . $title . "</h5>";
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
