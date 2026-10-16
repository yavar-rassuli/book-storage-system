<!-- Header -->
<?php include("include/header_buecher.php"); ?>

<?php
require_once __DIR__ . '/include/db.php';

// Genres for the select field, straight from the database
$genres = $pdo->query('SELECT Genre FROM genre ORDER BY Genre')->fetchAll();

$message = '';
$savedBook = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Read the form fields
    $titel = trim($_POST['titel'] ?? '');
    $otitel = trim($_POST['otitel'] ?? '');
    $autor = trim($_POST['autor'] ?? '');
    $verlag = trim($_POST['verlag'] ?? '');
    $jahrgang = trim($_POST['jahrgang'] ?? '');
    $genre = $_POST['genre'] ?? '';
    $sonstiges = trim($_POST['sonstiges'] ?? '');

    // 2. Is the book already saved?
    $stmt = $pdo->prepare('SELECT BID FROM books WHERE Titel = ?');
    $stmt->execute([$titel]);

    if ($stmt->fetchColumn() !== false) {
        $message = 'Dieses Buch ist bereits vorhanden.';
    } else {
        // 3. Find the author, or create a new one
        $stmt = $pdo->prepare('SELECT AID FROM autoren WHERE Name = ?');
        $stmt->execute([$autor]);
        $aid = $stmt->fetchColumn();

        if ($aid === false) {
            $stmt = $pdo->prepare('INSERT INTO autoren (Name) VALUES (?)');
            $stmt->execute([$autor]);
            $aid = $pdo->lastInsertId();
        }

        // 4. Find the genre (null if someone sends an unknown genre)
        $stmt = $pdo->prepare('SELECT GID FROM genre WHERE Genre = ?');
        $stmt->execute([$genre]);
        $gid = $stmt->fetchColumn() ?: null;

        // 5. Save the book
        $stmt = $pdo->prepare('
            INSERT INTO books (Titel, OT, Jahrgang, Verlag, Sonstiges, AID, GID)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([$titel, $otitel, $jahrgang, $verlag, $sonstiges, $aid, $gid]);

        $message = 'Das Buch wurde gespeichert.';
        $savedBook = [
            'BID' => $pdo->lastInsertId(),
            'Titel' => $titel,
            'OT' => $otitel,
            'Autor' => $autor,
            'Verlag' => $verlag,
            'Jahrgang' => $jahrgang,
            'Genre' => $genre,
            'Sonstiges' => $sonstiges,
        ];
    }
}
?>

<section class="seite" id="hero">
  <div class="container mt-4">
    <h1 class="display-4 text-center">
      <i class="fas fa-book-open text-brightRed"></i>
      Neues <span class="text-brightRed">Buch</span> erfassen
    </h1>

    <?php if ($message): ?>
      <p class="my-4 p-3 font-bold bg-red-200"><?= e($message) ?></p>
    <?php endif; ?>

    <?php if ($savedBook): ?>
      <table class="w-full my-4">
        <tr class="bg-red-300"><th>ID</th><td><?= e($savedBook['BID']) ?></td></tr>
        <tr class="bg-red-200"><th>Titel</th><td><?= e($savedBook['Titel']) ?></td></tr>
        <tr class="bg-red-300"><th>Original Titel</th><td><?= e($savedBook['OT']) ?></td></tr>
        <tr class="bg-red-200"><th>Autor</th><td><?= e($savedBook['Autor']) ?></td></tr>
        <tr class="bg-red-300"><th>Verlag</th><td><?= e($savedBook['Verlag']) ?></td></tr>
        <tr class="bg-red-200"><th>Jahrgang</th><td><?= e($savedBook['Jahrgang']) ?></td></tr>
        <tr class="bg-red-300"><th>Genre</th><td><?= e($savedBook['Genre']) ?></td></tr>
        <tr class="bg-red-200"><th>Sonstiges</th><td><?= e($savedBook['Sonstiges']) ?></td></tr>
      </table>
    <?php endif; ?>

    <form action="buecher.php" method="post">
      <div class="form-group">
        <label for="titel">Titel</label>
        <input type="text" id="titel" name="titel" required class="form-control">
      </div>
      <div class="form-group">
        <label for="otitel">Original Titel</label>
        <input type="text" id="otitel" name="otitel" required class="form-control">
      </div>
      <div class="form-group">
        <label for="autor">Autor</label>
        <input type="text" id="autor" name="autor" required class="form-control">
      </div>
      <div class="form-group">
        <label for="verlag">Verlag</label>
        <input type="text" id="verlag" name="verlag" required class="form-control">
      </div>
      <div class="form-group">
        <label for="jahrgang">Jahrgang</label>
        <input type="text" id="jahrgang" name="jahrgang" class="form-control">
      </div>
      <div class="form-group">
        <label for="genre">Genre</label>
        <select id="genre" name="genre" class="form-control">
          <?php foreach ($genres as $g): ?>
            <option value="<?= e($g['Genre']) ?>"><?= e($g['Genre']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group ">
        <label for="sonstiges">Sonstiges</label>
        <textarea id="sonstiges" name="sonstiges" class="form-control" rows="6"></textarea>
      </div>
      <div class="mb-8">
            <input type="submit" value="Abschicken" class="p-3 px-5 pt-2 text-white bg-brightRed rounded-full hover:bg-brightRedLight">
            <input type="reset" value="Zurücksetzen" class="p-3 px-5 pt-2 text-white bg-brightRed rounded-full hover:bg-brightRedLight">
      </div>

    </form>

  </div>
</section>

<!-- FOOTER -->
<?php include("include/footer.php"); ?>
