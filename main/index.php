<?php
// initialize errors variable
$errors = "";

// connect to database
$db = mysqli_connect("localhost", "root", "password", "todo");

// insert a quote if submit button is clicked
if (isset($_POST['submit'])) {
    if (empty($_POST['task'])) {
        $errors = "You must fill in the task";
    } else {
        $task = $_POST['task'];
        $sql = "INSERT INTO tasks (task) VALUES ('$task')";
        mysqli_query($db, $sql);
        header('location: index.php');
    }
}

if (isset($_GET['del_task'])) {
    $id = $_GET['del_task'];
    mysqli_query($db, "DELETE FROM tasks WHERE id=".$id);
    header('location: index.php');
}
?>

<html lang="en">
<head>
    <title>ToDo List Application PHP and MySQL</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="heading">
    <h2 style="font-style: normal;" >ToDo List Application PHP and MySQL database</h2>
</div>
<form id="taskForm" class="input_form">
    <?php if (isset($errors)) { ?>
        <p class="error-message"><?php echo $errors; ?></p>
    <?php } ?>
    <label>
        <input type="text" name="task" class="task_input">
    </label>
    <button type="button" onclick="addTask()" class="add_btn">Add Task</button>
</form>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Tasks</th>
        <th style="width: 50px;">Action</th>
    </tr>
    </thead>
    <tbody id="taskList">
    <?php
    // select all tasks if the page is visited or refreshed
    $tasks = mysqli_query($db, "SELECT * FROM tasks");
    $i = 1; while ($row = mysqli_fetch_array($tasks)) { ?>
        <tr>
            <td> <?php echo $i; ?> </td>
            <td class="task"> <?php echo $row['task']; ?> </td>
            <td class="delete">
                <a href="#" onclick="deleteTask(<?php echo $row['id']; ?>)">x</a>
            </td>
        </tr>
        <?php $i++; } ?>
    </tbody>
</table>

<script>
    function addTask() {
        const taskInput = document.querySelector('.task_input').value.trim();
        const errorElement = document.querySelector('.error-message');

        errorElement.textContent = '';

        if (taskInput === '') {
            // Display error message if the input is empty
            errorElement.textContent = 'You must fill in the task';
        } else {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    // Refresh the page to update the task list
                    window.location.reload();
                }
            };
            xhr.send('submit=1&task=' + encodeURIComponent(taskInput));
        }
    }

    function deleteTask(taskId) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'index.php?del_task=' + taskId, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Refresh the page to update the task list
                window.location.reload();
            }
        };
        xhr.send();
    }
</script>
</body>
</html>
