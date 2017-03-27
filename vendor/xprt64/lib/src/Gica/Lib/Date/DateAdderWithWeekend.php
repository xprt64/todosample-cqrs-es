<?php
/******************************************************************************
 * Copyright (c) 2016 Constantin Galbenu <gica.galbenu@gmail.com>             *
 ******************************************************************************/

namespace Gica\Lib\Date;


class DateAdderWithWeekend
{
    public function add(\DateTimeImmutable $startDate, \DateInterval $interval)
    {
        $endDate = $this->jump($startDate, $interval);

        if ($this->isWeekend($endDate)) {
            $endDate = $this->jumpOverWeekend($endDate);
        }
        return $endDate;
    }

    private function jump(\DateTimeImmutable $startDate, \DateInterval $interval):\DateTimeImmutable
    {
        return $startDate->add($interval);
    }

    private function isWeekend(\DateTimeImmutable $date):bool
    {
        $dayOfWeek = $date->format('N');
        return ($dayOfWeek >= 6);
    }

    private function jumpOverWeekend(\DateTimeImmutable $date):\DateTimeImmutable
    {
        return $date->add(new \DateInterval('P2D'));
    }
}