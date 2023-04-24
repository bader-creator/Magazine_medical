<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user/signup", name="app_register")
     */
    public function Signup(Request $request,EntityManagerInterface $em,UserPasswordEncoderInterface $encoder): JsonResponse
    {
        $email=$request->query->get('eamil');
        $firstname=$request->query->get('firstname');
        $lastname=$request->query->get('lastname');
        $plaintextPassword=$request->query->get('password');
        $date_naissance=$request->query->get('datenaissance');
        $roles=$request->query->get('roles');

        //control mail
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            return new Response('email invalid');
        }

        $user=new User();
        $user->setEmail($email);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setRoles(array($roles));
        $encoded = $encoder->encodePassword($user, $plaintextPassword);
        $user->setPassword($encoded);
        $user->setIsVerified($true);
        $user->setDateNaissane(new \Datetime($date_naissance));

        try{
            $em->persist($user);
            $em->flush();

            return new JsonResponse("Account is created",200);
        }catch(\Exception $ex){
            return new JsonResponse("Exception".$ex->getMessage());
        }

    }

    /**
     * @Route("/user/signIn", name="app_login")
     */

     public function SignIn(Request $request,EntityManagerInterface $em){

        $email=$request->query->get('eamil');
        $password=$request->query->get('password');

        $user=$em->getRepositroy(User::class)->findOneBy(['email'=>$email]);

        if($user){

            if(password_verify($password,$user->getPassword())){

                $data=[];

                $data['user']=$user;

                return new JsonResponse($data, 200);
            }else{
                return new Response('password invalid');
            }
        }else{
            return new Response('User not found');
        }
     }
}
