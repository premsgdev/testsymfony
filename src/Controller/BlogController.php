<?php
/**
 * Created by PhpStorm.
 * User: psgangadharan
 * Date: 9/20/2019
 * Time: 11:37 AM
 */

namespace App\Controller;


//use http\Env\Request;
use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * Class BlogController
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}",name="blog_list", defaults={"page":1}, requirements={"page"="\d+"})
     */
    public function list($page, Request $request)
    {
        $var = $request->get('limit', 12);
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();
        return $this->json([
            'vat' => $var,
            'page' => $page,
            'data' => $items
        ]);
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"})
     * @paramConverter("post", class="App:BlogPost")
     */
    public function post($post)
    {
        return $this->json($post);
    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug")
     */
    public function postBySlug($slug)
    {
        return $this->json(
            $this->getDoctrine()->getRepository(BlogPost::class)->findBy(['slug' => $slug])
        );
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');
        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');
        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();
        return $this->json($blogPost);
    }
}