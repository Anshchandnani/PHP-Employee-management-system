<?php include 'header_admin.php'; ?>
<?php
include 'db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Performance Analysis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        #taskChart {
            max-width: 1800px;
            margin: 0 190px;
            display: block;
        }
        .container {
            max-width: 1900px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📊 Employee Performance Analysis</h2>
    <table>
        <tr>
            <th>Emp ID</th>
            <th>Name</th>
            <th>Total Tasks</th>
            <th>Completed Tasks</th>
            <th>Task Completion %</th>
            <th>Total Leaves</th>
        </tr>

    <?php
    $sql = "SELECT 
                e.emp_id,
                e.name,
                COUNT(DISTINCT t.task_id) AS total_tasks,
                SUM(CASE WHEN t.status = 1 THEN 1 ELSE 0 END) AS completed_tasks,
                COUNT(DISTINCT l.leave_id) AS total_leaves
            FROM emp_info e
            LEFT JOIN task_info t ON e.emp_id = t.emp_id
            LEFT JOIN leave_info l ON e.emp_id = l.emp_id
            GROUP BY e.emp_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $completionPercent = $row['total_tasks'] > 0 ? round(($row['completed_tasks'] / $row['total_tasks']) * 100, 2) : 0;

            echo "<tr>
                <td>{$row['emp_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['total_tasks']}</td>
                <td>{$row['completed_tasks']}</td>
                <td>{$completionPercent}%</td>
                <td>{$row['total_leaves']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No data found</td></tr>";
    }
    ?>
    </table>
    <canvas id="taskChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('taskChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?php
            $result->data_seek(0); // rewind result set
            while ($row = $result->fetch_assoc()) {
                echo "'{$row['name']}',";
            }
        ?>],
        datasets: [{
            label: 'Task Completion %',
            data: [<?php
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    $taskPercent = $row['total_tasks'] > 0 ? round(($row['completed_tasks'] / $row['total_tasks']) * 100, 2) : 0;
                    echo "$taskPercent,";
                }
            ?>],
            backgroundColor: 'rgba(75, 192, 192, 0.7)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Completion Percentage'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Employees'
                }
            }
        }
    }
});
</script>
</body>
</html>
<?php include 'footer_admin.php'; ?>