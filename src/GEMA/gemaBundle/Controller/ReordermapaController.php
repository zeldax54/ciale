<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReordermapaController extends Controller
{
    public function razasindexAction(){


        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('gemaBundle:Raza')->findAll();
        return $this->render('gemaBundle:Mapaorder:index.html.twig', array(
            'entities' => $entities,
        ));

    }

    public function mapaindexAction($id){
        $em = $this->getDoctrine()->getManager();
        $mapa=$em->getRepository('gemaBundle:Raza')->find($id)->getMapa();
        $mapadatos= $mapa->getMapadatos();
        $razas=$mapa->getRazas();
        $info='<p>Mapa usado por las razas:';
        foreach($razas as $r){
            $info.='<strong>'.$r->getNombre().'<strong>,';
        }
        $info.='</p>';
        return $this->render('gemaBundle:Mapaorder:mapa.html.twig', array(
            'mapadatos' => $mapadatos,
            'mapa'=>$mapa->getNombre(),
            'mapaid'=>$mapa->getId(),
            'info'=>$info
        ));

    }

    public  function reorderAction()
    {
        $value=$_POST["value"];
        $mapaid=$_POST["mapaid"];

        $em = $this->getDoctrine()->getManager();
            $repo= $em->getRepository('gemaBundle:MapaDatos');
            foreach($value as $mapadato){
                $entity=$repo->findOneBy(array(
                    'comentario'=>$mapadato[1],
                    'mapa'=>$mapaid
                ));
                $entity->setPosinExcel($mapadato[0]);
                $em->persist($entity);
            }

        $em->flush();
        return new JsonResponse(array(
            0=>'yes'
        ));
    }


    public function editAction($id,$nombre){

        $em = $this->getDoctrine()->getManager();
        $mapadato=$em->getRepository('gemaBundle:MapaDatos')->find($id);
        $mapadato->setComentario($nombre);
        $em->persist($mapadato);
        $em->flush();
        return new JsonResponse(array(
            0=>'yes',
            1=>$id,
            2=>$nombre
        ));

    }

}
