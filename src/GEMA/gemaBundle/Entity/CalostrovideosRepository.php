<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * CalostrovideosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CalostrovideosRepository extends EntityRepository
{
    public function listarDQL() {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->addSelect('U')
            ->from($this->getClassName(), 'U');
        return $qb;
    }

    public function filtrar(Request $request) {
        $qb = new QueryBuilder($this->getEntityManager());
        $qb
            ->select("B")
            ->from($this->getClassName(), "B");


        if ($request->request->get("searchPhrase") != null) {
            $search = $request->request->get("searchPhrase");
            $qb->where($qb->expr()->like("B.titulo", $qb->expr()->literal("%" . $search . "%")));

        }
        if ($request->request->get("sort") != null) {
            $orden = $request->request->get("sort");
            foreach ($orden as $key => $value) {
                $qb->orderBy("B." . $key, $value);
            }
        }
        return $qb;
    }

    public function OrderedbyDesc(){

        $qb = new QueryBuilder($this->getEntityManager());
        $qb
            ->select("F")
            ->from($this->getClassName(), "F")       
            ->where('F.section is NULL')
            ->orWhere("F.section=''")
            ->orWhere("F.section='CALOSTRO'") 
            ->orderBy("F.id", 'DESC');
            return $qb->getQuery()->getResult();
    }
    public function BeefcompOrderedbyDesc(){

        $qb = new QueryBuilder($this->getEntityManager());
        $qb
            ->select("F")
            ->from($this->getClassName(), "F")       
            ->Where("F.section='BEEFCOMP'") 
            ->orderBy("F.id", 'DESC');
            return $qb->getQuery()->getResult();
    }
}
