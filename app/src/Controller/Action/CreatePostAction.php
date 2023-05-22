<?php

declare(strict_types=1);

namespace App\Controller\Action;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreatePostAction extends AbstractController
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function __invoke(Post $data, Request $request): Post
    {
dump(2123);exit;
        dump($request->headers->all()); exit();
        // /** @var User $user */
        // $user = $this->getUser();

        $this->validator->validate($data);

        return $data;
    }
}
