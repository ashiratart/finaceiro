<?php
// src/Repository/DespesasRepository.php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class DespesasRepository
{
    private Connection $connection;
    
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    
    public function findDespesasPagasMesAtual(): array
    {
        $mesAtual = date('m');
        $anoAtual = date('Y');
        
        $sql = "
            SELECT * 
            FROM despesas 
            WHERE dt_pag IS NOT NULL 
            AND MONTH(dt_pag) = :mesAtual 
            AND YEAR(dt_pag) = :anoAtual
            ORDER BY dt_pag DESC
        ";
        
        return $this->connection->executeQuery($sql, [
            'mesAtual' => $mesAtual,
            'anoAtual' => $anoAtual
        ])->fetchAllAssociative();
    }
    
    public function findDespesasConsumoMesAtual(): array
    {
        $mesAtual = date('m');
        $anoAtual = date('Y');
        
        $sql = "
            SELECT * 
            FROM despesas 
            WHERE dt_consumo IS NOT NULL 
            AND MONTH(dt_consumo) = :mesAtual 
            AND YEAR(dt_consumo) = :anoAtual
            AND (ds_fixa = 0 OR ds_fixa IS NULL)
            ORDER BY dt_consumo DESC
        ";
        
        return $this->connection->executeQuery($sql, [
            'mesAtual' => $mesAtual,
            'anoAtual' => $anoAtual
        ])->fetchAllAssociative();
    }
    
    public function findDespesasFixas(): array
    {
        $sql = "
            SELECT * 
            FROM despesas 
            WHERE ds_fixa = 1
            ORDER BY id DESC
        ";
        
        return $this->connection->executeQuery($sql)->fetchAllAssociative();
    }
    
    public function getTotalDespesasPagasMesAtual(): float
    {
        $mesAtual = date('m');
        $anoAtual = date('Y');
        
        $sql = "
            SELECT COALESCE(SUM(vl_valor), 0) as total
            FROM despesas 
            WHERE dt_pag IS NOT NULL 
            AND MONTH(dt_pag) = :mesAtual 
            AND YEAR(dt_pag) = :anoAtual
        ";
        
        $result = $this->connection->executeQuery($sql, [
            'mesAtual' => $mesAtual,
            'anoAtual' => $anoAtual
        ])->fetchAssociative();
        
        return (float) $result['total'];
    }
}