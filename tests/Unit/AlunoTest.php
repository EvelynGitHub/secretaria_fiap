<?php

declare(strict_types=1);

namespace Tests\Unit;

use DateTime;
use Exception;
use InvalidArgumentException;

use PDOException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SecretariaFiap\Adapter\PDO\AlunoRepositorioPDO;
use SecretariaFiap\Core\CasosUso\Aluno\Atualizar;
use SecretariaFiap\Core\CasosUso\Aluno\Obter;
use SecretariaFiap\Core\Entidade\Aluno;
use SecretariaFiap\Core\CasosUso\Aluno\Cadastrar;
use SecretariaFiap\Core\CasosUso\Aluno\InputObject;
use SecretariaFiap\Core\CasosUso\Aluno\OutputObject;
use SecretariaFiap\Core\Contratos\Repositorio\AlunoRepositorio;
use SecretariaFiap\Core\Entidade\Senha;
use SecretariaFiap\Helpers\GeradorUuid;
use Tests\ConexaoTeste;

class AlunoTest extends TestCase
{
    private AlunoRepositorio $repositorio;
    private Cadastrar $casoUso;
    private AlunoRepositorioPDO $repositorioPDO;

    protected function setUp(): void
    {
        $this->repositorio = $this->createMock(AlunoRepositorio::class);
        $this->casoUso = new Cadastrar($this->repositorio);

        $this->repositorioPDO = new AlunoRepositorioPDO();
        $this->limparTabela();
    }

    protected function tearDown(): void
    {
        $this->limparTabela();
    }

    private function limparTabela(): void
    {
        $pdo = ConexaoTeste::obter();
        $pdo->exec("DELETE FROM matriculas");
        $pdo->exec("DELETE FROM turmas");
        $pdo->exec("DELETE FROM alunos");
    }

    public function test_deve_rejeitar_nome_com_menos_de_3_caracteres()
    {
        $this->expectException(InvalidArgumentException::class);

        $input = InputObject::create([
            'nome' => 'An',
            'cpf' => '12345678901',
            'email' => 'ana@teste.com',
            'senha' => 'Senha123!',
            'data_nascimento' => '1990-01-01'
        ]);

        $this->casoUso->executar($input);
    }


    public function test_deve_permitir_cadastro_com_dados_validos()
    {
        $input = InputObject::create([
            'nome' => 'Ana Silva',
            'cpf' => '12345678901',
            'email' => 'ana@teste.com',
            'senha' => 'Senha123!',
            'data_nascimento' => '1990-01-01'
        ]);

        $this->repositorio
            ->expects($this->once())
            ->method('cadastrar');

        $output = $this->casoUso->executar($input);

        $this->assertInstanceOf(OutputObject::class, $output);
    }

    public function test_deve_cadastrar_aluno_com_dados_validos_pdo()
    {
        $aluno = new Aluno('Joana Silva', '32165498700', 'joana@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1990-10-10'));

        $alunoCriado = $this->repositorioPDO->cadastrar($aluno);

        $this->assertNotNull($alunoCriado->getId());
        $this->assertEquals('Joana Silva', $alunoCriado->getNome());
        $this->assertEquals('1990-10-10', $alunoCriado->getDataNascimento()->format('Y-m-d'));
    }

    public function test_nao_deve_permitir_cadastro_com_cpf_duplicado()
    {
        $aluno1 = new Aluno('Lucas', '12345678900', 'lucas1@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1991-01-01'));
        $aluno2 = new Aluno('Pedro', '12345678900', 'pedro@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1992-02-02'));

        $this->repositorioPDO->cadastrar($aluno1);

        $this->expectException(RuntimeException::class);
        $this->repositorioPDO->cadastrar($aluno2);

    }

    public function test_nao_deve_permitir_cadastro_com_email_duplicado()
    {
        $aluno1 = new Aluno('Beatriz', '98765432100', 'bea@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1993-03-03'));
        $aluno2 = new Aluno('Marcos', '78945612300', 'bea@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1994-04-04'));

        $this->repositorioPDO->cadastrar($aluno1);

        $this->expectException(RuntimeException::class);
        $this->repositorioPDO->cadastrar($aluno2);
    }

    public function test_deve_obter_aluno_por_uuid()
    {
        // Arrumar
        $aluno = new Aluno('Paulo', '65432178900', 'paulo@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1985-05-05'));
        $alunoCriado = $this->repositorioPDO->cadastrar($aluno);

        // Agir
        $casoUso = new Obter($this->repositorioPDO);
        $encontrado = $casoUso->executar($alunoCriado->getUuid());

        // Afirmar
        $this->assertNotNull($encontrado);
        $this->assertEquals('Paulo', $encontrado->nome);
    }

    public function test_nao_deve_obter_aluno_por_uuid()
    {
        // Arrumar
        $aluno = GeradorUuid::gerar();

        // Afirmar
        $this->expectException(Exception::class);

        // Agir
        $casoUso = new Obter($this->repositorioPDO);
        $encontrado = $casoUso->executar($aluno);

        // Afirmar
        $this->assertNull($encontrado);

    }

    public function test_deve_atualiza_aluno_por_uuid()
    {
        // Arrumar
        $aluno = new Aluno('Paulo', '65432178900', 'paulo@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1985-05-05'));
        $alunoCriado = $this->repositorioPDO->cadastrar($aluno);
        $input = InputObject::create([
            'uuid' => $alunoCriado->getUuid(),
            'nome' => $alunoCriado->getNome() . " da Silva", // quer atualizar apenas o nome e aniversário
            'cpf' => $alunoCriado->getCpf(),
            'email' => $alunoCriado->getEmail(),
            // 'senha' => $alunoCriado->getSenha(), // senha (com hash) não será atualizada
            'data_nascimento' => '1990-08-19'
        ]);

        // Agir
        $casoUso = new Atualizar($this->repositorioPDO);
        $casoUso->executar($input);

        // Compara com os valores do banco
        $encontrado = $this->repositorioPDO->buscarPorUuid($alunoCriado->getUuid());

        // Afirmar
        $this->assertNotNull($encontrado);
        $this->assertEquals('Paulo da Silva', $encontrado->getNome());
        $this->assertEquals($alunoCriado->getCpf(), $encontrado->getCpf());
        $this->assertEquals($alunoCriado->getEmail(), $encontrado->getEmail());
        $this->assertEquals($alunoCriado->getSenha(), $encontrado->getSenha());
        // Campos atualizados
        $this->assertNotEquals($alunoCriado->getNome(), $encontrado->getNome());
        $this->assertNotEquals($alunoCriado->getDataNascimento(), $encontrado->getDataNascimento());
    }

    public function test_nao_deve_atualiza_cpf_para_outro_ja_existente_no_banco()
    {
        // Arrumar
        $alunoCpfExistente = new Aluno('Paulo', '65432178900', 'paulo@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1985-05-05'));
        $this->repositorioPDO->cadastrar($alunoCpfExistente);

        $aluno = new Aluno('Marcos', '11111111111', 'marcos@teste.com', Senha::criarAPartirDoHash('Senha123!'), new DateTime('1985-05-05'));
        $alunoEditar = $this->repositorioPDO->cadastrar($aluno);

        $input = InputObject::create([
            'uuid' => $alunoEditar->getUuid(),
            'nome' => $alunoEditar->getNome() . " da Silva", // quer atualizar o nome, aniversário e CPF para o de Paulo
            'cpf' => '65432178900',
            'email' => $alunoEditar->getEmail(),
            // 'senha' => $alunoEditar->getSenha(), // senha (com hash) não será atualizada
            'data_nascimento' => '1990-08-19'
        ]);

        // Afirmar
        $this->expectException(Exception::class);

        // Agir
        $casoUso = new Atualizar($this->repositorioPDO);
        $casoUso->executar($input);

        // Compara com os valores do banco
        $encontrado = $this->repositorioPDO->buscarPorUuid($alunoEditar->getUuid());

        // Afirmar
        $this->assertNotNull($encontrado);
        $this->assertEquals('Marcos da Silva', $encontrado->getNome());
        $this->assertEquals($alunoEditar->getCpf(), $encontrado->getCpf());
        $this->assertEquals($alunoEditar->getEmail(), $encontrado->getEmail());
        $this->assertEquals($alunoEditar->getSenha(), $encontrado->getSenha());
        // Campos Não atualizados devido quebra de constraint atualizados
        $this->assertNotEquals($alunoEditar->getCpf(), "65432178900");
    }

    public function test_deve_retornar_paginacao_com_dados_corretos()
    {
        for ($i = 1; $i <= 15; $i++) {
            $nome = "Aluno $i";
            $cpf = str_pad((string) (10000000000 + $i), 11, '0', STR_PAD_LEFT);
            $email = "aluno$i@teste.com";
            $senha = Senha::criarAPartirDoHash("$i+$nome");
            $this->repositorioPDO->cadastrar(new Aluno($nome, $cpf, $email, $senha, new DateTime('2000-01-01')));
        }

        $pagina1 = $this->repositorioPDO->listarPorFiltros('', 0, 10);
        $pagina2 = $this->repositorioPDO->listarPorFiltros('', 10, 10);

        $this->assertEquals(1, $pagina1->paginaAtual);
        $this->assertEquals(10, $pagina1->limite);
        $this->assertEquals(15, $pagina1->totalRegistros);
        $this->assertCount(10, $pagina1->itens);

        $this->assertEquals(2, $pagina2->paginaAtual);
        $this->assertEquals(10, $pagina2->limite);
        $this->assertEquals(15, $pagina2->totalRegistros);
        $this->assertCount(5, $pagina2->itens);
    }

    public function test_deve_retornar_alunos_ordenados_por_nome()
    {
        $nomes = ['Carlos', 'Ana', 'Bruno', 'Daniela', 'Eduardo'];

        foreach ($nomes as $i => $nome) {
            $cpf = str_pad((string) (20000000000 + $i), 11, '0', STR_PAD_LEFT);
            $email = strtolower($nome) . "@teste.com";
            $senha = Senha::criarAPartirDoHash("$i+$nome");
            $this->repositorioPDO->cadastrar(new Aluno($nome, $cpf, $email, $senha, new DateTime('2000-01-01')));
        }

        $pagina = $this->repositorioPDO->listarPorFiltros('', 0, 10);

        $nomesEsperados = ['Ana', 'Bruno', 'Carlos', 'Daniela', 'Eduardo'];
        $nomesRetornados = array_map(fn($aluno) => $aluno->getNome(), $pagina->itens);

        $this->assertEquals($nomesEsperados, $nomesRetornados);

        // Também verifica se o JSON está no formato esperado
        $json = json_encode([
            'paginaAtual' => $pagina->paginaAtual,
            'totalRegistros' => $pagina->totalRegistros,
            'totalPaginas' => ceil($pagina->totalRegistros / $pagina->limite),
            'limite' => $pagina->limite,
            'itens' => array_map(fn($a) => [
                'uuid' => $a->getUuid(),
                'nome' => $a->getNome(),
                'cpf' => $a->getCpf(),
                'email' => $a->getEmail(),
                'dataNascimento' => $a->getDataNascimento()->format('Y-m-d'),
            ], $pagina->itens)
        ]);

        $jsonDecoded = json_decode($json, true);

        $this->assertEquals('Ana', $jsonDecoded['itens'][0]['nome']);
        $this->assertEquals('Bruno', $jsonDecoded['itens'][1]['nome']);
        $this->assertEquals('Eduardo', $jsonDecoded['itens'][4]['nome']);
        $this->assertCount(5, $jsonDecoded['itens']);
    }

}

