<?php

namespace GEMA\gemaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * RolRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RolRepository extends EntityRepository {




    public function filtrar(Request $request) {
        $qb = new QueryBuilder($this->getEntityManager());
        $qb
                ->select("U")
                ->from("gemaBundle:Rol", "U");

        if ($request->request->get("searchPhrase") != null) {
            $search = $request->request->get("searchPhrase");
            $qb->where($qb->expr()->like("U.nombre", $qb->expr()->literal("%" . $search . "%")));
        }
        if ($request->request->get("sort") != null) {
            $orden = $request->request->get("sort");
            foreach ($orden as $key => $value) {
                $qb->orderBy("U." . $key, $value);
            }
        }
        return $qb;
    }

}
