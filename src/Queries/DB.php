<?php

namespace App\Queries;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DB extends AbstractController{

    function findOneUserBySomeField(String $fieldtosearch, String $value){
        
        $repository = $this->getDoctrine()->getRepository(Products::class);
        $getProduct = $repository->find($fieldtosearch, $value);
        return $getProduct;
    }

}