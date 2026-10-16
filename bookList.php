<!-- Header -->
<?php include("include/header_buecher.php"); ?>

<?php
require_once __DIR__ . '/include/db.php';

// Search text from the search field (empty = show all books)
$search = trim($_POST['searchtext'] ?? '');
$like = '%' . $search . '%';

// Prepared statement: the search text is sent separately, never inside the SQL
$stmt = $pdo->prepare('
    SELECT books.*, autoren.Name, genre.Genre
    FROM books
    LEFT JOIN autoren ON books.AID = autoren.AID
    LEFT JOIN genre ON books.GID = genre.GID
    WHERE books.Titel LIKE ?
       OR books.OT LIKE ?
       OR books.Jahrgang LIKE ?
       OR books.Verlag LIKE ?
       OR books.Sonstiges LIKE ?
       OR autoren.Name LIKE ?
       OR genre.Genre LIKE ?
    ORDER BY books.Titel
');
$stmt->execute(array_fill(0, 7, $like));
$books = $stmt->fetchAll();
?>

<section class="container my-8">
  <?php if (count($books) === 0): ?>
    <p>Kein Eintrag gefunden.</p>
  <?php else: ?>

    <!-- Mobile: one small table per book -->
    <div class="block xl:hidden">
      <?php foreach ($books as $book): ?>
        <table class="w-full my-4">
          <tr class="bg-red-300"><th>ID</th><td><?= e($book['BID']) ?></td></tr>
          <tr class="bg-red-200"><th>Titel</th><td><?= e($book['Titel']) ?></td></tr>
          <tr class="bg-red-300"><th>Original Titel</th><td><?= e($book['OT']) ?></td></tr>
          <tr class="bg-red-200"><th>Autor</th><td><?= e($book['Name']) ?></td></tr>
          <tr class="bg-red-300"><th>Verlag</th><td><?= e($book['Verlag']) ?></td></tr>
          <tr class="bg-red-200"><th>Jahrgang</th><td><?= e($book['Jahrgang']) ?></td></tr>
          <tr class="bg-red-300"><th>Genre</th><td><?= e($book['Genre']) ?></td></tr>
          <tr class="bg-red-200"><th>Sonstiges</th><td><?= e($book['Sonstiges']) ?></td></tr>
        </table>
      <?php endforeach; ?>
    </div>

    <!-- Desktop: one big table -->
    <table class="hidden xl:table w-full border-2 border-indigo-600 bg-red-100">
      <tr class="border-b-2 border-indigo-600">
        <th>ID</th>
        <th>Titel</th>
        <th>Original Titel</th>
        <th>Autor</th>
        <th>Verlag</th>
        <th>Jahrgang</th>
        <th>Genre</th>
        <th>Sonstiges</th>
      </tr>
      <?php foreach ($books as $book): ?>
        <tr>
          <td><?= e($book['BID']) ?></td>
          <td><?= e($book['Titel']) ?></td>
          <td><?= e($book['OT']) ?></td>
          <td><?= e($book['Name']) ?></td>
          <td><?= e($book['Verlag']) ?></td>
          <td><?= e($book['Jahrgang']) ?></td>
          <td><?= e($book['Genre']) ?></td>
          <td><?= e($book['Sonstiges']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>

  <?php endif; ?>

  <div class="mt-8">
    <a class="p-3 px-6 pt-2 text-white font-bold bg-brightRed rounded-lg hover:bg-red-200" href="javascript:history.back()">Zurück</a>
  </div>
</section>

<!-- FOOTER -->
<?php include("include/footer.php"); ?>
