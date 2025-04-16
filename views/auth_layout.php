<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/test3/assets/style2.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <title><?= $title ?? 'Auth Page' ?></title>
</head>
<body>
  <div class="auth-container">
    <div class="auth-box">
      <?= $content ?>
    </div>
  </div>
</body>
</html>
