<?php
// src/Controller/LoginController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Connection;  // Importe o Connection

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function index(Request $request, Connection $connection): Response
    {
        // Se for POST (formulário enviado)
        if ($request->isMethod('POST')) {
            $login = $request->request->get('login');
            $senha_texto = $request->request->get('senha');
            
            // Converte para MD5 binário (16 bytes)
            $senha_binaria = md5($senha_texto, true); // O true retorna em binário
            
            // Agora consulta usando a senha binária
            $sql = "SELECT * FROM users WHERE login = ? AND senha = ?";
            $result = $connection->executeQuery($sql, [
                $login,
                $senha_binaria
            ]);
            
            $user = $result->fetchAssociative();
            
            if ($user) {
                // Login bem-sucedido
                // Armazena dados na sessão
                $request->getSession()->set('user_id', $user['id']);
                $request->getSession()->set('user_login', $user['login']);
                
                $this->addFlash('success', 'Login realizado com sucesso!');
                return $this->redirectToRoute('financas_dashboard'); // Rota para página inicial
            } else {
                // Login falhou
                $this->addFlash('error', 'Usuário ou senha inválidos');
            }
        }
        
        return $this->render('login/index.html.twig');
    }
}