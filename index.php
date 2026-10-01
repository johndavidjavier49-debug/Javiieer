<?php
require_once "config.php";

$stmt = $pdo->query("SELECT * FROM portfolio_items ORDER BY uploaded_at DESC");
$items = $stmt->fetchAll();

$profile = $pdo->query("SELECT * FROM profile LIMIT 1")->fetch() ?: [
    "name" => "Your Name",
    "tagline" => "Student • Creator • Future Professional",
    "bio" => "Welcome to my academic portfolio.",
    "education" => "Your Course / School",
    "skills" => "HTML, CSS, JavaScript, PHP, MySQL"
];

$grouped = [];
foreach ($items as $item) {
    $grouped[$item["category"]][] = $item;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($profile["name"]) ?> — Academic Portfolio</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="navbar">
  <a class="brand" href="#home"><?= e($profile["name"]) ?><span>.</span></a>
  <button class="menu-toggle" aria-label="Menu">☰</button>
  <nav>
    <a href="#home">Home</a>
    <a href="#about">About</a>
    <a href="#portfolio">Portfolio</a>
    <a href="#contact">Contact</a>
  </nav>
</header>

<main>
<section id="home" class="hero">
  <div class="hero-glow"></div>
  <div class="hero-content reveal">
    <p class="eyebrow">MY ACADEMIC PORTFOLIO</p>
    <h1><?= e($profile["name"]) ?></h1>
    <h2><?= e($profile["tagline"]) ?></h2>
    <p><?= e($profile["bio"]) ?></p>
    <div class="hero-actions">
      <a class="btn primary" href="#portfolio">Explore My Work</a>
      <a class="btn ghost" href="#about">About Me</a>
    </div>
  </div>
  <div class="floating-card card-one">QUIZ</div>
  <div class="floating-card card-two">PROJECT</div>
  <div class="floating-card card-three">FINALS</div>
</section>

<section id="about" class="section">
  <div class="section-heading reveal">
    <p class="eyebrow">GET TO KNOW ME</p>
    <h2>About Me</h2>
  </div>
  <div class="about-grid">
    <article class="about-card reveal">
      <span class="icon">✦</span>
      <h3>Introduction</h3>
      <p><?= e($profile["bio"]) ?></p>
    </article>
    <article class="about-card reveal">
      <span class="icon">⌂</span>
      <h3>Education</h3>
      <p><?= e($profile["education"]) ?></p>
    </article>
    <article class="about-card reveal">
      <span class="icon">✧</span>
      <h3>Skills</h3>
      <p><?= e($profile["skills"]) ?></p>
    </article>
  </div>
</section>

<section id="portfolio" class="section portfolio-section">
  <div class="section-heading reveal">
    <p class="eyebrow">MY WORK</p>
    <h2>Academic Portfolio</h2>
    <p>Browse my quizzes, activities, examinations, and projects.</p>
  </div>

  <div class="filters reveal">
    <button class="filter active" data-filter="all">All</button>
    <button class="filter" data-filter="quiz">Quiz</button>
    <button class="filter" data-filter="long_quiz">Long Quiz</button>
    <button class="filter" data-filter="activity">Activities</button>
    <button class="filter" data-filter="midterms">Midterms</button>
    <button class="filter" data-filter="finals">Finals</button>
    <button class="filter" data-filter="project">Projects</button>
  </div>

  <div class="portfolio-grid" id="portfolioGrid">
  <?php foreach ($items as $item): ?>
    <article class="portfolio-card reveal" data-category="<?= e($item["category"]) ?>">
      <?php if (str_starts_with($item["file_type"] ?? "", "image/")): ?>
        <div class="thumbnail"><img src="<?= e($item["file_path"]) ?>" alt="<?= e($item["title"]) ?>"></div>
      <?php else: ?>
        <div class="file-thumbnail"><span><?= strtoupper(e(pathinfo($item["file_path"], PATHINFO_EXTENSION))) ?></span></div>
      <?php endif; ?>
      <div class="portfolio-body">
        <span class="tag"><?= e(category_label($item["category"])) ?></span>
        <h3><?= e($item["title"]) ?></h3>
        <p><?= e($item["description"]) ?></p>
        <div class="card-actions">
          <a class="small-btn" href="<?= e($item["file_path"]) ?>" target="_blank">View</a>
          <a class="small-btn dark" href="<?= e($item["file_path"]) ?>" download>Download</a>
        </div>
      </div>
    </article>
  <?php endforeach; ?>
  </div>

  <?php if (!$items): ?>
    <div class="empty-state">No portfolio items have been uploaded yet.</div>
  <?php endif; ?>
</section>

<section id="contact" class="section contact-section">
  <div class="contact-box reveal">
    <p class="eyebrow">LET'S CONNECT</p>
    <h2>Thanks for visiting.</h2>
    <p>This portfolio presents my academic progress, activities, examinations, and projects.</p>
    <a class="btn primary" href="mailto:your-email@example.com">Contact Me</a>
  </div>
</section>
</main>

<footer>
  <p>© <?= date("Y") ?> <?= e($profile["name"]) ?>. Academic Portfolio.</p>
  <a href="admin/login.php">Admin</a>
</footer>

<script src="assets/js/app.js"></script>
</body>
</html>
