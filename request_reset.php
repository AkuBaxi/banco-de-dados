<?php
header('Content-type: text/html; charset=utf-8');

// Credenciais do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabalhoprova";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obter dados do formulário
$email = $_POST['email'];

// Verificar se o e-mail existe no banco de dados
$sql = "SELECT id FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Gerar token de redefinição
    $token = bin2hex(random_bytes(50));

    // Salvar o token no banco de dados com a data de expiração
    $sql = "UPDATE usuarios SET reset_token = ?, reset_token_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $email);
    $stmt->execute();

    // Enviar email com o link de redefinição (supondo que a função mail esteja configurada)
    $reset_link = "http://localhost/reset_password.php?token=" . $token; //link para combinar com o token
    $subject = '=?utf-8?B?' . base64_encode("Redefinição de Senha") . '?=';
    // Codificar o assunto em UTF-8
    $encoded_subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';        
    $message = "Clique no link para redefinir sua senha: " . $reset_link;
    $headers = "From: resetpasskaio@hotmail.com"; //email da empresa para redefinir senha // seuemail@hotmail.com


    mail($email, $subject, $message, $headers); //envia o email de recuperação 
    header("Location: email_request.html");
} else {
    header("Location: email_not_request.html");

    //echo "Nenhuma conta encontrada com esse endereço de e-mail.";
}

$conn->close();
?>