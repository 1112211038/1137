<?php
// 資料庫連線設定
$host = "localhost";        // 主機名稱
$user = "root";             // 使用者名稱
$password = "";             // 密碼（如果有請填入）
$dbname = "1137";  // 資料庫名稱（請自行修改）

// 建立資料庫連線
$conn = new mysqli($host, $user, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// 查詢資料
$sql = "SELECT * FROM `horror novels`";
$result = $conn->query($sql);

// 顯示資料列表
echo "<h2>恐怖小說清單</h2>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>ID</th><th>名稱</th><th>發行年</th><th>作者</th><th>發行商</th><th>價格</th></tr>";

if ($result->num_rows > 0) {
    // 輸出每一筆資料
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ID"] . "</td>";
        echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
        echo "<td>" . $row["year"] . "</td>";
        echo "<td>" . htmlspecialchars($row["writer"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["Publisher"]) . "</td>";
        echo "<td>$" . $row["price"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>無資料</td></tr>";
}
echo "</table>";

// 關閉連線
$conn->close();
?>
