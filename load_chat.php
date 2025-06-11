<?php
$sender = $_POST["sender"] ?? $_GET["sender"] ?? null;
$receiver = $_POST["receiver"] ?? $_GET["receiver"] ?? null;

if (!$sender || !$receiver) {
  echo "<div style='color:red;'>Missing sender or receiver</div>";
  exit;
}

$path = "chats/" . $sender . "_" . $receiver . ".txt";
if (!file_exists($path)) {
  $path = "chats/" . $receiver . "_" . $sender . ".txt";
}

if (file_exists($path)) {
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (str_starts_with($line, "#")) {
      echo "<div style='text-align:center;color:gray;margin:5px;'>".htmlspecialchars(trim($line))."</div>";
      continue;
    }

    $parts = explode('|', $line, 2);
    if (count($parts) === 2) {
      $timestamp = htmlspecialchars(trim($parts[0]));
      $userText = explode(':', $parts[1], 2);
      if (count($userText) === 2) {
        $user = htmlspecialchars(trim($userText[0]));
        $text = htmlspecialchars(trim($userText[1]));
        $align = ($user === $sender) ? 'right' : 'left';
        $bg = ($user === $sender) ? '#4caf50' : '#1f1f1f';
        $color = ($user === $sender) ? 'white' : 'white';
        echo "<div style='text-align:$align;margin:5px;'>
                <div style='display:inline-block;background:$bg;color:$color;border-radius:10px;padding:8px 12px;max-width:75%;box-shadow:0 1px 3px rgba(0,0,0,0.2);'>
                  <small style='color:lightgray;font-size:11px;'>$user • $timestamp</small><br>
                  <span style='font-size:14px;'>$text</span>
                </div>
              </div>";
      }
    }
  }
} else {
  echo "<div style='color:gray;'>No messages yet.</div>";
}
?>
