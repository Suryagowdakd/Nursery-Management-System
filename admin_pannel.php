<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/admin_style.css">
    <style>
        /* Add your CSS styles here to make it attractive */
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #ccc;
        }

        th,
        td {
            padding: 10px;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <?php @include 'admin_header.php'; ?>
    <div class="container">
        <h1>Admin Panel</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>User ID</th>
                    <th>View Details</th>
                </tr>
            </thead>
            <tbody>
                {% for order in orders %}
                <tr>
                    <td>{{ order.username }}</td>
                    <td>{{ order.email }}</td>
                    <td>{{ order.user_id }}</td>
                    <td><a href="admin_orders.php">View Details</a></td>
                </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
</body>

</html>