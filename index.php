<?php
include('conn.php');

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['user_role'] ?? null;

$nav_links = [
    'guest' => [
        ['href' => 'reg.php', 'label' => 'Registration'],
        ['href' => 'login.php', 'label' => 'Login']
    ],
    'worker' => [
        ['href' => 'profile.php', 'label' => 'Profile'],
        ['href' => 'logout.php', 'label' => 'Logout'],
        [
            'label' => 'Request',
            'dropdown' => [
                ['href' => 'request.php', 'label' => 'Job Request'],
                ['href' => 'request_status.php', 'label' => 'Request Status']
            ]
        ]
    ],
    'user' => [
        ['href' => 'profile.php', 'label' => 'Profile'],
        ['href' => 'logout.php', 'label' => 'Logout'],
        ['href' => 'request_status.php', 'label' => 'Request Status']
    ],
    'admin' => [
        ['href' => 'profile.php', 'label' => 'Profile'],
        ['href' => 'logout.php', 'label' => 'Logout'],
        [
            'label' => 'Request',
            'dropdown' => [
                ['href' => 'workermanage.php', 'label' => 'Worker Request'],
                ['href' => 'request_status.php', 'label' => 'Request Status'],
                ['href' => 'manage_user.php', 'label' => 'Manage User']
            ]
        ]
    ]
];

if ($user_id !== null) { // Check if user_id is not null
    $sql = "SELECT user_status FROM user_tab WHERE user_id='$user_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) { // Check if query executed and returned rows
        $user_data = mysqli_fetch_assoc($result);

        if ($user_data['user_status'] == 1) { // User is blocked
            header("Location: http://localhost/mini2/block.php");
            exit();
        }
    }
}

$role = $user_id ? $user_role : 'guest';
$links = $nav_links[$role];
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Service Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        /* Custom styles */
        .navbar-custom {
            background-color: #00072D;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #A6E1FA;
        }

        .navbar-custom .nav-link:hover {
            color: white;
        }

        .navbar-custom .form-control {
            border-color: #0A2472;
        }

        .navbar-custom .btn {
            color: #A6E1FA;
            border-color: #A6E1FA;
        }

        .dropdown-menu {
            background: #00072D;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">Service Hub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php foreach ($links as $link): ?>
                        <?php if (isset($link['dropdown'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= htmlspecialchars($link['label']) ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php foreach ($link['dropdown'] as $dropdown): ?>
                                        <li><a class="dropdown-item" href="<?= htmlspecialchars($dropdown['href']) ?>"><?= htmlspecialchars($dropdown['label']) ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= htmlspecialchars($link['href']) ?>"><?= htmlspecialchars($link['label']) ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <?php if ($user_id): ?>
                    <p class="me-3"><strong>User ID:</strong> <?= htmlspecialchars($user_id) ?></p>
                <?php endif; ?>
                <form class="d-flex" role="search" method="GET" action="home.php">
                    <input class="form-control me-2" type="search" placeholder="Search" name="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
