<?php
declare(strict_types=1);

session_start();

$dbHost = "localhost";
$dbName = "academic_portfolio";
$dbUser = "root";
$dbPass = "";

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed. Check config.php and import database/academic_portfolio.sql.");
}

function e(?string $value): string {
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}

function is_admin(): bool {
    return isset($_SESSION["admin_id"]);
}

function require_admin(): void {
    if (!is_admin()) {
        header("Location: login.php");
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf"];
}

function check_csrf(): void {
    if (!hash_equals($_SESSION["csrf"] ?? "", $_POST["csrf"] ?? "")) {
        http_response_code(403);
        exit("Invalid security token.");
    }
}

function category_label(string $category): string {
    return match ($category) {
        "quiz" => "Quiz",
        "long_quiz" => "Long Quiz",
        "activity" => "Activities",
        "midterms" => "Midterms",
        "finals" => "Finals",
        "project" => "Projects",
        default => "Other",
    };
}

function upload_file(array $file): array {
    if (($file["error"] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ["path" => null, "type" => null];
    }

    if ($file["error"] !== UPLOAD_ERR_OK) {
        throw new RuntimeException("File upload failed.");
    }

    if ($file["size"] > 20 * 1024 * 1024) {
        throw new RuntimeException("Maximum file size is 20 MB.");
    }

    $allowed = [
        "jpg" => "image/jpeg", "jpeg" => "image/jpeg", "png" => "image/png",
        "webp" => "image/webp", "gif" => "image/gif",
        "pdf" => "application/pdf", "doc" => "application/msword",
        "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "ppt" => "application/vnd.ms-powerpoint",
        "pptx" => "application/vnd.openxmlformats-officedocument.presentationml.presentation",
        "txt" => "text/plain"
    ];

    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    if (!isset($allowed[$ext])) {
        throw new RuntimeException("File type is not allowed.");
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file["tmp_name"]);
    if ($ext !== "txt" && $mime !== $allowed[$ext]) {
        throw new RuntimeException("The uploaded file type could not be verified.");
    }

    $name = bin2hex(random_bytes(16)) . "." . $ext;
    $relative = "assets/uploads/" . $name;
    $destination = __DIR__ . "/" . $relative;

    if (!move_uploaded_file($file["tmp_name"], $destination)) {
        throw new RuntimeException("Could not save uploaded file.");
    }

    return ["path" => $relative, "type" => $mime];
}
