<?php

namespace App\Controller;
use App\Entity\Coffee;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CoffeeController extends AbstractController
{
    /**
     * @Route("/coffee", name="coffee")
     */
    public function index()
    {
        $coffee = $this->getDoctrine()->getRepository(Coffee::class)->findAll();
        return $this->render('coffee/index.html.twig', array('coffee' => $coffee));
    }
    /**
    * @Route("/coffee/new")
    * Method({"GET", "POST"})
    */
    public function new(Request $request) {
        $today = getdate();  
        //dd($today);
        $coffee = new Coffee();
        $form = $this->createFormBuilder($coffee)
        ->add('milk', CheckboxType::class, array(
            'label' => 'Ar norite su pienu?',
            'required' => false,
            'attr' => array('onclick' => 'showHideMilkType()')))
        ->add('milktype', ChoiceType::class, array(
            'choices' => [
                'Pirmas pienas' => 'Pirmas pienas',
                'Antras pienas' => 'Antras pienas',
            ],
            'label' => 'Pieno rūšis',
            'label_attr' => array('id' => 'milktype_label'),
            'placeholder' => 'Pasirinkite pieno rūšį',
            'required' => false,
            'attr' => array('class' => 'form-control')))
        ->add('cupsize', ChoiceType::class, array(
            'choices' => [
                'S' => 'S',
                'L' => 'L',
                'XL' => 'XL',
            ],
            'label' => 'Puodelio dydis',
            'placeholder' => 'Pasirinkite puodelio dydį',
            'required' => true,
            'attr' => array('class' => 'form-control')))
        ->add('location', TextType::class, array(
            'label' => 'Pristatymo adresas',
            'required' => true,
            'attr' => array('class' => 'form-control')))
        //Kas 30min
        ->add('deliveron', DateTimeType::class, array(
            'label' => 'Pristatymo laikas',
            'years' => range(date('Y'), date('Y')+0),
            'months' => range(date('m'), 12),
            'days' => range(date('d'), 31),
            'minutes' => range(00, 30, 30),
            'required' => true,
            'attr' => array('class' => 'input-group date')))
        //
        ->add('save', SubmitType::class, array(
            'label' => 'Užsakyti',
            'attr' => array('class' => 'btn btn-primary mt-3')))
        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $coffee = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($coffee);
            $entityManager->flush();
            return $this->redirectToRoute('coffee');
        }

        return $this->render('coffee/new.html.twig', array(
        'form' => $form->createView()));
    }
    /**
    * @Route("/coffee/json/{id}")
    * Method({"GET"})
    */
    public function showJson (Request $request){
        /*
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        */
        $id = $request->get('id');
        $coffee = $this->getDoctrine()->getRepository(Coffee::class)->find($id);
        $coffeeLocation = $coffee->getLocation();
        $coffeeTime = $coffee->getDeliveron();
        //$jsonContent = $serializer->serialize($id, 'json');
        return $this->json([$coffeeLocation, $coffeeTime]);
    }

    /**
    * @Route("/coffee/xml/{id}", defaults={"_format"="xml"})
    * Method({"GET"})
    */
    public function showXml(Request $request)
    {   
        $id = $request->get('id');
        $coffee = $this->getDoctrine()->getRepository(Coffee::class)->find($id);
        $coffeeLocation = $coffee->getLocation();
        $coffeeTime = $coffee->getDeliveron();
        return $this->render('coffee/xml.xml.twig', array(['coffeeLocation' => $coffeeLocation, 'coffeeTime' => $coffeeTime]));
    }

}
