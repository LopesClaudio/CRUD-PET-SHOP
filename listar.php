<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "conexao.php";

try {

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

    $pets = $stmt->fetchAll();

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

?>