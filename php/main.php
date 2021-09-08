<?php



function validateX($inp)
{
    if (!isset($inp)) return false;

    $X_MIN = -3;
    $X_MAX = 5;

    $x_num = str_replace(",", ".", $inp);
    return is_numeric($x_num) && $X_MIN < $x_num && $x_num < $X_MAX && strlen($inp) <= 15;
}

function validateY($inp) {
    return isset($inp) && is_numeric($inp) && $inp >= -2 && $inp <=2 && $inp * 2 == intval($inp * 2); // protection from changing value attr
}

function validateR($inp) {
    return isset($inp) && is_numeric($inp) &&  $inp >= 1 && $inp <=3 && $inp * 2 == intval($inp * 2); // protection from changing value attr
}

function validateTimezone($inp) {
    return isset($inp) && is_numeric($inp) && abs($inp) <= 24 * 60;
}

function isSquareHit($x, $y, $r) {
    return ($x < 0 && $y > 0 && $x > -$r && $y < $r);
}

function isTriangleHit($x, $y, $r) {
    $hypotenuse = -1/2 * $x - $r/2;
    return ($x < 0 && $y < 0 && $y > $hypotenuse);

}

function isCircleHit($x, $y, $r)
{
    $isInsideCircle = pow($x, 2) + pow($y, 2) < pow($r, 2);
    return ($x > 0 && $y < 0 && $isInsideCircle);
}

function isBlueAreaHit($x, $y, $r) {
    return isCircleHit($x, $y, $r) || isTriangleHit($x, $y, $r) || isSquareHit($x, $y, $r);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$x = $_GET['x'];
$y = $_GET['y'];
$r = $_GET['r'];
$timezone = $_GET['timezone'];



    $isValid = validateR($r) && validateX($x) && validateY($y) && validateTimezone($timezone);
    $isBlueAreaHit = NULL;
    $userTime = NULL;
    $timePassed = NULL;
    if ($isValid) {
        $isBlueAreaHit = isBlueAreaHit($x, $y, $r);
        $userTime = @date('H:i:s', time() - $timezone * 60);
        $timePassed = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 4);
    }
    $response = array(
        "isValid" => $isValid,
        "isBlueAreaHit" => $isBlueAreaHit,
        "userTime" => $userTime,
        "execTime" => $timePassed,
        "x" => $x,
        "y" => $y,
        "r" => $r);
echo json_encode($response);