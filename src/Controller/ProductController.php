<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductController extends AbstractController
{
    /**
     * @Route("product/add", name="app_product")
     */
    public function add(Request $request,EntityManagerInterface $em): JsonResponse
    {
        $name=$request->query->get('name');
        $price=$request->query->get('price');
        $quantity=$request->query->get('quantity');
        $iduser=$request->query->get('iduser');


        $product=new Product();
        $product->setName($name);
        $product->setPrix($price);
        $product->setQuantity($quantity);
        $product->setIsDesponible(true);
        $product->setCreatedAt(new \DateTime("now"));
        $user=$em->getRepository(User::class)->find($iduser);
        $product->setCreateur($user);

        try{
            $em->persist($product);
            $em->flush();

            return new JsonResponse("Product is added successfully",200);
        }catch(\Exception $ex){
            return new JsonResponse("Exception".$ex->getMessage());
        }

    }

    /**
     * @Route("product/list", name="list_product")
     */
    public function ListProductById(Request $request,EntityManagerInterface $em){

        $iduser=$request->query->get('iduser');

        $products=$em->getRepository(Product::class)->findBy(['createur'=>$iduser]);


        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, ['circular_reference_handler' => function ($object) {
            return $object->getId();
        }]);
        $serializer = new Serializer([$normalizer]);
        $jsonData = $serializer->normalize($products);

        $response = new JsonResponse($jsonData, 200, ['Content-Type' => 'application/json']);
        return $response;

    }

    /**
     * @Route("product/Detail", name="Detail_product")
     */
    public function DetailProduct(Request $request,EntityManagerInterface $em){

        $idproduct=$request->query->get('idproduct');

        $product=$em->getRepository(Product::class)->find($idproduct);

        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($product);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("product/Delete", name="Delete_product")
     */
    public function DeleteProduct(Request $request,EntityManagerInterface $em){

        $idproduct=$request->query->get('idproduct');

        $product=$em->getRepository(Product::class)->find($idproduct);

        $em->remove($product);
        $em->flush();

        return new JsonResponse("Product is deleted successfully",200);
    }

    /**
     * @Route("product/edit", name="edit_product")
     */
    public function EditProduct(Request $request,EntityManagerInterface $em){

        $idproduct=$request->query->get('idproduct');
        $name=$request->query->get('name');
        $price=$request->query->get('price');
        $quantity=$request->query->get('quantity');

        $product=$em->getRepository(Product::class)->find($idproduct);
        $product->setName($name);
        $product->setPrix($price);
        $product->setQuantity($quantity);

        try{
            $em->persist($product);
            $em->flush();

            return new JsonResponse("Product is updated successfully",200);
        }catch(\Exception $ex){
            return new JsonResponse("Exception".$ex->getMessage());
        }
    }
}
