<?php

namespace GEMA\gemaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GEMA\gemaBundle\Entity\Usuario;
use GEMA\gemaBundle\Form\UsuarioType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Usuario controller.
 *
 */
class UsuarioController extends Controller {

    /**
     * Lists all Usuario entities.
     *
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $entities = array();
        if ($request->isXmlHttpRequest()) {
            $repo = $em->getRepository('gemaBundle:Usuario');
            $qb = $repo->filtrar($request);
            $result = $this->get("gema.utiles")->paginar($request->get("current"), $request->get("rowCount"), $qb->getQuery());
            return new JsonResponse($result);
        } else {
            $entities = $em->getRepository('gemaBundle:Usuario')->findAll();
        }
        $accion = 'Listar Usuarios del Sistema';
        $this->get("gema.utiles")->traza($accion);
        return $this->render('gemaBundle:Usuario:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Usuario entity.
     *
     */
    public function createAction(Request $request) {

        $entity = new Usuario();
        $form = $this->createCreateForm($entity);

            $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $existe = $em->getRepository("gemaBundle:Usuario")->findOneByUsuario($entity->getUsername());

            if ($existe) {
                $form->get("usuario")->addError(new FormError("Seleccione otro usuario."));
            } else {
                $entity->setSalt(md5(time()));
                $encoder = $this->get('security.encoder_factory')
                        ->getEncoder($entity);
                $password = $encoder->encodePassword(
                        $entity->getPassword(), $entity->getSalt()
                );

                $entity->setPassword($password);
                $accion = 'Nuevo Usuario: ' . $entity->getUsuario();
                $this->get("gema.utiles")->traza($accion);
                $em->persist($entity);
                $em->flush();
                $request->getSession()->getFlashBag()->add(
                        'notice', 'Registro creado exitosamente'
                );
                return $this->redirect($this->generateUrl('usuario'));
            }
        }

        return $this->render('gemaBundle:Usuario:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Usuario entity.
     *
     * @param Usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Usuario $entity) {
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('usuario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Usuario entity.
     *
     */
    public function newAction() {
        $entity = new Usuario();
        $form = $this->createCreateForm($entity);

        return $this->render('gemaBundle:Usuario:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Usuario entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se encontro ningun usuario.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $accion = 'Ver Datos de Usuario: ' . $entity->getUsuario();
        $this->get("gema.utiles")->traza($accion);
        return $this->render('gemaBundle:Usuario:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Usuario entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se encontro ningun usuario.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('gemaBundle:Usuario:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Usuario entity.
     *
     * @param Usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Usuario $entity) {
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('usuario_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }

    /**
     * Edits an existing Usuario entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('gemaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se encontro ningun usuario.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setSalt(md5(time()));
            $encoder = $this->get('security.encoder_factory')
                    ->getEncoder($entity);
            $password = $encoder->encodePassword(
                    $entity->getPassword(), $entity->getSalt()
            );
            $entity->setPassword($password);
            $accion = 'Editar Usuario: ' . $entity->getUsuario();
            $this->get("gema.utiles")->traza($accion);
            $em->persist($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add(
                    'notice', 'Registro actualizado exitosamente'
            );
            return $this->redirect($this->generateUrl('usuario'));
        }

        return $this->render('gemaBundle:Usuario:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Usuario entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

//        if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('gemaBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se encontro ningun usuario.');
        }
        $accion = 'Borrar Usuario: ' . $entity->getUsuario();
        $this->get("gema.utiles")->traza($accion);
        $em->remove($entity);
        $em->flush();
//        }

        return $this->redirect($this->generateUrl('usuario'));
    }

    /**
     * Creates a form to delete a Usuario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('usuario_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Eliminar'))
                        ->getForm()
        ;
    }

}
