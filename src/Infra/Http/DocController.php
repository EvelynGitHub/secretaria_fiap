<?php

namespace SecretariaFiap\Infra\Http;

class DocController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="API da Secretaria Fiap",
     *      description="Documentação da API para gerenciamento de alunos, turmas e matrículas na Secretaria Fiap."
     * )
     * @OA\Server(
     *      url="http://localhost:8080/api",
     *      description="Servidor de Desenvolvimento Local"
     * )
     *
     * // --- Definição do Componente de Segurança JWT ---
     * @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      type="http",
     *      description="Autenticação JWT com token Bearer. Muita atenção para não colocar caracteres a mais ou a menos para o token.",
     *      scheme="bearer",
     *      bearerFormat="JWT"
     * )
     * 
     */
    public $token;

    /**
     * @OA\Tag(
     *      name="Admin",
     *      description="Operações e recursos de administração do sistema."
     * )
     * @OA\Tag(
     *      name="Aluno",
     *      description="Operações relacionadas à gestão de alunos, incluindo listagem, criação e atualização."
     * )
     * @OA\Tag(
     *      name="Turma",
     *      description="Gerenciamento de turmas, suas informações e alunos associados."
     * )
     * @OA\Tag(
     *      name="Matricula",
     *      description="Gerenciamento de matrículas de alunos em turmas, incluindo status e histórico."
     * )
     */
    public $tags;

    // --- Definições de Schemas (Modelos de Dados) para Aluno ---

    /**
     * @OA\Schema(
     *      schema="ErroResponse",
     *      title="Resposta de Erro Padrão",
     *      description="Estrutura padrão para mensagens de erro da API.",
     *      type="object",
     *      @OA\Property(property="message", type="string", example="Não foi possível atualizar o Aluno informado.", description="Mensagem descritiva do erro."),
     *      @OA\Property(property="status_code", type="integer", example=500, description="Código de status HTTP da resposta."),
     *      @OA\Property(property="file", type="string", example="/var/www/html/src/Core/CasosUso/Aluno/Atualizar.php", nullable=true, description="Arquivo onde o erro ocorreu (apenas em ambientes de desenvolvimento)."),
     *      @OA\Property(property="line", type="integer", example=51, nullable=true, description="Linha do arquivo onde o erro ocorreu (apenas em ambientes de desenvolvimento).")
     * ),
     * @OA\Schema(
     *      schema="AlunoInput",
     *      title="Dados de Entrada do Aluno",
     *      description="Estrutura para criação e atualização de um aluno. A senha é obrigatória na criação.",
     *      type="object",
     *      required={"nome", "cpf", "email", "senha", "data_nascimento"},
     *      @OA\Property(property="nome", type="string", example="Abel"),
     *      @OA\Property(property="cpf", type="string", pattern="^\d{11}$", example="12334567812", description="CPF com 11 dígitos, apenas números."),
     *      @OA\Property(property="email", type="string", format="email", example="abel@email.com"),
     *      @OA\Property(property="senha", type="string", format="password", minLength=8, example="abc123W!", description="Senha com no mínimo 8 caracteres."),
     *      @OA\Property(property="data_nascimento", type="string", format="date", pattern="^\d{2}-\d{2}-\d{4}$", example="08-02-1997", description="Data de nascimento no formato DD-MM-YYYY.")
     * ),
     * @OA\Schema(
     *      schema="AlunoOutput",
     *      title="Dados de Saída do Aluno",
     *      description="Estrutura de um aluno retornado pela API.",
     *      type="object",
     *      @OA\Property(property="uuid", type="string", format="uuid", example="52f7b495-458d-11f0-a0d1-4208e9f4cc4d", description="Identificador único do aluno."),
     *      @OA\Property(property="nome", type="string", example="Ana Souza Silva"),
     *      @OA\Property(property="cpf", type="string", example="12345678901"),
     *      @OA\Property(property="email", type="string", format="email", example="ana@exemplo.com"),
     *      @OA\Property(property="dataNascimento", type="string", format="date", example="2000-05-12", description="Data de nascimento no formato YYYY-MM-DD.")
     * ),
     * @OA\Schema(
     *      schema="PaginacaoMeta",
     *      title="Metadados de Paginação",
     *      description="Informações sobre a paginação dos resultados.",
     *      type="object",
     *      @OA\Property(property="paginaAtual", type="integer", example=1, description="Número da página atual."),
     *      @OA\Property(property="totalRegistros", type="integer", example=1, description="Total de registros encontrados sem considerar o limite."),
     *      @OA\Property(property="totalPaginas", type="integer", example=1, description="Total de páginas disponíveis."),
     *      @OA\Property(property="limite", type="integer", example=10, description="Limite de registros por página.")
     * ),
     * @OA\Schema(
     *      schema="AlunoListagemResponse",
     *      title="Resposta de Listagem de Alunos Paginada",
     *      description="Estrutura da resposta para listagem paginada de alunos.",
     *      type="object",
     *      allOf={
     *          @OA\Schema(ref="#/components/schemas/PaginacaoMeta"),
     *          @OA\Schema(
     *              properties={
     *                  @OA\Property(
     *                      property="itens",
     *                      type="array",
     *                      description="Lista de objetos Aluno.",
     *                      @OA\Items(ref="#/components/schemas/AlunoOutput")
     *                  )
     *              }
     *          )
     *      }
     * ),
     */
    public $esquemaAluno;


    // --- GET /alunos ---
    /**
     * @OA\Get(
     *      path="/alunos",
     *      summary="Lista todos os alunos com filtros e paginação",
     *      tags={"Aluno"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="nome",
     *          in="query",
     *          description="Filtra alunos por nome (suporta busca parcial, ex: 'Ana').",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="offset",
     *          in="query",
     *          description="Deslocamento para a paginação (início dos resultados). Use 0 para a primeira página.",
     *          required=false,
     *          @OA\Schema(type="integer", default=0)
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Número máximo de registros por página.",
     *          required=false,
     *          @OA\Schema(type="integer", default=10)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Lista de alunos paginada com sucesso.",
     *          @OA\JsonContent(ref="#/components/schemas/AlunoListagemResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $listarAlunos;

    // --- POST /alunos ---
    /**
     * @OA\Post(
     *      path="/alunos",
     *      summary="Cadastra um novo aluno",
     *      tags={"Aluno"},
     * security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          description="Dados do aluno a ser cadastrado. Todos os campos são obrigatórios.",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AlunoInput")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Aluno cadastrado com sucesso. Retorna os dados do aluno criado, incluindo o UUID.",
     *          @OA\JsonContent(ref="#/components/schemas/AlunoOutput")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida. Ocorre se houver dados faltando, em formato incorreto (ex: CPF inválido), ou se o email/CPF já estiverem cadastrados.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor ao tentar cadastrar o aluno.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $cadastrarAluno;

    // --- GET /alunos/{uuid} ---
    /**
     * @OA\Get(
     *      path="/alunos/{uuid}",
     *      summary="Obtém os detalhes de um aluno específico por UUID",
     *      tags={"Aluno"},
     * security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID (Identificador Único Universal) do aluno a ser obtido.",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Detalhes do aluno encontrado.",
     *          @OA\JsonContent(ref="#/components/schemas/AlunoOutput")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Aluno não encontrado com o UUID informado.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor ao tentar obter o aluno.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $obterAluno;

    // --- PUT /alunos/{uuid} ---
    /**
     * @OA\Put(
     *      path="/alunos/{uuid}",
     *      summary="Atualiza as informações de um aluno existente",
     *      tags={"Aluno"},
     * security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID do aluno a ser atualizado.",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid")
     *      ),
     *      @OA\RequestBody(
     *          description="Dados do aluno a serem atualizados. A senha pode ser incluída para alteração ou omitida para manter a atual.",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AlunoInput")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Aluno atualizado com sucesso. Retorna os dados atualizados do aluno.",
     *          @OA\JsonContent(ref="#/components/schemas/AlunoOutput")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida (dados em formato incorreto ou UUID não corresponde a um aluno válido).",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Aluno não encontrado com o UUID informado para atualização.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor ao tentar atualizar o aluno.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $atualizarAluno;

    // --- DELETE /alunos/{uuid} ---
    /**
     * @OA\Delete(
     *      path="/alunos/{uuid}",
     *      summary="Remove um aluno específico por UUID",
     *      tags={"Aluno"},
     * security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID do aluno a ser removido.",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Aluno removido com sucesso. Nenhuma resposta de conteúdo."
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor ao tentar remover o aluno.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $removerAluno;

    // --- GET /alunos/turma/{uuid_turma} ---
    /**
     * @OA\Get(
     *      path="/alunos/turma/{uuid_turma}",
     *      summary="Lista alunos associados a uma turma específica",
     *      tags={"Aluno"},
     * security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="uuid_turma",
     *          in="path",
     *          description="UUID da turma para listar os alunos associados.",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid")
     *      ),
     *      @OA\Parameter(
     *          name="offset",
     *          in="query",
     *          description="Deslocamento para a paginação (início dos resultados).",
     *          required=false,
     *          @OA\Schema(type="integer", default=0)
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Número máximo de registros por página.",
     *          required=false,
     *          @OA\Schema(type="integer", default=10)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Lista paginada de alunos da turma com sucesso.",
     *          @OA\JsonContent(ref="#/components/schemas/AlunoListagemResponse")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Turma não encontrada com o UUID informado.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor ao tentar listar os alunos da turma.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $listarAlunoPorTurma;

    // --- Definições de Schemas (Modelos de Dados) para Aluno ---

    /**
     * @OA\Schema(
     *      schema="TurmaInput",
     *      title="Dados de Entrada da Turma",
     *      description="Estrutura para criação e atualização de uma turma.",
     *      type="object",
     *      required={"nome", "descricao"},
     *      @OA\Property(property="nome", type="string", example="Matemática Avançada"),
     *      @OA\Property(property="descricao", type="integer", example="Matemática Avançada para IA", description="descricao letivo da turma")
     * ),
     * 
     * @OA\Schema(
     *      schema="TurmaOutput",
     *      title="Dados de Saída da Turma",
     *      description="Estrutura de uma turma retornada pela API.",
     *      type="object",
     *      @OA\Property(property="uuid", type="string", format="uuid", example="a1b2c3d4-e5f6-7890-1234-567890abcdef"),
     *      @OA\Property(property="nome", type="string", example="Matemática Avançada"),
     *      @OA\Property(property="descricao", type="string", example="Matemática Avançada para IA"),
     *      @OA\Property(property="qtd_alunos", type="integer", example=1)
     * ),
     * 
     * @OA\Schema(
     *      schema="TurmaListagemResponse",
     *      title="Resposta de Listagem de Turmas Paginada",
     *      description="Estrutura da resposta para listagem paginada de turmas.",
     *      type="object",
     *      allOf={
     *          @OA\Schema(ref="#/components/schemas/PaginacaoMeta"),
     *          @OA\Schema(
     *              properties={
     *                  @OA\Property(
     *                      property="itens",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/TurmaOutput")
     *                  )
     *              }
     *          )
     *      }
     * )
     */
    public $esquemaTurma;


    /**
     * @OA\Schema(
     *      schema="MatriculaInput",
     *      title="Dados de Entrada da Matrícula",
     *      description="Estrutura para matricular um aluno em uma turma.",
     *      type="object",
     *      required={"uuid_aluno", "uuid_turma"},
     *      @OA\Property(property="uuid_aluno", type="string", format="uuid", example="52f7b495-458d-11f0-a0d1-4208e9f4cc4d", description="UUID do aluno a ser matriculado"),
     *      @OA\Property(property="uuid_turma", type="string", format="uuid", example="a1b2c3d4-e5f6-7890-1234-567890abcdef", description="UUID da turma onde o aluno será matriculado")
     * ),
     * @OA\Schema(
     *      schema="MatriculaOutput",
     *      title="Dados de Saída da Matrícula",
     *      description="Estrutura de uma matrícula retornada pela API.",
     *      type="object",
     *      @OA\Property(property="mensagem", type="string", format="mensagem", example="Matricula realizada")
     * )
     */
    public $esquemaMatricula;

    /**
     * @OA\Schema(
     *      schema="AdminLoginInput",
     *      title="Credenciais de Login do Admin",
     *      description="Estrutura para as credenciais de autenticação de um administrador.",
     *      type="object",
     *      required={"email", "senha"},
     *      @OA\Property(property="email", type="string", format="email", example="admin@fiap.com.br"),
     *      @OA\Property(property="senha", type="string", format="password", example="Admin@123")
     * ),
     * 
     * @OA\Schema(
     *      schema="AdminLoginResponse",
     *      title="Resposta de Login do Admin",
     *      description="Estrutura da resposta após um login de administrador bem-sucedido, incluindo o token JWT.",
     *      type="object",
     *      @OA\Property(property="uuid", type="string", format="uuid", example="a1b2c3d4-e5f6-7890-1234-0987654321ab", description="UUID do administrador logado."),
     *      @OA\Property(property="nome", type="string", example="Administrador Fiap", description="Nome completo do administrador."),
     *      @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkFkbWluIiwiYWRtaW4iOnRydWV9.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c", description="Token JWT para autenticação em requisições futuras.")
     * )
     */
    public $esquemaAdmin;

    // --- POST /turmas ---
    /**
     * @OA\Post(
     *      path="/turmas",
     *      summary="Cadastra uma nova turma",
     *      tags={"Turma"},
     *      security={{"bearerAuth": {}}},
     *      @OA\RequestBody(
     *          description="Dados da turma a ser cadastrada",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TurmaInput")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Turma cadastrada com sucesso.",
     *          @OA\JsonContent(ref="#/components/schemas/TurmaOutput")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida (dados faltando ou em formato incorreto).",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $cadastrarTurma;

    // --- GET /turmas ---
    /**
     * @OA\Get(
     *      path="/turmas",
     *      summary="Lista todas as turmas com filtros e paginação",
     *      tags={"Turma"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="nome",
     *          in="query",
     *          description="Filtra turmas por nome (suporta busca parcial).",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="offset",
     *          in="query",
     *          description="Deslocamento para a paginação (início dos resultados).",
     *          required=false,
     *          @OA\Schema(type="integer", default=0)
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          description="Número máximo de registros por página.",
     *          required=false,
     *          @OA\Schema(type="integer", default=10)
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Lista de turmas paginada com sucesso.",
     *          @OA\JsonContent(ref="#/components/schemas/TurmaListagemResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $listarTurma;

    // --- GET /turmas/{uuid} ---
    /**
     * @OA\Get(
     *      path="/turmas/{uuid}",
     *      summary="Obtém os detalhes de uma turma específica por UUID",
     *      tags={"Turma"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID da turma a ser obtida.",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Detalhes da turma.",
     *          @OA\JsonContent(ref="#/components/schemas/TurmaOutput")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Turma não encontrada.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $obterTurmas;

    // --- PUT /turmas/{uuid} ---
    /**
     * @OA\Put(
     *      path="/turmas/{uuid}",
     *      summary="Atualiza as informações de uma turma existente",
     *      tags={"Turma"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID da turma a ser atualizada.",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid")
     *      ),
     *      @OA\RequestBody(
     *          description="Dados da turma a serem atualizados",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TurmaInput")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Turma atualizada com sucesso.",
     *          @OA\JsonContent(ref="#/components/schemas/TurmaOutput")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Turma não encontrada.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $atualizarTurma;

    // --- DELETE /turmas/{uuid} ---
    /**
     * @OA\Delete(
     *      path="/turmas/{uuid}",
     *      summary="Remove uma turma específica por UUID",
     *      tags={"Turma"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Parameter(
     *          name="uuid",
     *          in="path",
     *          description="UUID da turma a ser removida.",
     *          required=true,
     *          @OA\Schema(type="string", format="uuid")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Turma removida com sucesso (Sem Conteúdo)."
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Turma não encontrada.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $removerTurma;

    /**
     * @OA\Post(
     *      path="/matriculas",
     *      summary="Matricula um aluno em uma turma",
     *      tags={"Matricula"},
     *      @OA\RequestBody(
     *          description="Dados da matrícula a ser criada",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/MatriculaInput")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Aluno matriculado com sucesso.",
     *          @OA\JsonContent(ref="#/components/schemas/MatriculaOutput")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Requisição inválida (dados faltando, aluno ou turma não encontrados, ou aluno já matriculado).",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Aluno ou Turma não encontrados.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $matricularAluno;



    /**
     * @OA\Post(
     *      path="/admin/login",
     *      summary="Autentica um administrador e retorna um token JWT",
     *      tags={"Admin"},
     *      @OA\RequestBody(
     *          description="Credenciais (email e senha) do administrador para login. As de exemplo já são validas.",
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AdminLoginInput")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login de administrador bem-sucedido. Retorna o token JWT e dados do admin.",
     *          @OA\JsonContent(ref="#/components/schemas/AdminLoginResponse")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Credenciais de administrador inválidas (email ou senha incorretos).",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor ao tentar autenticar o administrador.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $login;

    /**
     * @OA\Post(
     *      path="/admin/logout",
     *      summary="Invalida o token JWT",
     *      tags={"Admin"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Logout de administrador bem-sucedido.",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                property="mensagem", 
     *                type="string",
     *                format="mensagem",
     *                example="Logout efetuado com sucesso.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno do servidor.",
     *          @OA\JsonContent(ref="#/components/schemas/ErroResponse")
     *      )
     * )
     */
    public $logout;

}
