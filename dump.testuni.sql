-- SQLite SQL dump
-- Adaptação do dump MySQL
-- Tabela de alunos
CREATE TABLE
    IF NOT EXISTS alunos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        uuid TEXT NOT NULL UNIQUE,
        nome TEXT NOT NULL,
        cpf TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        senha_hash TEXT NOT NULL,
        data_nascimento TEXT NOT NULL, -- SQLite geralmente armazena datas como TEXT (YYYY-MM-DD)
        created_at TEXT DEFAULT CURRENT_TIMESTAMP -- SQLite geralmente armazena timestamps como TEXT (YYYY-MM-DD HH:MM:SS)
    );

-- Tabela de turmas
CREATE TABLE
    IF NOT EXISTS turmas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        uuid TEXT NOT NULL UNIQUE,
        nome TEXT NOT NULL,
        descricao TEXT,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    );

-- Tabela de matrículas
CREATE TABLE
    IF NOT EXISTS matriculas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        uuid TEXT NOT NULL UNIQUE,
        aluno_id INTEGER NOT NULL,
        turma_id INTEGER NOT NULL,
        data_matricula TEXT DEFAULT CURRENT_TIMESTAMP, -- SQLite geralmente armazena datetimes como TEXT
        UNIQUE (aluno_id, turma_id), -- Restrição UNIQUE diretamente na tabela
        FOREIGN KEY (aluno_id) REFERENCES alunos (id) ON DELETE CASCADE,
        FOREIGN KEY (turma_id) REFERENCES turmas (id) ON DELETE CASCADE
    );

-- Tabela de admins
CREATE TABLE
    IF NOT EXISTS admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        uuid TEXT NOT NULL UNIQUE,
        nome TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        senha_hash TEXT NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP
    );