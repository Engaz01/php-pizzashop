<?php

namespace App\Views\Tables\User;

use App\App;
use Core\Views\Table;

class OrderTable extends Table
{
    public function __construct()
    {
        $rows = App::$db->getRowsWhere('orders', ['email' => $_SESSION['email']]);

        foreach ($rows as $id => &$row) {
            $timeStamp = date('Y-m-d H:i:s', $row['timestamp']);
            $difference = abs(strtotime("now") - strtotime($timeStamp));

            $days = floor($difference / (3600*24));
            $hours = floor($difference / 3600);
            $minutes = floor(($difference - ($hours*3600)) / 60);
            $seconds = floor($difference % 60);

            if ($days){
                $hours = $hours - 24;
                $result = "{$days}d {$hours}:{$minutes} H";
            } elseif ($minutes) {
                $result = "{$minutes} min";
            } elseif ($hours) {
                $result = "{$hours}:{$minutes} H";
            } else {
                $result ="{$seconds} seconds";
            }

            $row['timestamp'] = $result;

            unset($row['email']);
        }

        parent::__construct([
            'headers' => [
                'ID',
                'Status',
                'Pizza name',
                'Time Ago'
            ],
            'rows' => $rows
        ]);
    }
}