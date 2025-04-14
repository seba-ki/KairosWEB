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
            $nombre = $request->request->get('nombre');
            $correo = $request->request->get('correo');
            $asunto = $request->request->get('asunto');
            $mensaje = $request->request->get('mensaje');

            // 1. Email para vos
            $emailToOwner = (new Email())
                ->from(new Address($correo, $nombre))
                ->to('somos.env@gmail.com')
                ->subject("📥 $asunto")
                ->html(
                    '<!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <style>
                            body {
                                background-color: #1a1a1a;
                                color: #ffffff;
                                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                                padding: 30px;
                            }
                            .card {
                                max-width: 600px;
                                margin: auto;
                                background-color: #2a2a2a;
                                border-radius: 8px;
                                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                                padding: 30px;
                            }
                            h2 {
                                color: #00ff88;
                                margin-bottom: 20px;
                            }
                            table {
                                width: 100%;
                            }
                            td {
                                padding: 10px 0;
                                vertical-align: top;
                            }
                            .label {
                                font-weight: bold;
                                color: #ffffff;
                                width: 120px;
                            }
                            .message {
                                margin-top: 25px;
                                padding: 20px;
                                background-color: #333333;
                                border-left: 5px solid #00ff88;
                                white-space: pre-wrap;
                                color: #ffffff;
                            }
                            p, strong {
                                color: #ffffff;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="card">
                            <h2>Nuevo mensaje de contacto</h2>
                            <table>
                                <tr>
                                    <td class="label">Nombre:</td>
                                    <td style="color: #ffffff">' . htmlspecialchars($nombre) . '</td>
                                </tr>
                                <tr>
                                    <td class="label">Correo:</td>
                                    <td style="color: #ffffff">' . htmlspecialchars($correo) . '</td>
                                </tr>
                            </table>
                            <div class="message">
                                ' . nl2br(htmlspecialchars($mensaje)) . '
                            </div>
                        </div>
                    </body>
                    </html>'
                );

            $mailer->send($emailToOwner);

            // 📨 2. Email automático al usuario con imagen embebida en el diseño
            $logoPath = $this->getParameter('kernel.project_dir') . '/public/img/logo_render.png';
            $logoCid = 'logo_cid';

            $emailToUser = (new Email())
                ->from(new Address('somos.env@gmail.com', 'SoMoS'))
                ->to($correo)
                ->subject('Gracias por contactarnos')
                ->html(
                    '<!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <style>
                            body {
                                background-color: #1a1a1a;
                                color: #ffffff;
                                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                                padding: 30px;
                            }
                            .container {
                                max-width: 600px;
                                margin: auto;
                                background-color: #2a2a2a;
                                border-radius: 8px;
                                padding: 30px;
                                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                            }
                            h1 {
                                color: #00ff88;
                            }
                            p, strong {
                                color: #ffffff;
                            }
                            .footer {
                                margin-top: 40px;
                                text-align: center;
                                font-size: 13px;
                                color: #cccccc;
                            }
                            .logo {
                                margin-top: 15px;
                                width: 100px;
                                opacity: 0.9;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <h1>¡Gracias por escribirnos!</h1>
                            <p>Hola <strong>' . htmlspecialchars($nombre, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</strong>,</p>
                            <p>Recibimos tu mensaje con el asunto "<strong>' . htmlspecialchars($asunto) . '</strong>".</p>
                            <p>En breve alguien del equipo de <strong>SoMoS</strong> se estará contactando con vos.</p>
                            <p>¡Te agradecemos mucho por tu interés!</p>
                            <div class="footer">
                                <p>Este es un correo automático, por favor no lo respondas.</p>
                                <img src="cid:' . $logoCid . '" alt="SoMoS logo" class="logo">
                            </div>
                        </div>
                    </body>
                    </html>'
                )
                ->embedFromPath($logoPath, $logoCid);

            $mailer->send($emailToUser);

            $this->addFlash('success', 'Tu mensaje fue enviado correctamente. ¡Gracias por escribirnos!');
            return $this->render('base.html.twig');
        }

        return $this->render('base.html.twig');
    }

    #[Route('/contacto/info', name: 'contacto')]
    public function index(): Response
    {
        return $this->render('contacto/index.html.twig', [
            'controller_name' => 'ContactoController',
        ]);
    }
}
