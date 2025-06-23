<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header class="navbar">
  <section class="container nav-content">
    <div class="logo">
      <a href="./index.php" aria-label="Shangya Home">
        <section class="logo">
          <a href="./index.php"
            ><img src="./images/shangya-logo.avif" alt="shangya-logo"
          /></a>
        </section>
      </a>
    </div>
    <input type="checkbox" class="menu-toggle" id="menu-toggle" />
    <label
      for="menu-toggle"
      class="hamburger"
      aria-label="Toggle navigation menu"
    >
      <span></span>
      <span></span>
      <span></span>
    </label>
    <nav class="nav-links">
      <?php include 'menu.inc'; ?>
    </nav>
  </section>
</header>
