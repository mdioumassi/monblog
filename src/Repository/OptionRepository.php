<?php

namespace App\Repository;

use App\Entity\Option;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Option>
 */
class OptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Option::class);
    }

    /**
     * @return Option[]
     */
    public function findAllForTwig() : array
    {
        return $this->createQueryBuilder('o', 'o.name')
            ->getQuery()
            ->getResult();
    }

    public function getValue(string $name) : string
    {
        try {
            return $this->createQueryBuilder('o')
                ->select('o.value')
                ->where('o.name = :name')
                ->setParameter('name', $name)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return '';
        }
    }
}
