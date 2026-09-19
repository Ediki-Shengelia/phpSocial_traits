<?php


function Redirect($location)
{
    header("Location:" . $location);
    exit;
}


function diffForHumans(string $datetime, ?string $now = null): string
{
    $timestamp = strtotime($datetime);
    $now = $now ? strtotime($now) : time();
    $diff = $now - $timestamp;
    $isFuture = $diff < 0;
    $diff = abs($diff);
    $intervals = [
        'year'   => 31536000,
        'month'  => 2592000,
        'week'   => 604800,
        'day'    => 86400,
        'hour'   => 3600,
        'minute' => 60,
        'second' => 1,
    ];
    if ($diff < 5) {
        return "Just Now";
    }
    foreach ($intervals as $label => $seconds) {
        $count = intdiv($diff, $seconds);
        if ($count >= 1) {
            $unit = $count > 1 ? $label . 's' : $label;
            return $isFuture ? "in {$count} {$unit}" : "{$count} {$unit} ago";
        }
    }
    return "Just Now";
}


function isPostRequest()
{
    return $_SERVER['REQUEST_METHOD'] === "POST";
}
