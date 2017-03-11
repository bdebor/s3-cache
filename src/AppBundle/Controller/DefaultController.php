<?php

namespace AppBundle\Controller;

use Doctrine\Common\Cache\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
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

        /**/ // to cache some data

        $cacheName = 'cacheData';
        $cache = $this->get('app.cache');
        if(! $data = $cache->read($cacheName, 20)) {
            sleep(2);
            $data = 'Data cached ';
            $cache->write($cacheName, $data);
        }
        echo($data);


        /**/ // to cache some code

        if(! $cache->start('cacheCode')){
            sleep(2);
            echo('Code cached ');
        }
        $cache->end();


        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
}
