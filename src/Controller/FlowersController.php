<?php

namespace App\Controller;
use App\Entity\Flowers;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FlowersController extends AbstractController
{
    /**
     * @Route("/flowers", name="flowers")
     */
    public function index()
    {

        $flowers = $this->getDoctrine()->getRepository(Flowers::class)->findAll();
        return $this->render('flowers.html.twig', array('flowers' => $flowers));
    }

    /**
     * @Route("/flowers/new")
     * Method({"GET", "POST"})
     */
    public function new(Request $request) {
        $flowers = new Flowers();
        $form = $this->createFormBuilder($flowers)
            ->add('name', ChoiceType::class, array(
                'choices' => [
                    'Pirma gėlė' => 'Pirma gėlė',
                    'Antra gėlė' => 'Antra gėlė',
                    'Trečia gėlė' => 'Trečia gėlė',
                    'Ketvirta gėlė' => 'Ketvirta gėlė',
                    'Penkta gėlė' => 'Penkta gėlė',
                ],
                'label' => 'Gėlių pavadinimas',
                'placeholder' => 'Pasirinkite gėlę',
                'required' => true,
                'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array(
                'label' => 'Pristatymo adresas',
                'required' => true,
                'attr' => array('class' => 'form-control')))
            ->add('deliveron', DateTimeType::class, array(
                'label' => 'Pristatymo laikas',
                'years' => range(date('Y'), date('Y')+0),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
                'required' => true,
                'attr' => array('class' => 'input-group date')))
            ->add('save', SubmitType::class, array(
                'label' => 'Užsakyti',
                'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $flowers = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($flowers);
            $entityManager->flush();
            return $this->redirectToRoute('flowers');
        }

        return $this->render('flowers/new.html.twig', array(
        'form' => $form->createView()));
    }

    /**
     * @Route("/flowers/json/{id}")
     * Method({"GET"})
     */

    public function showJson (Request $request){
        /*
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        */
        $id = $request->get('id');
        $flowers = $this->getDoctrine()->getRepository(Flowers::class)->find($id);
        $flowersAddress = $flowers->getAddress();
        $flowersTime = $flowers->getDeliveron();
        //$jsonContent = $serializer->serialize($id, 'json');
        return $this->json([$flowersAddress, $flowersTime]);
    }
    /**
     * @Route("/flowers/xml/{id}")
     * Method({"GET"})
     */

    public function showXml (){
        return $this->json(['username' => 'jane.doe']);
    }
}
