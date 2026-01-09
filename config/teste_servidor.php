<?php
// teste_servidor.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnóstico do Servidor Hostinger</h1>";

// 1. Verificar Versão do PHP
echo "<h2>1. Versão PHP</h2>";
echo "Versão atual: " . phpversion();

// 2. Verificar Extensões
echo "<h2>2. Extensões Obrigatórias</h2>";
$extensions = ['pdo', 'pdo_pgsql', 'pgsql'];
$all_ok = true;

foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color:green'>✅ Extensão <strong>$ext</strong> está ATIVA.</p>";
    } else {
        echo "<p style='color:red'>❌ Extensão <strong>$ext</strong> NÃO está ativa.</p>";
        $all_ok = false;
    }
}

if (!$all_ok) {
    echo "<h3 style='color:red'>⚠️ PROBLEMA CRÍTICO: Sem a extensão pdo_pgsql, o site NÃO vai funcionar.</h3>";
    echo "<p>Como não tens acesso ao painel, terás de pedir ao dono da conta Hostinger para ativar 'pdo_pgsql' nas configurações PHP.</p>";
    exit; // Para aqui se não tiver extensões
}

// 3. Teste de Conexão Real
echo "<h2>3. Teste de Conexão ao Supabase</h2>";

$host = "aws-1-eu-west-1.pooler.supabase.com";
$port = "5432"; // Testamos a porta padrão primeiro
$db_name = "postgres";
$username = "postgres.zzftdgnxiwqteyqfglzi";
$password = "Pingo2931@";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db_name;sslmode=require";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green; font-weight:bold; font-size:1.2em;'>✅ SUCESSO! A conexão à base de dados foi estabelecida.</p>";
    echo "<p>Podes fazer o upload do projeto à vontade.</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Erro na conexão: " . $e->getMessage() . "</p>";
    echo "<p>Dica: O servidor tem as extensões, mas a firewall pode estar a bloquear ou a password estar errada.</p>";
}
?>