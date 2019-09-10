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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Translation\TranslatorInterface;
/**
 * Class monsterController
 * @package App\Controller
 *
 * @Route("/")
 */
class monsterController extends AbstractController
{
    /**
     * @return Response
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/", name="monster_index")
     * @param Request HTTP $request Request
     * @param MonsterRepository $repository Repository
     * @param PaginatorInterface $paginator Paginator
     */
    public function index(Request $request, MonsterRepository $repository, PaginatorInterface $paginator): Response {

        $monsters = $this->getDoctrine()->getRepository(Monster::class)->findAll();   // to jest potrzebne żeby działało bez paginacji jak odkomentuję poniżej

//        $id = $request->query->getAlnum('id');
//
//        $name = $request->query->getAlnum('Name');

//        $pagination = $paginator->paginate(                                   // gdzieś tu jest bład który wywala całą apkę z powodu paginacji
//            $repository->queryAll(),                                          // tzn nie do końca tu, tylko coś co z tego korzysta powoduje błąd
//            $request->query->getInt('page', 1),
//            Monster::NUMBER_OF_ITEMS
//        );

        return $this->render('monster/index.html.twig', array('monsters' => $monsters));            // bez paginacji


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
     *  @IsGranted(
     *     "IS_AUTHENTICATED_REMEMBERED",
     * )
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

            $monster->setAuthor($this->getUser());       /////////////////  yyyyyyyyyyyy no chyba działa

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($monster); // przygotowac do wyslania

            $entityManager->flush();  // wyslac dane

            return $this->redirectToRoute('monster_index');

        }

        return $this->render('monster/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/edit/{id}", name="monster_edit")
     * @Method({"GET", "POST"})
     *
     *
     *
     * @IsGranted("ROLE_USER")
     *
     */
    public function edit(Request $request, $id){


        // ifa porównać autora z obecnym userem i tylko wtedy dopuścić a jak nie to paszoł won?
        // trzeba jeszcze admina uwzględnić..

        $monster = $this->getDoctrine()->getRepository(Monster::Class)->find($id);

//        $this->isGranted()                   // << sie pewnie przyda

        if (($monster->getAuthor() == $this->getUser()) || ($this->isGranted("ROLE_ADMIN"))){       // jeżeli user to autor LUB user to admin to wpuszczamy
            $form = $this->createFormBuilder($monster)->add('name',TextType::
            class , array('attr'=>array('class'=>'form-control')))->add('health',NumberType::class, array('required'=>true,'attr'=>array('class'=>'form-control')))
                ->add('experience',NumberType::class, array('required'=>true,'attr'=>array('class'=>'form-control')))
                ->add('save',SubmitType::class, array(
                    'label' => 'Save',
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
                'form' => $form->createView(),
                'monster' => $monster
            ));
        } else {

            // to zwracamy w sytuacji gdy autor nie jest zgodny z obecnym userem

//        return $this->render('monster/index.html.twig', array(
//            'monster' => $monster
//        ));

            return $this->redirectToRoute('monster_index');
        }

    }


    /**
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/{id}", name="monster_show",requirements={"id": "[1-9]\d*"})
     */
    public function view($id) {

        $monster = $this->getDoctrine()->getRepository(Monster::Class)->find($id);



        return $this->render('monster/show.html.twig',

            array ('monster' => $monster));

    }

    /**
     * @param $id
     *
     *
     *
     * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\Route("/delete/{id}", name="monster_delete")
     * @Method({"DELETE"})
     *
     *
     *
     *
     * @return Response
     *
     */
    public function delete($id) {

        $monster = $this->getDoctrine()->getRepository(Monster::Class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($monster); // przygotowac do wyslania
        $entityManager->flush();  // wyslac dane

        return $this->render('monster/delete.html.twig', array('monster' => $monster));

    }


}

