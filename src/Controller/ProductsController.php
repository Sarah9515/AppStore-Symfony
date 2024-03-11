<?php



namespace App\Controller;

use App\Entity\Products;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;
use App\Form\ProductsFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\Request;

#[Route('/products', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(ManagerRegistry $mr): Response
    {
        $allProducts = $mr->getRepository(Products::class)->findAll();
        return $this->render('products/index.html.twig', ['allProducts'=>$allProducts]);
    }

    #[Route('/add', name: 'addProduit')]
    public function add(Request $request , ManagerRegistry $mr): Response
    {
        $produit=new products();
        $form=$this->createForm(ProductsFormType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $produit=$form->getData();
            $em=$mr->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('index');
        }
        return $this->render('products/add.html.twig',['form'=> $form->createView()]);
            

        

        
    }
}