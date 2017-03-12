<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\Common\Cache\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
//        $str = 'pppppp';
//        $cache = $this->get('doctrine_cache.providers.my_cache');
//        $key = md5($str);
//        if ($cache->contains($key)) {
//            $str = $cache->fetch($key);
//            echo('en cache');
//        } else {
//            sleep(2);
//            $cache->save($key, $str);
//            echo('pas en cache');
//        }

//        /**/ // to cache some data
//
//        $cacheName = 'cacheData';
//        $cache = $this->get('app.cache');
//        if(! $data = $cache->read($cacheName, 20)) {
//            sleep(2);
//            $data = 'Data cached ';
//            $cache->write($cacheName, $data);
//        }
//        echo($data);
//
//
//        /**/ // to cache some code
//
//        if(! $cache->start('cacheCode')){
//            sleep(2);
//            echo('Code cached ');
//        }
//        $cache->end();


        $response = $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);

        $response->setSharedMaxAge(10); // seconds

        return $response;
    }

    /**
     * Lists all post entities.
     *
     * @Route("/post/", name="post_index")
     * @Method("GET")
     */
    public function postIndexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->findAll();

        $response = $this->render('post/index.html.twig', array(
            'post' => $post,
        ));
        //sleep(2);
        $response->setSharedMaxAge(20); // seconds

        return $response;
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/post/new", name="post_new")
     * @Method({"GET", "POST"})
     */
    public function postNewAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm('AppBundle\Form\PostType', $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }
}
