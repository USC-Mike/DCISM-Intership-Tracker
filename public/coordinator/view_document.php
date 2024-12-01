<?php
require_once '../../src/controllers/coordinatorcontroller.php';

if (isset($_GET['document_id'])) {
    $documentId = $_GET['document_id'];

    $stmt = $pdo->prepare("SELECT document_content, document_name FROM documents WHERE id = ?");
    $stmt->execute([$documentId]);
    $document = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($document) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $document['document_name'] . '"');
        echo $document['document_content'];
        exit();
    } else {
        echo "Document not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
