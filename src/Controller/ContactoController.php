<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\Mime\Part\FilePart;
use Symfony\Component\Mime\Part\FileBinaryMimeTypeGuesser;
use Symfony\Component\Mime\Part\MimePart;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends AbstractController
{
    #[Route('/contacto', name: 'app_contacto', methods: ['GET', 'POST'])]
    public function contacto(Request $request, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            // Verificar si es una petición AJAX
            $isAjax = $request->isXmlHttpRequest();
            
            $nombre = $request->request->get('nombre');
            $correo = $request->request->get('correo');
            $asunto = $request->request->get('asunto');
            $mensaje = $request->request->get('mensaje');

            try {
                // 1. Email para el dueño
                $emailToOwner = (new Email())
                    ->from(new Address($correo, $nombre))
                    ->to('somos.env@gmail.com')
                    ->subject("📥 $asunto")
                    ->html($this->renderEmailTemplate('owner', $nombre, $correo, $asunto, $mensaje));

                $mailer->send($emailToOwner);

                // 2. Email de confirmación al usuario
                $logoPath = $this->getParameter('kernel.project_dir') . '/public/img/logo_render.png';
                $logoCid = 'logo_cid';

                $emailToUser = (new Email())
                    ->from(new Address('somos.env@gmail.com', 'SoMoS'))
                    ->to($correo)
                    ->subject('Gracias por contactarnos')
                    ->html($this->renderEmailTemplate('user', $nombre, $correo, $asunto, $mensaje))
                    ->embedFromPath($logoPath, $logoCid);

                $mailer->send($emailToUser);

                // Respuesta diferente para AJAX y solicitudes normales
                if ($isAjax) {
                    return $this->json([
                        'status' => 'success',
                        'message' => 'Tu mensaje fue enviado correctamente. ¡Gracias por escribirnos!'
                    ]);
                } else {
                    $this->addFlash('success', 'Tu mensaje fue enviado correctamente. ¡Gracias por escribirnos!');
                    return $this->redirectToRoute('app_home');
                }

            } catch (\Exception $e) {
                // Manejo de errores
                if ($isAjax) {
                    return $this->json([
                        'status' => 'error',
                        'message' => 'Hubo un error al enviar el mensaje. Por favor intenta nuevamente.'
                    ], 500);
                } else {
                    $this->addFlash('error', 'Hubo un error al enviar el mensaje. Por favor intenta nuevamente.');
                    return $this->redirectToRoute('app_home');
                }
            }
        }

        // Para GET requests simplemente renderizamos la página
        return $this->render('base.html.twig');
    }

    private function renderEmailTemplate(string $type, string $nombre, string $correo, string $asunto, string $mensaje): string
    {
        $template = $type === 'owner' ? 'email/owner_email.html.twig' : 'email/user_email.html.twig';
        
        return $this->renderView($template, [
            'nombre' => $nombre,
            'correo' => $correo,
            'asunto' => $asunto,
            'mensaje' => $mensaje
        ]);
    }

    #[Route('/contacto/info', name: 'contacto')]
    public function index(): Response
    {
        return $this->render('contacto/index.html.twig', [
            'controller_name' => 'ContactoController',
        ]);
    }
}
