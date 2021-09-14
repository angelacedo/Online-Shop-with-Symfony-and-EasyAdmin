<?php

namespace App\Controller;

use App\Entity\Products;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="shop")
     */
    public function shop(): Response
    {

        $repository = $this->getDoctrine()->getRepository(Products::class);
        $getProducts = $repository->findAll();

        return $this->render('shop/shop.html.twig', ["products" => $getProducts]);
    }

    /**
     * @Route("/additemtocart", name="additemtocart")
     */
    public function additemtocart(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $idproduct = $request->request->get('idproduct');
            if ($request->getSession()->has('idproducts')) {

                $arrayidproducts = $request->getSession()->get('idproducts');

                //If idproduct exist on array, it will do nothing. If it is not, the product will be added
                if (in_array($idproduct, $arrayidproducts) == false) {
                    $arrayidproducts[] = $idproduct;
                    $request->getSession()->set('idproducts', $arrayidproducts);

                    //If user is logged in, the item will be added to the database.
                    if ($this->getUser()) {
                        $em = $this->getDoctrine()->getManager();
                        $repository = $em->getRepository(Users::class);
                        $user = $repository->findOneBy(['id' => $this->getUser()->getId()]);
                        $user->setCartItems(json_encode($arrayidproducts));
                        $em->persist($user);
                        $em->flush();
                    }
                    $repository = $this->getDoctrine()->getRepository(Users::class);

                    return new JsonResponse(json_encode($arrayidproducts));
                }
            } else {
                $request->getSession()->set('idproducts', []);
                $arrayidproducts = $request->getSession()->get('idproducts');
                $arrayidproducts[] = $idproduct;
                $request->getSession()->set('idproducts', $arrayidproducts);

                //If user is logged in, the item will be added to the database.
                if ($this->getUser()) {

                    $em = $this->getDoctrine()->getManager();
                    $repository = $em->getRepository(Users::class);

                    $user = $repository->findOneBy(['id' => $this->getUser()->getId()]);
                    $user->setCartItems(json_encode($arrayidproducts));
                    $em->persist($user);
                    $em->flush();
                }

                return new JsonResponse(json_encode($arrayidproducts));
            }
        }
        return $this->render('shop/mycart.html.twig');
    }


    /**
     * @Route("/deleteproductfromcart", name="deleteproductfromcart")
     */
    public function deleteproduct(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            if ($request->getSession()->has('idproducts')) {

                $arrayidproducts = $request->getSession()->get('idproducts');
                $repository = $this->getDoctrine()->getRepository(Products::class);
                $idproduct = $request->request->get('idproduct');

                $pos = array_search($idproduct, $arrayidproducts);
                array_splice($arrayidproducts, $pos, 1);

                $request->getSession()->set('idproducts', $arrayidproducts);
                if ($this->getUser()) {

                    $em = $this->getDoctrine()->getManager();
                    $repository = $em->getRepository(Users::class);

                    $user = $repository->findOneBy(['id' => $this->getUser()->getId()]);
                    $user->setCartItems(json_encode($arrayidproducts));
                    $em->persist($user);
                    $em->flush();
                }


                $productsubtotalprice = 0;
                foreach ($arrayidproducts as $productid) {
                    $productobject = $repository->findOneBy(['id' => $productid]);
                    $productsubtotalprice += $productobject->getPrice();
                }
                $returneddata = [];
                $returneddata[] = $idproduct;
                $returneddata[] = $productsubtotalprice;
                return new JsonResponse(json_encode($returneddata));
            }
        }
    }

    /**
     * @Route("/mycart", name="mycart")
     */
    public function mycart(Request $request)
    {
        if ($request->getSession()->has('idproducts') && $request->getSession()->get('idproducts') != []) {
            $arrayidproducts = $request->getSession()->get('idproducts');
            $repository = $this->getDoctrine()->getRepository(Products::class);

            $arrayproducts = [];
            $productsubtotalprice = 0;
            $productcategories = [];

            foreach ($arrayidproducts as $idproduct) {
                $productobject = $repository->findOneBy(['id' => $idproduct]);
                $arrayproducts[] = $productobject;
                $productsubtotalprice += $productobject->getPrice();
                $productcategories[] =  $productobject->getCategory();
            }



            return $this->render('shop/mycart.html.twig', ['products' => $arrayproducts, 'subtotalprice' => $productsubtotalprice, 'categories' => $productcategories]);
        };
        return $this->render('shop/mycart.html.twig');
    }
}
