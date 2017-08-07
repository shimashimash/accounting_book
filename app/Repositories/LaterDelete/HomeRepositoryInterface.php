<?php

namespace App\Repositories\Home;

interface HomeRepositoryInterface
{
    public function getMonth($year, $month);
    public function createMonth($year, $month);
    public function getDays($monthFromDB);
    public function getDate($year, $month);
    public function getTotalOfDays($days);
    public function update($attributes);
}