<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';
require_once BASE_PATH . '/includes/admin_header.php';

$page_title = 'Пользователи';

// Только для админа
if ($_SESSION['user_email'] !== '1c_is_my_waify@gmail.com') {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

function e($s) { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

// Фильтры
$search = trim($_GET['search'] ?? '');
$role = trim($_GET['role'] ?? '');

// Формируем SQL
$sql = "SELECT id, name, email, role, created_at FROM users WHERE 1";

if ($search) {
    $safe = mysqli_real_escape_string($connect, $search);
    $sql .= " AND (name LIKE '%$safe%' OR email LIKE '%$safe%')";
}

if ($role && in_array($role, ['admin', 'user'])) {
    $sql .= " AND role = '$role'";
}

$sql .= " ORDER BY id DESC";
$result = mysqli_query($connect, $sql);

if (!$result) {
    die("Ошибка запроса: " . mysqli_error($connect));
}
?>

<section class="admin-section">
  <h1>Пользователи</h1>

  <!-- 🔍 Панель поиска и фильтрации -->
  <form method="get" class="filter-form">
    <input type="text" name="search" placeholder="Поиск по имени или email..." value="<?= e($search) ?>" class="filter-input">

    <select name="role" class="filter-select">
      <option value="">Все роли</option>
      <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Администраторы</option>
      <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>Пользователи</option>
    </select>

    <button type="submit" class="btn-filter">Применить</button>
    <a href="users.php" class="btn-reset">Сбросить</a>
  </form>

  <p class="count-info">Найдено пользователей: <strong><?= mysqli_num_rows($result) ?></strong></p>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Дата регистрации</th>
            <th>Действия</th>
          </tr>
        </thead>
        <tbody>
          <?php while($user = mysqli_fetch_assoc($result)): ?>
            <tr class="<?= $user['role'] === 'admin' ? 'admin-row' : '' ?>">
              <td><?= e($user['id']) ?></td>
              <td><?= e($user['name']) ?></td>
              <td><?= e($user['email']) ?></td>
              <td><span class="role-badge <?= $user['role'] ?>"><?= e($user['role'] ?: 'user') ?></span></td>
              <td><?= e($user['created_at'] ?? '—') ?></td>
              <td>
                <?php if ($user['email'] !== '1c_is_my_waify@gmail.com'): ?>
                  <form method="post" action="user_delete.php" onsubmit="return confirm('Удалить пользователя <?= e($user['name']) ?>?');">
                    <input type="hidden" name="id" value="<?= e($user['id']) ?>">
                    <button type="submit" class="btn-delete">Удалить</button>
                  </form>
                <?php else: ?>
                  <span class="badge-admin">Главный админ</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p>Пользователи не найдены.</p>
  <?php endif; ?>
</section>

<?php include BASE_PATH . '/includes/footer.php'; ?>
