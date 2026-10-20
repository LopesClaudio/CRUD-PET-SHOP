<?php

header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$banco = "crud_petshop";
$usuario = "root";
$senha = "97b3@o519mJ";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $busca = trim($_GET["busca"] ?? "");

    if ($busca === "") {
        $sql = "SELECT * FROM pets ORDER BY id DESC";
        $stmt = $pdo->query($sql);
    } else {
        $sql = "SELECT * FROM pets
                WHERE pet LIKE :busca
                ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":busca" => "%$busca%"
        ]);
    }

    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "sucesso" => true,
        "pets" => $pets
    ]);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao buscar os pets."
    ]);
}