<?php

declare(strict_types=1);

namespace SecretariaFiap\Adapter\PDO;

use PDO;
use SecretariaFiap\Core\Contratos\Repositorio\MatriculaRepositorio;
use SecretariaFiap\Core\Entidade\Matricula;
use SecretariaFiap\Helpers\Paginacao;
use SecretariaFiap\Infra\Banco\Conexao;

class MatriculaRepositorioPDO implements MatriculaRepositorio
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::obter();
    }

    public function existe(int $idAluno, int $idTurma): bool
    {
        $sql = "SELECT COUNT(*) FROM matriculas WHERE aluno_id = :aluno_id AND turma_id = :turma_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':aluno_id', $idAluno, PDO::PARAM_INT);
        $stmt->bindValue(':turma_id', $idTurma, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function salvar(Matricula $matricula): Matricula
    {
        $sql = "INSERT INTO matriculas (aluno_id, turma_id, data_matricula, uuid) 
                VALUES (:aluno_id, :turma_id, :data_matricula, :uuid)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':aluno_id', $matricula->getAluno()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':turma_id', $matricula->getTurma()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':data_matricula', $matricula->getDataMatricula()->format('Y-m-d H:i:s'));
        $stmt->bindValue(':uuid', $matricula->getUuid());
        $stmt->execute();

        $matricula->setId((int) $this->pdo->lastInsertId());
        return $matricula;
    }
}
