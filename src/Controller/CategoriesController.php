<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Products;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        $products=new products();
        $categories=new categories();
        $categories = $categoriesRepository->findAll();

        return $this->render('/categories/index.html.twig', compact('categories' , 'products'));
    }


    #[Route('/{name}', name: 'list')]
    public function list(Categories $category, ProductsRepository $productsRepository, Request $request): Response
    {
        $produit=new products();
        

        //On va chercher la liste des produits de la catégorie
        //$products = $productsRepository->findProductsPaginated($category->getName(), 4);

       // return $this->render('categories/list.html.twig', compact('category', 'products'));
        // Syntaxe alternative
         return $this->render('categories/list.html.twig', [
            'category' => $category,
             'products' => $produit,
         ]);
    }
    #[Route('/add', name: 'addCategory')]
    public function add(Request $request , ManagerRegistry $mr): Response
    {
        $category=new categories();
        $form=$this->createForm(CategoriesFormType::class,$category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category=$form->getData();
            $em=$mr->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('index');
        }
        return $this->render('categories/add.html.twig',['form'=> $form->createView()]);
        
}

}