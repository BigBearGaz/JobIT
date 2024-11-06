<?php

// src/MessageHandler/DeleteExpiredOffersHandler.php

namespace App\MessageHandler;

use App\Message\DeleteExpiredOffersMessage;
use App\Repository\OfferRepository;
use App\Repository\OffreRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Doctrine\ORM\EntityManagerInterface;

#[AsMessageHandler]
class DeleteExpiredOffersHandler
{
    public function __construct(
        private OffreRepository $offerRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(DeleteExpiredOffersMessage $message)
    {
        $thirtyDaysAgo = new \DateTime('-30 days');
        $expiredOffers = $this->offerRepository->findOffersOlderThan($thirtyDaysAgo);

        foreach ($expiredOffers as $offer) {
            $this->entityManager->remove($offer);
        }

        $this->entityManager->flush();
    }
}