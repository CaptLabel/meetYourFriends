<?php

namespace CommonBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * StayRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StayRepository extends EntityRepository
{
    public function myFindStay($keysecure)
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->leftJoin('a.user', 'user')
            ->addSelect('a.dateArrival')
            ->addSelect('a.dateDeparture')
            ->addSelect('a.city')
            ->addSelect('a.id')
            ->where('user.key_secure = :keysecure')
            ->orderBy('a.dateArrival', 'ASC')
            ->setParameter('keysecure', $keysecure);
        return $qb
            ->getQuery()
            ->getArrayResult();
    }
    public function myFindStayById($keysecure, $id)
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->leftJoin('a.user', 'user')
            ->addSelect('user')
            ->where('user.key_secure = :keysecure')
            ->andWhere('a.id = :id')
            ->setParameter('keysecure', $keysecure)
            ->setParameter('id', $id);
        return $qb
            ->getQuery()
            ->getSingleResult();
    }
    public function findDoubleStay($date_arr, $date_dep, $keysecure, $id){
        $qb = $this->createQueryBuilder('a');
        $qb
            ->leftJoin('a.user', 'user')
            ->addSelect('user')
            ->where('user.key_secure = :keysecure')
            ->andWhere('a.id != :id')
            ->andWhere('(a.dateDeparture BETWEEN :date_arr and :date_dep) OR (a.dateArrival BETWEEN :date_arr and :date_dep)')
            ->setParameter('keysecure', $keysecure)
            ->setParameter('date_arr', $date_arr)
            ->setParameter('id', $id)
            ->setParameter('date_dep', $date_dep);
        return $qb
            ->getQuery()
            ->getArrayResult();
    }
    public function findMatch($date_arr, $date_dep, $city, $keysecure)
    {
        $connection = $this->_em->getConnection();
        $sql = "SELECT s.date_arrival, s.date_departure, s.city, um.name, um.key_avatar  FROM `stay` s, `user` u, `user_user` uu, `user` um WHERE u.key_secure = ? AND u.id = uu.user_source AND uu.user_target = s.user_id AND u.id != s.user_id AND s.city = ? AND ((s.date_arrival BETWEEN ? AND ?) OR (s.date_departure BETWEEN ? AND ?)) AND s.user_id = um.id";
        $statement = $connection->prepare($sql);
        $statement->bindValue(1, $keysecure);
        $statement->bindValue(2, $city);
        $statement->bindValue(3, $date_arr);
        $statement->bindValue(4, $date_dep);
        $statement->bindValue(5, $date_arr);
        $statement->bindValue(6, $date_dep);
        $statement->execute();
        return $statement->fetchAll();
    }
}
