<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/style.css">
    <title>Document</title>
</head>

<body>
    <main>
        <section class="section">
            <h1>Signin View</h1>
        </section>
        <section class="section">
            <form action="" method="post">

                <input type="text" name="email" placeholder="email" value="<?= old_value('email') ?>" id="">
                <div><?= $user->getError('email') ?></div><br>

                <input type="password" name="password" placeholder="password" value="<?= old_value('password') ?>" id="">
                <div><?= $user->getError('password') ?></div><br>

                <input type="submit" value="submit">
            </form>
        </section>
    </main>
    <footer>
    </footer>
</body>

</html>