<?php
// src/Controller/FinancasController.php

namespace App\Controller;

use App\Repository\DespesasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function dashboard(Request $request): Response
    {
        // Processa o formulário de nova despesa se a requisição for POST
        if ($request->isMethod('POST')) {
            // Obtém os dados do formulário
            $motivo   = $request->request->get('motivo');
            $valor    = $request->request->get('valor');
            $dtConsumo = $request->request->get('data_consumo');
            $dtPag    = $request->request->get('data_pagamento');
            $fixo     = $request->request->get('fixo') ? 1 : 0;

            // Validação simples (adapte conforme necessário)
            if ($motivo && $valor && $dtConsumo) {
                $data = [
                    'ds_motivo'   => $motivo,
                    'vl_valor'    => (float) $valor,
                    'dt_consumo'  => $dtConsumo,
                    'dt_pag'      => $dtPag ?: null, // pode ser null se ainda não paga
                    'ds_fixa'     => $fixo,
                ];
                
                $this->despesasRepository->insertDespesa($data);
                
                // Mensagem de sucesso (opcional)
                $this->addFlash('success', 'Despesa inserida com sucesso!');
                
                // Redireciona para evitar reenvio do formulário (PRG)
                return $this->redirectToRoute('financas_dashboard');
            } else {
                $this->addFlash('error', 'Preencha os campos obrigatórios (motivo, valor, data de consumo).');
            }
        }
        
        // Lógica existente (GET ou após redirecionamento)
        $mesAtual = date('m');
        $anoAtual = date('Y');
        
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
            'anoAtual' => $anoAtual,
        ]);
    }
}