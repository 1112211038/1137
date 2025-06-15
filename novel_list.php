<?php
session_start();

// ✅ 強制登入保護（除了登入與註冊頁）
if (!isset($_SESSION['loggedin']) && !in_array($_GET['page'] ?? '', ['login', 'register'])) {
    header("Location: ?page=login");
    exit;
}

// ✅ 資料庫連線設定
$host = 'sql112.infinityfree.com';
$user = 'if0_39080830';
$password = 'ROtLh6BZ2TOjV';
$dbname = 'if0_39080830_1137data';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) die("連線失敗: " . $conn->connect_error);
$conn->set_charset("utf8mb4");

// ✅ 新增小說
if (isset($_POST['add']) && isset($_SESSION["loggedin"])) {
    $stmt = $conn->prepare("INSERT INTO `horror novels` (title, year, writer, Publisher, price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sissi", $_POST['title'], $_POST['year'], $_POST['writer'], $_POST['Publisher'], $_POST['price']);
    $stmt->execute();
    header("Location: novel_list.php");
    exit;
}

// ✅ 更新小說
if (isset($_POST['update']) && isset($_SESSION["loggedin"])) {
    $stmt = $conn->prepare("UPDATE `horror novels` SET title=?, year=?, writer=?, Publisher=?, price=? WHERE ID=?");
    $stmt->bind_param("sissii", $_POST['title'], $_POST['year'], $_POST['writer'], $_POST['Publisher'], $_POST['price'], $_POST['ID']);
    $stmt->execute();
    header("Location: novel_list.php");
    exit;
}

// ✅ 刪除小說
if (isset($_GET['delete']) && isset($_SESSION["loggedin"])) {
    $stmt = $conn->prepare("DELETE FROM `horror novels` WHERE ID=?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: novel_list.php?p=" . ($_GET['p'] ?? 1));
    exit;
}

// ✅ 登出
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];
    session_destroy();
    header("Refresh:2; url=?page=login");
    echo "登出中...請稍候";
    exit;
}

// ✅ 註冊
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doRegister'])) {
    $username = trim($_POST["username"]);
    $userpass = trim($_POST["userpass"]);
    $usercnfm = trim($_POST["usercnfm"]);
    $errors = [];

    if (empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "請輸入有效帳號（只能包含英文數字底線）";
    }
    if (strlen($userpass) < 6) {
        $errors[] = "密碼至少6位數";
    }
    if ($userpass !== $usercnfm) {
        $errors[] = "兩次密碼輸入不一致";
    }

    if (empty($errors)) {
        $check = $conn->prepare("SELECT id FROM users WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $errors[] = "帳號已存在";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, userpass) VALUES (?, ?)");
            $hashed = password_hash($userpass, PASSWORD_DEFAULT);
            $stmt->bind_param("ss", $username, $hashed);
            $stmt->execute();
            header("Location: ?page=login&msg=註冊成功");
            exit;
        }
    }
}

// ✅ 登入
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['doLogin'])) {
    $username = trim($_POST["username"]);
    $userpass = trim($_POST["userpass"]);

    $stmt = $conn->prepare("SELECT id, username, userpass FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows == 1 && $row = $res->fetch_assoc()) {
        if (password_verify($userpass, $row["userpass"])) {
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            header("Location: novel_list.php");
            exit;
        } else {
            $login_err = "帳號或密碼錯誤";
        }
    } else {
        $login_err = "帳號或密碼錯誤";
    }
}

// ✅ 登入/註冊畫面
$page = $_GET['page'] ?? 'list';
if ($page === 'login' || $page === 'register') {
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title><?= $page === 'register' ? "註冊" : "登入" ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body class="p-4"><div class="container">
<?php if ($page === 'register'): ?>
<h2>註冊帳號</h2>
<?php if (!empty($errors)) echo '<div class="alert alert-danger">'.implode("<br>", $errors).'</div>'; ?>
<form method="post">
    <input type="hidden" name="doRegister" value="1">
    <div class="mb-3"><label>帳號</label><input type="text" name="username" class="form-control"></div>
    <div class="mb-3"><label>密碼</label><input type="password" name="userpass" class="form-control"></div>
    <div class="mb-3"><label>確認密碼</label><input type="password" name="usercnfm" class="form-control"></div>
    <button type="submit" class="btn btn-success">註冊</button>
    <a href="?page=login" class="btn btn-secondary">返回登入</a>
</form>
<?php else: ?>
<h2>登入帳號</h2>
<?php if (!empty($_GET['msg'])) echo '<div class="alert alert-success">'.$_GET['msg'].'</div>'; ?>
<?php if (!empty($login_err)) echo '<div class="alert alert-danger">'.$login_err.'</div>'; ?>
<form method="post">
    <input type="hidden" name="doLogin" value="1">
    <div class="mb-3"><label>帳號</label><input type="text" name="username" class="form-control"></div>
    <div class="mb-3"><label>密碼</label><input type="password" name="userpass" class="form-control"></div>
    <button type="submit" class="btn btn-primary">登入</button>
    <a href="?page=register" class="btn btn-secondary">註冊</a>
</form>
<?php endif; ?></div></body></html><?php exit; }

// ✅ 分頁查詢
$limit = 10;
$page_num = $_GET['p'] ?? 1;
$offset = ($page_num - 1) * $limit;
$total = $conn->query("SELECT COUNT(*) as t FROM `horror novels`")->fetch_assoc()['t'];
$result = $conn->query("SELECT * FROM `horror novels` LIMIT $limit OFFSET $offset");

// 單筆查看或編輯
$edit_row = null;
if (isset($_GET['edit']) || isset($_GET['view'])) {
    $id = $_GET['edit'] ?? $_GET['view'];
    $stmt = $conn->prepare("SELECT * FROM `horror novels` WHERE ID=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edit_row = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<html><head>
<title>恐怖小說管理</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="p-4 bg-light"><div class="container">
<div class="d-flex justify-content-between mb-3">
<h3>恐怖小說管理系統</h3>
<div><?php if (isset($_SESSION['loggedin'])): ?>
<span class="text-muted me-2">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
<a href="?action=logout" class="btn btn-outline-danger btn-sm">登出</a>
<?php endif; ?></div></div>

<?php if (isset($_GET['view']) && $edit_row): ?>
<div class="card mb-3"><div class="card-body">
<p><strong>名稱：</strong><?= $edit_row['title'] ?></p>
<p><strong>發行年：</strong><?= $edit_row['year'] ?></p>
<p><strong>作者：</strong><?= $edit_row['writer'] ?></p>
<p><strong>發行商：</strong><?= $edit_row['Publisher'] ?></p>
<p><strong>價格：</strong>$<?= $edit_row['price'] ?></p>
<a href="novel_list.php" class="btn btn-secondary">返回</a>
</div></div>
<?php elseif (isset($_GET['edit']) && $edit_row): ?>
<form method="post" class="card mb-3"><div class="card-body">
<input type="hidden" name="ID" value="<?= $edit_row['ID'] ?>">
<input type="text" name="title" class="form-control mb-2" value="<?= $edit_row['title'] ?>" required>
<input type="number" name="year" class="form-control mb-2" value="<?= $edit_row['year'] ?>" required>
<input type="text" name="writer" class="form-control mb-2" value="<?= $edit_row['writer'] ?>" required>
<input type="text" name="Publisher" class="form-control mb-2" value="<?= $edit_row['Publisher'] ?>" required>
<input type="number" name="price" class="form-control mb-2" value="<?= $edit_row['price'] ?>" required>
<button type="submit" name="update" class="btn btn-primary">儲存</button>
<a href="novel_list.php" class="btn btn-secondary">取消</a>
</div></form>
<?php else: ?>
<?php if (isset($_SESSION["loggedin"])): ?>
<form method="post" class="card mb-3"><div class="card-body">
<input type="text" name="title" class="form-control mb-2" placeholder="名稱" required>
<input type="number" name="year" class="form-control mb-2" placeholder="發行年" required>
<input type="text" name="writer" class="form-control mb-2" placeholder="作者" required>
<input type="text" name="Publisher" class="form-control mb-2" placeholder="發行商" required>
<input type="number" name="price" class="form-control mb-2" placeholder="價格" required>
<button type="submit" name="add" class="btn btn-success">新增小說</button>
</div></form>
<?php endif; ?>
<?php endif; ?>

<table class="table table-bordered">
<thead><tr><th>名稱</th><th>發行年</th><th>作者</th><th>價格</th><th>功能</th></tr></thead>
<tbody>
<?php while($r = $result->fetch_assoc()): ?>
<tr>
<td><?= $r['title'] ?></td>
<td><?= $r['year'] ?></td>
<td><?= $r['writer'] ?></td>
<td>$<?= $r['price'] ?></td>
<td>
<a href="?view=<?= $r['ID'] ?>" class="btn btn-sm btn-info">詳細</a>
<?php if (isset($_SESSION['loggedin'])): ?>
<a href="?edit=<?= $r['ID'] ?>" class="btn btn-sm btn-warning">編輯</a>
<a href="?delete=<?= $r['ID'] ?>&p=<?= $page_num ?>" class="btn btn-sm btn-danger" onclick="return confirm('確定刪除?')">刪除</a>
<?php endif; ?>
</td></tr>
<?php endwhile; ?>
</tbody></table>

<nav><ul class="pagination">
<?php for ($i = 1; $i <= ceil($total/$limit); $i++): ?>
<li class="page-item <?= ($i==$page_num)?'active':'' ?>">
<a class="page-link" href="?p=<?= $i ?>"><?= $i ?></a></li>
<?php endfor; ?>
</ul></nav>
</div></body></html>
