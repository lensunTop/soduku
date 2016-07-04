<?php
require_once './soduku/printTables.php';
require_once './soduku/SetGridValue.php';
require_once './soduku/soduku.php';

$soduku = new Soduku(3);
try {
    /*$soduku->setValue(0, 0, 2, 7);
    $soduku->setValue(0, 1, 0, 3);
    $soduku->setValue(0, 2, 1, 5);
    $soduku->setValue(1, 0, 0, 5);
    $soduku->setValue(1, 0, 2, 8);
    $soduku->setValue(1, 1, 1, 4);
    $soduku->setValue(1, 2, 0, 3);
    $soduku->setValue(1, 2, 1, 9);
    $soduku->setValue(2, 1, 0, 6);
    $soduku->setValue(3, 0, 0, 2);
    $soduku->setValue(3, 0, 1, 8);
    $soduku->setValue(3, 1, 1, 6);
    $soduku->setValue(3, 2, 1, 3);
    $soduku->setValue(3, 2, 2, 5);
    $soduku->setValue(4, 1, 0, 7);
    $soduku->setValue(4, 1, 2, 5);
    $soduku->setValue(5, 0, 0, 5);
    $soduku->setValue(5, 0, 1, 4);
    $soduku->setValue(5, 1, 1, 8);
    $soduku->setValue(5, 2, 1, 1);
    $soduku->setValue(5, 2, 2, 6);
    $soduku->setValue(6, 1, 2, 3);
    $soduku->setValue(7, 0, 1, 7);
    $soduku->setValue(7, 0, 2, 4);
    $soduku->setValue(7, 1, 1, 1);
    $soduku->setValue(7, 2, 0, 6);
    $soduku->setValue(7, 2, 2, 3);
    $soduku->setValue(8, 0, 1, 3);
    $soduku->setValue(8, 1, 2, 4);
    $soduku->setValue(8, 2, 0, 1);*/

    $soduku->setValue(0, 0, 1, 7);
    $soduku->setValue(0, 2, 1, 1);

    $soduku->setValue(1, 0, 1, 5);
    $soduku->setValue(1, 0, 2, 3);
    $soduku->setValue(1, 2, 0, 8);
    $soduku->setValue(1, 2, 1, 2);

    $soduku->setValue(2, 1, 0, 7);
    $soduku->setValue(2, 1, 2, 4);

    $soduku->setValue(3, 0, 0, 4);
    $soduku->setValue(3, 1, 0, 5);
    $soduku->setValue(3, 1, 2, 1);
    $soduku->setValue(3, 2, 2, 9);

    $soduku->setValue(4, 1, 1, 8);

    $soduku->setValue(5, 0, 0, 2);
    $soduku->setValue(5, 1, 0, 6);
    $soduku->setValue(5, 1, 2, 3);
    $soduku->setValue(5, 2, 2, 7);

    $soduku->setValue(6, 1, 0, 3);
    $soduku->setValue(6, 1, 2, 2);

    $soduku->setValue(7, 0, 1, 9);
    $soduku->setValue(7, 0, 2, 4);
    $soduku->setValue(7, 2, 0, 2);
    $soduku->setValue(7, 2, 1, 3);

    $soduku->setValue(8, 0, 1, 7);
    $soduku->setValue(8, 2, 1, 9);
    $soduku->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
