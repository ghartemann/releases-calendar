<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class AbstractManager
{
    public EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function getClassRepository(string $className = null): ObjectRepository
    {
        if (empty($this->em)) {
            throw new \RuntimeException('No entity manager provided.');
        }

        if (!$className) {
            $className = $this->getClassDependency();
        }

        return $this->em->getRepository('App\Entity\\' . $className);
    }

    private function getClassDependency(): string
    {
        $className = substr(get_class($this), strrpos(get_class($this), '\\') + 1);
        return str_replace('Manager', '', $className);
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->em;
    }
}
