<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Docker\Docker;
use Docker\DockerClientFactory;
use Docker\API\Model\ContainersCreatePostBody;

use App\Message\ServerMessage;

/**
 * @Route("/server")
 */
class ServerController extends AbstractController
{
    /**
     * @Route("/", name="server_index", methods={"GET"})
     */
    public function index(): Response
    {
        $headers = array('Accept' => 'application/json');
        $response = \Unirest\Request::get($this->getParameter('api_url').'/servers/', $headers);

        foreach ($response->body as $container) {
            $containersArr[$container->id] = [
                'name'          => $container->names,
                'state'         => $container->state,
                'creation_date' => date('d/m/Y', $container->created),
                'ips'           => $container->ips
            ];
        }

        return $this->render('server/index.html.twig', [
            'servers' => $containersArr
        ]);
    }

    /**
     * @Route("/new", name="server_new", methods={"GET"})
     */
    public function new(Request $request): Response
    {
        $headers = array('Accept' => 'application/json');
        $data = array('name' => uniqid());
        $body = \Unirest\Request\Body::form($data);

        $response = \Unirest\Request::post($this->getParameter('api_url').'/servers/create', $headers, $body);

        return $this->redirectToRoute('server_index');
    }

    private function handleStartStop($action, $id)
    {
        $headers = array('Accept' => 'application/json');
        $response = \Unirest\Request::get($this->getParameter('api_url').'/servers/'.$id.'/'.$action, $headers);

        if ($response->code != 200) {
            if (!empty($response->body)) {
                $this->addFlash('error', 'Une erreur est survenue: ' . $response->body);
            } else {
                $this->addFlash('error', 'Une erreur est survenue');
            }
        }
    }

    /**
     * @Route("/start/{id}", name="server_start", methods={"GET"})
     */
    public function start(Request $request, $id): Response
    {
        $this->handleStartStop('start', $id);

        return $this->redirectToRoute('server_index');
    }

    /**
     * @Route("/stop/{id}", name="server_stop", methods={"GET"})
     */
    public function stop(Request $request, $id): Response
    {
        $this->handleStartStop('stop', $id);
        
        return $this->redirectToRoute('server_index');
    }

    /**
     * @Route("/delete/{id}", name="server_delete", methods={"GET"})
     */
    public function delete(Request $request, $id): Response
    {
        $headers = array('Accept' => 'application/json');
        $response = \Unirest\Request::delete($this->getParameter('api_url').'/servers/'.$id.'/delete', $headers);

        if ($response->code != 200) {
            if (!empty($response->body)) {
                $this->addFlash('error', 'Une erreur est survenue: ' . $response->body);
            } else {
                $this->addFlash('error', 'Une erreur est survenue');
            }
        }
        
        return $this->redirectToRoute('server_index');
    }
}
