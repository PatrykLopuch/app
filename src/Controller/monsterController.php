<?php

namespace App\Controller;

use App\Entity\Monster;
use App\Repository\MonsterRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Translation\TranslatorInterface;
/**
 * Class monsterController
 * @package App\Controller
 *
 * @Route("/monster")
 */
class monsterController extends AbstractController
{
    /**
     * @return Response
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/index", name="monster_index")
     * @param Request HTTP $request Request
     * @param MonsterRepository $repository Repository
     * @param PaginatorInterface $paginator Paginator
     */
    public function index(Request $request, MonsterRepository $repository, PaginatorInterface $paginator): Response {

        $monsters = $this->getDoctrine()->getRepository(Monster::class)->findAll();

        $name = $request->query->getAlnum('Name');

//        $pagination = $paginator->paginate(
//            $repository->queryAll(),
//            $request->query->getInt('page', 1),
//            Monster::NUMBER_OF_ITEMS
//        );
//
        return $this->render('monster/index.html.twig', array('monsters' => $monsters));

//
//        return $this->render(
//            'monster/index.html.twig',
//            ['pagination' => $pagination]
//        );
    }



    /**
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/new", name="monster_new")
     * @Method({"GET", "POST"})
     *
     *
     */
    public function new(Request $request){

        $monster = new Monster();

        $form = $this->createFormBuilder($monster)->add('name',TextType::
        class , array('attr'=>array('class'=>'form-control')))->add('health',NumberType::class, array('required'=>true,'attr'=>array('class'=>'form-control')))
        ->add('experience',NumberType::class, array('required'=>true,'attr'=>array('class'=>'form-control')))
            ->add('save',SubmitType::class, array(
            'label' => 'Add',
            'attr' => array('class' => 'btn btn-primary mt-3')
        ))->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $monster = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($monster); // przygotowac do wyslania

            $entityManager->flush();  // wyslac dane

            return $this->redirectToRoute('monster_index');

        }

        return $this->render('monster/new.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/edit/{id}", name="monster_edit")
     * @Method({"GET", "POST"})
     *
     *
     */
    public function edit(Request $request, $id){

        $monster = $this->getDoctrine()->getRepository(Monster::Class)->find($id);



        $form = $this->createFormBuilder($monster)->add('name',TextType::
        class , array('attr'=>array('class'=>'form-control')))->add('health',NumberType::class, array('required'=>true,'attr'=>array('class'=>'form-control')))
            ->add('experience',NumberType::class, array('required'=>true,'attr'=>array('class'=>'form-control')))
            ->add('save',SubmitType::class, array(
                'label' => 'Save Changes',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {


            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($monster); // przygotowac do wyslania

            $entityManager->flush();  // wyslac dane

            return $this->redirectToRoute('monster_index');

        }

        return $this->render('monster/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/{id}", name="monster_show")
     */
    public function view($id) {

        $monster = $this->getDoctrine()->getRepository(Monster::Class)->find($id);



        return $this->render('monster/show.html.twig', array ('monster' => $monster));

    }

    /**
     * @param $id
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/delete/{id}", name="monster_delete")
     * @Method({"DELETE"})
     *
     * @return
     */
    public function delete($id) {

        $monster = $this->getDoctrine()->getRepository(Monster::Class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($monster); // przygotowac do wyslania
        $entityManager->flush();  // wyslac dane

        return $this->render('monster/delete.html.twig', array('monster' => $monster));

    }


}

