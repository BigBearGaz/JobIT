<?php 

namespace App\Scheduler;

use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Scheduler\RecurringMessage;
use App\Message\YourTask;
use App\MessageHandler\DeleteExpiredOffersHandler;

#[AsSchedule('default')]
class MainSchedule implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(RecurringMessage::every('5 seconds', new DeleteExpiredOffersHandler()));
    }
}
