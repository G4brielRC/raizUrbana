<?php
require 'conexao_produtos.php';

// Buscar todas as categorias
$categorias = $pdo->query("SELECT * FROM categoria")->fetchAll(PDO::FETCH_ASSOC);$categorias = $pdo->query("SELECT * FROM categoria")->fetchAll(PDO::FETCH_ASSOC);
$generos = $pdo->query("SELECT * FROM genero")->fetchAll(PDO::FETCH_ASSOC);

// Cadastrar peca ao enviar o form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome_peca = $_POST['nome_peca'];
    $id_genero = $_POST['id_genero'];
    $cor = $_POST['cor'];
    $preco = $_POST['preco'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];

    // Processar a imagem
    $imagem = $_FILES['imagem']['name'];
    $caminho_imagem = 'imagens/' . basename($imagem);

if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_imagem)) {
    //Inserir no banco de dados
    if (empty($id_categoria)) {
    die("Selecione uma categoria válida.");
}

    $sql = "INSERT INTO peca (nome_peca, id_genero, cor, preco, descricao, imagem, id_categoria)
            VALUES (:nome_peca, :id_genero, :cor, :preco, :descricao, :imagem, :id_categoria)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome_peca', $nome_peca);
    $stmt->bindParam(':id_genero', $id_genero, PDO::PARAM_INT);
    $stmt->bindParam(':cor', $cor);
    $stmt->bindParam(':preco', $preco);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':imagem', $caminho_imagem);
    $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
    header("Location: cadastrar_produto.php?sucesso=peca");
    exit();
    } else {
    header("Location: cadastrar_produto.php?erro=peca");
    exit();
}

    }
    else {
        echo "Falha ao enviar a imagem";
    }
}
?>
