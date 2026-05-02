<?php
// src/Controller/FinancasController.php

namespace App\Controller;

use App\Repository\DespesasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FinancasController extends AbstractController
{
    private DespesasRepository $despesasRepository;
    
    public function __construct(DespesasRepository $despesasRepository)
    {
        $this->despesasRepository = $despesasRepository;
    }
    
    #[Route('/financas/dashboard', name: 'financas_dashboard', methods: ['GET', 'POST'])]
    public function dashboard(): Response
    {
        $mesAtual = date('m');
        $anoAtual = date('Y');
        
        // Buscar dados usando o repository
        $despesasFixas = $this->despesasRepository->findDespesasFixas();
        $despesasConsumo = $this->despesasRepository->findDespesasConsumoMesAtual();
        $despesasPagas = $this->despesasRepository->findDespesasPagasMesAtual();
        $totalPago = $this->despesasRepository->getTotalDespesasPagasMesAtual();
        
        return $this->render('financas/dashboard.html.twig', [
            'despesasPagas' => $despesasPagas,
            'despesasConsumo' => $despesasConsumo,
            'despesasFixas' => $despesasFixas,
            'totalPago' => $totalPago,
            'mesAtual' => $mesAtual,
            'anoAtual' => $anoAtual
        ]);
    }
}