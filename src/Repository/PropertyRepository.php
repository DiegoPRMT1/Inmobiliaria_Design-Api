<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 *
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function add(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Property $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Property[] Returns an array of Property objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findAllHouses()
    {
        return $this->createQueryBuilder('p')
            ->join('p.realState', 'r')
            ->where('r.name = :type')
            ->setParameter('type', 'casa')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }
    public function findAllApartments()
    {
        return $this->createQueryBuilder('p')
            ->join('p.realState', 'r')
            ->where('r.name = :type')
            ->setParameter('type', 'piso')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    public function findAllVilles()
    {
        return $this->createQueryBuilder('p')
            ->join('p.realState', 'r')
            ->where('r.name = :type')
            ->setParameter('type', 'villa')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function searchPost ( string $titleparameter )
    {
        return $this->createQueryBuilder( 'p' )
            ->where( 'p.name LIKE :titleparameter ' )
            ->orderBy( 'p.name', 'DESC' )
            ->setParameter( 'titleparameter', '%' . $titleparameter . '%' )
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findPropertiesByAreaAndRealEstate(string $area, string $realEstate)
    {
        return $this->createQueryBuilder('p')
            ->join('p.realState', 'rs')
            ->where('p.area = :area')
            ->andWhere('rs.name = :realEstate')
            ->orderBy('p.name', 'DESC')
            ->setParameter('area', $area)
            ->setParameter('realEstate', $realEstate)
            ->getQuery()
            ->getResult();
    }

    public function findPropertiesByRealEstate(string $realEstate)
    {
        return $this->createQueryBuilder('p')
            ->join('p.realState', 'rs')
            ->where('rs.name = :realEstate')
            ->orderBy('p.name', 'DESC')
            ->setParameter('realEstate', $realEstate)
            ->getQuery()
            ->getResult();
    }

    public function findPropertiesByAreaAndRealEstate1(string $area, string $realEstate)
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->join('p.realState', 'rs')
            ->orderBy('p.name', 'DESC')
            ->setParameter('realEstate', $realEstate);

        if ($area !== 'default') {
            $queryBuilder
                ->andWhere('p.area = :area')
                ->setParameter('area', $area);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function filterProperty($price, $zone, $bedrooms){
        $qb = $this->createQueryBuilder('p');

        // Agrega condiciones según los filtros proporcionados
        if ($price !== null) {
            $qb->andWhere('p.price <= :price')
                ->setParameter('price', $price);
        }

        if ($zone !== null) {
            if ($zone !== "null"){
                $qb->andWhere('p.area = :zone')
                    ->setParameter('zone', $zone);
            }
        }

        if ($bedrooms !== null) {
            if ($bedrooms !== 10000) {
                $qb->andWhere('p.bedrooms = :bedrooms')
                    ->setParameter('bedrooms', $bedrooms);
            }
        }

        $properties =  $qb->getQuery()->getResult();
        // Modifica el resultado para que sea un arreglo de objetos con el atributo propertyId
        $filter = [];
        foreach ($properties as $property) {
            $filter[] = ['propertyId' => $property->getId()];
        }

        return $filter;
    }

    public function findById($id)
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }





}
