<?php

namespace ISETSO\MagazineBundle\Repository\ArticleManagement;

use ISETSO\MagazineBundle\Entity\Magazine\MagazineDischarge;
use ISETSO\MagazineBundle\Entity\ArticleManagement\ReturnArticleToSubStore;
/**
 * DischargeArticleToMagazineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DischargeArticleToMagazineRepository extends \Doctrine\ORM\EntityRepository
{

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
     * @param Query $query
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
     * @param int $id
     * @return Query
     */
    public function getStoreArticleBy($id)
    {
        return $this->createQueryBuilder('a')
                        ->select('sum(d.quantity) as quantity , ar.id as article_id , ar.designation as article_name , u.designation as unit, sf.designation as subFamily , fa.designation as family ')
                        ->join('a.toMagazine', 'm')
                        ->join('a.detail' , 'd')
                        ->join('d.supportingDocument' , 's')
                        ->join('s.article' , 'ar')
                        ->join('ar.subFamily','sf')
                        ->join('sf.family','fa')
                        ->join('ar.unit' , 'u')
                        ->groupBy('s.article')
                        ->where('a.etat = :etat')
                        ->setParameter('etat', "a")
                        ->andWhere('m.id = :id')
                        ->setParameter('id',$id)
                        ->getQuery()
                        ->getResult()
                        ;
    }

    /**
     * @param int $id Magasin Id
     * @return Query
     */
    public function getStoreArticleBySupportingDoc($id , $supportingDocument)
    {
        return $this->createQueryBuilder('a')
                        ->select('sum(d.quantity) as quantity')
                        ->join('a.toMagazine', 'm')
                        ->join('a.detail' , 'd')
                        ->join('d.supportingDocument' , 's')
                        ->groupBy('d.supportingDocument')
                        ->where('a.etat = :etat')
                        ->setParameter('etat', "a")
                        ->andWhere('m.id = :id')
                        ->setParameter('id',$id)
                        ->andWhere('s.id = :supportingDocument')
                        ->setParameter('supportingDocument',$supportingDocument)
                        ->getQuery()
                        ->getOneOrNullResult()
                        ;
    }

    /**
     * @param int $id Magasin Id
     * @param int $article_id Article Id
     * @return Query
     */
    public function getStoreArticleByArticleId($id , $article_id)
    {
        return $this->createQueryBuilder('j')
                        ->select('s.price ,sum(d.quantity) as quantity , s.inventoryNumber as inventoryNumber, s.id as sd_id, a.id as article_id, a.designation as article')
                        ->join('j.toMagazine', 'm')
                        ->join('j.detail' , 'd')
                        ->join('d.supportingDocument' , 's')
                        ->join('s.article' , 'a')
                        ->join('a.subFamily','sf')
                        ->join('sf.family','fa')
                        ->join('a.unit','u')
                        ->groupBy('d.supportingDocument')
                        ->where('j.etat = :etat')
                        ->setParameter('etat', "a")

                        ->andWhere('m.id = :id')
                        ->setParameter('id',$id)

                        ->andWhere('a.id = :article_id')
                        ->setParameter('article_id',$article_id)
                       
                        ->getQuery()
                        ->getResult()
                        ;
    }

}