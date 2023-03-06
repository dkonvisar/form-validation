<?php

const REQUIRED_FIELD_ERROR = 'This is field is required';
$errors = [];
$username = $email = $password = $password_confirm = $cv_url = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = post_data('username');
    $email = post_data('email');
    $password = post_data('password');
    $password_confirm = post_data('password_confirm');
    $cv_url = post_data('cv_url');

    if (!$username) {
        $errors['username'] = REQUIRED_FIELD_ERROR;
    } elseif (strlen($username < 6 || strlen($username) > 16)) {
        $errors['username'] = 'This field must be in between 6 and 16 characters';
    }

    if (!$email) {
        $errors['email'] = REQUIRED_FIELD_ERROR;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'This field must be valid email address';
    }

    if (!$password) {
        $errors['password'] = REQUIRED_FIELD_ERROR;
    }

    if (!$password_confirm) {
        $errors['password_confirm'] = REQUIRED_FIELD_ERROR;
    }

    if ($password && $password_confirm && strcmp($password, $password_confirm) !== 0) {
        $errors['password_confirm'] = 'This must match the password field';
    }

    if ($cv_url && !filter_var($cv_url, FILTER_VALIDATE_URL)) {
        $errors['cv_url'] = 'Please provide a valid link';
    }

    if (empty($errors)) {
        echo 'Everything is good and we can send data to the DB...' . '<br>';
    }
}

function post_data($field): string
{
    $_POST[$field] ??= '';
    return htmlspecialchars(stripslashes($_POST[$field]));
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form Validation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
<main>
    <h2 class="text-center my-5">Form with a server validation</h2>
    <form class="container my-5" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="username" class="form-label">Username*</label>
                    <input type="text"
                           class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>"
                           name="username" value="<?php echo $username; ?>" placeholder="Username">
                    <small class="form-text text-muted">Min: 6 and max 16 characters</small>
                    <div class="invalid-feedback"><?php echo $errors['username'] ?? ''; ?></div>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address*</label>
                    <input type="text" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>"
                           name="email" value="<?php echo $email; ?>" placeholder="name@example.com">
                    <div class="invalid-feedback"><?php echo $errors['email'] ?? ''; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="password" class="form-label">Password*</label>
                    <input type="password"
                           class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>"
                           name="password" value="<?php echo $password; ?>" placeholder="Password">
                    <div class="invalid-feedback"><?php echo $errors['password'] ?? ''; ?></div>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Repeat Password*</label>
                    <input type="password"
                           class="form-control <?php echo isset($errors['password_confirm']) ? 'is-invalid' : ''; ?>"
                           name="password_confirm" value="<?php echo $password_confirm; ?>"
                           placeholder="Repeat Password">
                    <div class="invalid-feedback"><?php echo $errors['password_confirm'] ?? ''; ?></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="cv_url" class="form-label">Your CV (optional)</label>
                    <input type="text" class="form-control <?php echo isset($errors['cv_url']) ? 'is-invalid' : ''; ?>"
                           name="cv_url" value="<?php echo $cv_url; ?>" placeholder="https://www.example.com/my-cv">
                    <div class="invalid-feedback"><?php echo $errors['cv_url'] ?? ''; ?></div>
                </div>
            </div>
        </div>
        <button class="btn btn-primary">Register</button>
    </form>
</main>
</body>
</html>