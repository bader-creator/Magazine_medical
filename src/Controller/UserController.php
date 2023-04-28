<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class UserController extends AbstractController
{
    /**
     * @Route("user/signup", name="app_register")
     */
    public function Signup(Request $request,EntityManagerInterface $em,UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $email=$request->query->get('email');
        $firstname=$request->query->get('firstname');
        $lastname=$request->query->get('lastname');
        $plainPassword=$request->query->get('password');
        $roles=$request->query->get('roles');
        $date_naissance=$request->query->get('date_naissance');

        //control mail
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            return new JsonResponse('email invalid',200);
        }

        $user=new User();
        $user->setEmail($email);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setRoles($roles);
        $encoded = $encoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);
        $user->setIsVerified(true);
        $user->setDateNaissane(new \Datetime(strtotime($date_naissance)));

        try{
            $em->persist($user);
            $em->flush();

            return new JsonResponse("Account is created",200);
        }catch(\Exception $ex){
            return new JsonResponse("Exception".$ex->getMessage());
        }

    }

    /**
     * @Route("user/signIn", name="app_login")
     */

     public function SignIn(Request $request,EntityManagerInterface $em){

        $email=$request->query->get('email');
        $password=$request->query->get('password');

        $user=$em->getRepository(User::class)->findOneBy(['email'=>$email]);

        if($user){

            if(password_verify($password,$user->getPassword())){
                $normalizer = new ObjectNormalizer(null, null, null, null, null, null, ['circular_reference_handler' => function ($object) {
                    return $object->getId();
                }]);
                $serializer = new Serializer([$normalizer]);
                $jsonData = $serializer->normalize($user);

                $response = new JsonResponse($jsonData, 200, ['Content-Type' => 'application/json']);
                return $response;
            }else{
                return new Response('password invalid');
            }
        }else{
            return new Response('User not found');
        }
     }
}
