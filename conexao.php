<?php
function loadDotEnv(string $path): void
{
  if (!file_exists($path)) return;
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#') continue;
    if (strpos($line, '=') === false) continue;
    list($name, $value) = explode('=', $line, 2);
    $name = trim($name);
    $value = trim($value);

    if ((isset($value[0]) && ($value[0] === '"' || $value[0] === "'")) && substr($value, -1) === $value[0]) {
      $value = substr($value, 1, -1);
    }
    if (getenv($name) === false) {
      putenv("$name=$value");
      $_ENV[$name] = $value;
      $_SERVER[$name] = $value;
    }
  }
}
// carregar .env local (se existir)
loadDotEnv(__DIR__ . '/.env');

$envDatabaseUrl = getenv('DATABASE_URL') ?: getenv('SUPABASE_DATABASE_URL');
if ($envDatabaseUrl) {
  $url = parse_url($envDatabaseUrl);
  $host = $url['host'] ?? '';
  $port = $url['port'] ?? '5432';
  $db   = isset($url['path']) ? ltrim($url['path'], '/') : '';
  $user = $url['user'] ?? '';
  $pass = $url['pass'] ?? '';
} else {
  // Se não existe DATABASE_URL, tentamos as variáveis separadas.
  $host = getenv('SUPABASE_HOST') ?: null;
  $db   = getenv('SUPABASE_DB') ?: null;
  $user = getenv('SUPABASE_USER') ?: null;
  $port = getenv('SUPABASE_PORT') ?: '5432';
  $pass = getenv('SUPABASE_PASS') ?: null;

  // Falha imediatamente com mensagem clara se as variáveis essenciais estiverem em falta
  if (!$host || !$db || !$user || !$pass) {
    die("❌ Credenciais de base de dados ausentes. Copia '.env.example' para '.env' e preenche DATABASE_URL ou as variáveis SUPABASE_*. Não comites '.env'.");
  }
}

// String de Conexão (DSN) — força sslmode=require para TLS
$dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";


// Opções do PDO (Para mostrar erros e facilitar o uso)
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança erros se algo falhar
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Retorna arrays associativos (['nome' => 'Ricardo'])
  PDO::ATTR_EMULATE_PREPARES   => false,                 // Segurança contra SQL Injection
];

try {
  // Tenta criar a conexão
  $pdo = new PDO($dsn, $user, $pass, $options);

  echo "✅ Sucesso! Conectado à base de dados do Check&Go.";
} catch (PDOException $e) {

  die("❌ Erro na conexão: " . $e->getMessage());
}