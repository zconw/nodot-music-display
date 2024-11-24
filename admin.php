<?php

$musicData = json_decode(file_get_contents('music_data.json'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_music'])) {

    $tempFilePath = $_FILES['music_file']['tmp_name'];

    $newFileName = $_FILES['music_file']['name'];

    $uploadDir ='music_uploads/';
    $destinationFilePath = $uploadDir. $newFileName;
    move_uploaded_file($tempFilePath, $destinationFilePath);

    $newMusic = [
        'name' => $_POST['name'],
        'author' => $_POST['author'],
        'cover_image' => $_POST['cover_image'],
        'file_path' => $destinationFilePath
    ];
    $musicData[] = $newMusic;
    file_put_contents('music_data.json', json_encode($musicData));
    header('Location: admin.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $deleteIndex = $_GET['delete'];
    unset($musicData[$deleteIndex]);
    $musicData = array_values($musicData);
    file_put_contents('music_data.json', json_encode($musicData));
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_music'])) {
    $editIndex = $_POST['edit_index'];
    $musicData[$editIndex]['name'] = $_POST['name'];
    $musicData[$editIndex]['author'] = $_POST['author'];
    $musicData[$editIndex]['cover_image'] = $_POST['cover_image'];
    $musicData[$editIndex]['file_path'] = $_POST['file_path'];
    file_put_contents('music_data.json', json_encode($musicData));
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>音乐管理页面</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="url"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }


        input[type="file"] {
            display: none;
        }

        label.upload-label {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        label.upload-label:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>音乐管理页面</h1>

    <form method="post" enctype="multipart/form-data">
        <h2>添加音乐</h2>
        <label for="name">音乐名称：</label>
        <input type="text" id="name" name="name" required><br>
        <label for="author">作者：</label>
        <input type="text" id="author" name="author" required><br>
        <label for="cover_image">封面图片链接：</label>
        <input type="url" id="cover_image" name="cover_image" required><br>
        <label for="music_file">音乐文件：</label>
        <input type="file" id="music_file" name="music_file" required>
        <label class="upload-label" for="music_file">上传音乐</label><br>
        <button type="submit" name="add_music">添加音乐</button>
    </form>

    <h2>音乐列表</h2>
    <table>
        <tr>
            <th>序号</th>
            <th>音乐名称</th>
            <th>作者</th>
            <th>封面图片</th>
            <th>音乐文件路径</th>
            <th>操作</th>
        </tr>
        <?php foreach ($musicData as $index => $music) :?>
            <tr>
                <td><?php echo $index;?></td>
                <td><?php echo $music['name'];?></td>
                <td><?php echo $music['author'];?></td>
                <td><?php echo $music['cover_image'];?></td>
                <td><?php echo $music['file_path'];?></td>
                <td>
                    <a href="?delete=<?php echo $index;?>">删除</a>
                    <form method="post">
                        <input type="hidden" name="edit_index" value="<?php echo $index;?>">
                        <label for="edit_name_<?php echo $index;?>">名称：</label>
                        <input type="text" id="edit_name_<?php echo $index;?>", name="name" value="<?php echo $music['name'];?>"><br>
                        <label for="edit_author_<?php echo $index;?>">作者：</label>
                        <input type="text" id="edit_author_<?php echo $index;?>", name="author" value="<?php echo $music['author'];?>"><br>
                        <label for="edit_cover_image_<?php echo $index;?>">封面图片：</label>
                        <input type="url" id="edit_cover_image_<?php echo $index;?>", name="cover_image" value="<?php echo $music['cover_image'];?>"><br>
                        <label for="edit_file_path_<?php echo $index;?>">音乐文件路径：</label>
                        <input type="text" id="edit_file_path_<?php echo $index;?>", name="file_path" value="<?php echo $music['file_path'];?>"><br>
                        <button type="submit" name="edit_music">修改</button>
                    </form>
                </td>
            </tr>
        <?php endforeach;?>
    </table>

</body>

</html>