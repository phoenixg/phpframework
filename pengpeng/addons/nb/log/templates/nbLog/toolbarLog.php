
  <h1>Log Content</h1>
  <p>
    <?php foreach ($appInfo as $appName => $appLogs) :?>

      <?php foreach ($appLogs as $appLog): ?>
        <?php echo $appName; ?>:<?php echo $appLog; ?><br />
      <?php endforeach; ?>
    <?php endforeach; ?>
  </p>
