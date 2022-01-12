<?php

namespace App\Detec;

class Detection
{

    public function Detec(int $amount)
    {
        if ($amount >= 100) return true;
        else return false;
    }
}
