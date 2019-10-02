<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use CTIC\App\Account\Domain\Account;
use CTIC\App\Company\Domain\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CTIC\App\User\Domain\User;
use CTIC\App\User\Application\CreateUser;
use CTIC\App\User\Domain\Command\UserCommand;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();

        $username = $request->request->get('_username');
        $password = $request->request->get('_password');

        if (!$username || !$password) {
            return new Response('_username or _password not found', Response::HTTP_BAD_REQUEST);
        }

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->find(1);
        $companyRepository = $em->getRepository(Company::class);
        $company = $companyRepository->find(1);

        $userCommand = new UserCommand();
        $userCommand->name = $username;
        $userCommand->username = $username;
        $userCommand->enabled = true;
        $userCommand->permission = 0;
        $userCommand->account = $account;
        $userCommand->defaultCompany = $company;

        $user = CreateUser::create($userCommand);

        $user->password = $encoder->encodePassword($user, $password);

        $validator->validate($user);
        $em->persist($user);
        $em->flush();
        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    public function api()
    {
        return new Response(sprintf('Logged in as %s. Role/s %s', $this->getUser()->getUsername(), implode(',', $this->getUser()->getRoles())));
    }
}