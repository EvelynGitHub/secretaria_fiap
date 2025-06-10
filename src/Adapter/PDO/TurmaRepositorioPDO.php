<?php

declare(strict_types=1);

namespace SecretariaFiap\Adapter\PDO;

use PDO;
use PDOException;
use SecretariaFiap\Core\Contratos\Repositorio\TurmaRepositorio;
use SecretariaFiap\Core\Entidade\Turma;
use SecretariaFiap\Helpers\Paginacao;
use SecretariaFiap\Infra\Banco\Conexao;

class TurmaRepositorioPDO implements TurmaRepositorio
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::obter();
    }

    public function cadastrar(Turma $turma): Turma
    {
        $sql = "INSERT INTO turmas (uuid, nome, descricao) 
                VALUES (:uuid, :nome, :descricao)";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':uuid' => $turma->getUuid(),
                ':nome' => $turma->getNome(),
                ':descricao' => $turma->getDescricao()
            ]);
            $turma->setId((int) $this->pdo->lastInsertId());
            return $turma;
        } catch (PDOException $e) {
            throw new \RuntimeException("Erro ao cadastrar turma: " . $e->getMessage());
        }
    }

    public function editar(Turma $turma): bool
    {
        $sql = "UPDATE turmas 
                SET nome = :nome, nome = :nome, descricao = :descricao
                WHERE uuid = :uuid";
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':nome' => $turma->getNome(),
                ':descricao' => $turma->getDescricao(),
                ':uuid' => $turma->getUuid(),
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deletar(string $uuid): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM turmas WHERE uuid = :uuid");
            return $stmt->execute([':uuid' => $uuid]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function buscarPorUuid(string $uuid): ?Turma
    {
        $stmt = $this->pdo->prepare("SELECT * FROM turmas WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->mapearParaEntidade($dados) : null;
    }

    /**
     * Lista turmas por filtros, retornando um objeto de paginação.
     *
     * @param string $nome O nome a ser filtrado.
     * @param int $offset O offset para a paginação.
     * @param int $limit O limite de registros por página.
     * @return Paginacao<Turma> Um objeto Paginacao contendo uma lista de objetos Turma.
     */
    public function listarPorFiltros(string $nome, int $offset = 0, int $limit = 10): Paginacao
    {
        // 1. Consulta para obter os dados da página atual
        $sqlDados = "SELECT * FROM turmas WHERE nome LIKE :nome LIMIT :offset, :limit";
        $stmtDados = $this->pdo->prepare($sqlDados);
        $stmtDados->bindValue(':nome', "%{$nome}%");
        $stmtDados->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmtDados->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmtDados->execute();

        $turmas = [];
        while ($row = $stmtDados->fetch(PDO::FETCH_ASSOC)) {
            $turmas[] = $this->mapearParaEntidade($row);
        }

        // 2. Consulta separada para obter o total de registros
        $sqlTotal = "SELECT COUNT(id) FROM turmas WHERE nome LIKE :nome";
        $stmtTotal = $this->pdo->prepare($sqlTotal);
        $stmtTotal->bindValue(':nome', "%{$nome}%");
        $stmtTotal->execute();
        $totalRegistros = $stmtTotal->fetchColumn();

        $totalPaginas = (int) ceil($totalRegistros / $limit);
        $paginaAtual = (int) ($offset / $limit) + 1;

        return new Paginacao(
            paginaAtual: $paginaAtual,
            totalRegistros: (int) $totalRegistros,
            totalPaginas: $totalPaginas,
            limite: $limit,
            itens: $turmas
        );
    }

    private function mapearParaEntidade(array $dados): Turma
    {
        $turma = new Turma(
            $dados['nome'],
            $dados['descricao'],
            $dados['uuid']
        );
        $turma->setId((int) $dados['id']);
        return $turma;
    }
}
