<?php
namespace App\EventListener;

use App\Entity\Activite;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class ActiviteSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::prePersist,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->updateQuantite($args);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateDateActivite($args);
    }

    public function updateQuantite(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        if ($entity instanceof Activite) {
            if($entity->getTypeActivite()==='A') {
                $entity->getProduit()->addQteRestante($entity->getQuantite());
            }

            if($entity->getTypeActivite()==='V'){
                $entity->getProduit()->subQteRestante($entity->getQuantite());
            }

            $entityManager->flush();

        }
    }

    public function updateDateActivite(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Activite) {

            $entity->setDateAtivite(new \DateTime());

        }
    }
}