<?php

function remove_query_params($url) {
    return strtok($url, '?');
}

$current_url = remove_query_params($_SERVER['REQUEST_URI']);
$dir = __DIR__ . $current_url; // Đảm bảo đường dẫn đầy đủ
$dir = realpath($dir); // Lấy đường dẫn thực để tránh các lỗ hổng bảo mật

if ($dir === false || strpos($dir, __DIR__) !== 0 || !is_dir($dir)) {
    echo "Thư mục không tồn tại hoặc không thể truy cập được.";
    exit;
}

$files = scandir($dir);

function format_size($size) {
    $units = ['B', 'K', 'M', 'G', 'T'];
    for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    return round($size, 2) . $units[$i];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<head>
    <title>BHOKodi.TOP</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #dddddd;
        }
    </style>
</head>
<body>
    <h1>BHOKodi.TOP</h1>
    <table>
        <tr>
            <th></th>
            <th>Tên</th>
            <th>Sửa đổi gần đây</th>
            <th>Kích thước</th>
            <th>Mô tả</th>
        </tr>
        <tr>
            <td style="text-align:center;">⤴️</td>
            <td><a href="../">..</a></td>
            <td>&nbsp;</td>
            <td align="right">-</td>
            <td>&nbsp;</td>
        </tr>
        <?php
        foreach ($files as $file) {
            if ($file != "." && $file != ".." && $file != ".ftpquota" && strpos($file, '.php') === false && $file != ".htaccess") {
                $path = $dir . "/" . $file;
                if (file_exists($path)) {
                    $last_modified = date("Y-m-d H:i:s", filemtime($path));
                    $size = filesize($path);
                    $file_type = is_dir($path) ? "directory" : "file";
                    $file_path = ($file_type === "directory") ? $file . "/" : $file;
                    $size_display = ($file_type === "directory") ? "-" : format_size($size);
        ?>
        <tr>
            <td style="text-align:center;">
                <?php echo ($file_type === "directory") ? "📁" : "🗒️️"; ?>
            </td>
            <td><a href="<?php echo htmlspecialchars($file_path); ?>"><?php echo htmlspecialchars($file); ?></a></td>
            <td align="right"><?php echo $last_modified; ?></td>
            <td align="right"><?php echo $size_display; ?></td>
            <td>&nbsp;</td>
        </tr>
        <?php
                } else {
                    echo "<tr><td colspan='5'>Tệp hoặc thư mục không tồn tại: " . htmlspecialchars($file) . "</td></tr>";
                }
            }
        }
        ?>
        <tr>
            <th colspan="5" style="text-align:right;">❤️</th>
        </tr>
    </table>
</body>
</html>
