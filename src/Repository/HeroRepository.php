<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Hero;
use ContainerOKzUwnq\getCompanyRepositoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Middleware\Debug\Query;

/**
 * @extends ServiceEntityRepository<Hero>
 *
 * @method Hero|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hero|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hero[]    findAll()
 * @method Hero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeroRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hero::class);
    }

    public function save(Hero $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Hero $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function createPagerQueryBuilder(
        ManagerRegistry $doctrine
    )
    {
        $dql = "SELECT a FROM App:Company a";

        $q = $doctrine->getManager()->createQuery($dql);
        return $q;
    }

    public function searchPagerQueryBuilder(ManagerRegistry $doctrine, $search)
    {
        $dql = "SELECT company FROM App:Company company 
                JOIN company.hero hero 
                WHERE (company.title like :search 
                OR company.area like :search
                OR company.city like :search
                OR company.region like :search
                OR company.country like :search
                OR hero.name like :search
                OR hero.age like :search
                OR hero.wikiLink like :search
                )";
        return $doctrine
            ->getManager()
            ->createQuery($dql)
            ->setParameter('search', "%$search%");

    }
}
