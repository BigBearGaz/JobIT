<?php

// src/Scheduler/OfferCleanupSchedule.php

namespace App\Scheduler;

use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Scheduler\RecurringMessage;
use App\Message\DeleteExpiredOffersMessage;

#[AsSchedule('offer_cleanup')]
class OfferCleanupSchedule implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(RecurringMessage::daily(new DeleteExpiredOffersMessage()));
    }
}