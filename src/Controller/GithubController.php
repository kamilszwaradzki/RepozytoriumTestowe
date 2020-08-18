<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;

class GithubController extends AbstractController
{
    /**
     * @Route("/github", name="github")
     */
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function index(): Response
    {
    	$response = $this->client->request(
            'GET',
	    'https://api.github.com/user/repos',
	    [ 'auth_basic' => '%env(USERNAME)%:%env(PASSWORD)%' ]
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
	// $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

	return new Response(
            '<html><body>My Repos: <br/>'.$response->getContent().'</body></html>'
        );

	/*
		return $this->render('github/index.html.twig', [
		    'controller_name' => 'GithubController',
	    ]);
	*/
    }
}
