<?php

namespace App\Repositories\Add;

interface AddRepositoryInterface
{
    public function getDayInfo($monthId, $day);
    public function updateData($day);
}