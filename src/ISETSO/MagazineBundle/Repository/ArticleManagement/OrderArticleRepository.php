<?php

namespace ISETSO\MagazineBundle\Repository\ArticleManagement;

/**
 * OrderArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderArticleRepository extends \Doctrine\ORM\EntityRepository
{
    /**
    * @return array
    */
    public function getAllOrder()
    {
        return $this->createQueryBuilder('n')
                        ->select('sum(s.quantity) as quantity , a.id as article_id , a.designation as article_name , u.designation as unit ')
                        ->join('n.detail' , 's')
                        ->join('s.article' , 'a')
                        ->join('a.unit' , 'u')
                        ->groupBy('s.article')
                        ->getQuery()
                        ->getResult()
                        ;
    }

	/**
     * @return Query
     */
    public function findAll()
    {
        return $this->createQueryBuilder('f')
                    ->join('f.user', 'u');
    }

    /**
     * @param \ISETSO\UserBundle\Entity\User\User $user
     * @return Query
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('f')
                        ->join('f.user', 'u')
                        ->where('u.id = :id')
                        ->setParameter('id',$user->getId());
    }

    /**
     * @param String $field
     * @param \ISETSO\UserBundle\Entity\User\User $user
     * @return Query
     */
    public function findByAnything($query , $field)
    {
        return  $query->andWhere('f.id like :search OR f.etat LIKE :search OR f.date LIKE :search OR f.observation LIKE :search OR u.username LIKE :search')
                    ->setParameter('search', '%'.$field.'%')
                    ->orderBy('f.id', 'ASC');
    }

    /**
     * @param date $startDate
     * @param date $endDate
     * @param Query $query
     * @return Query
     */
    public function findBetween($query , $startDate , $endDate)
    {
        return  $query->andWhere('f.dateEntre BETWEEN :startDate AND :endDate')
                        ->setParameter('startDate', $startDate)
                        ->setParameter('endDate', $endDate)
                        ->orderBy('f.id', 'DESC');
    }

    /**
     * @return int
     */
    public function getTotalOrderNumber()
    {
        return  $this->createQueryBuilder('f')
                        ->select('count(f) as TotalOrderNumber')
                        ->getQuery()
                        ->getResult()[0]['TotalOrderNumber']
                        ;
        
    }

    /**
     * @return int
     */
    public function getNewOrderNumber()
    {
        return  $this->createQueryBuilder('f')
                        ->select('count(f) as NewOrderNumber')
                        ->getQuery()
                        ->getResult()[0]['NewOrderNumber'];
    }
}