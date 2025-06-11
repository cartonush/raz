<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sender = $_POST['sender'] ?? '';
  $receiver = $_POST['receiver'] ?? '';
  $message = $_POST['message'] ?? '';

  if (!$sender || !$receiver || !$message) {
    echo "Missing parameters: ";
    echo "sender=" . ($sender ?: "null") . ", ";
    echo "receiver=" . ($receiver ?: "null") . ", ";
    echo "message=" . ($message ?: "null");
    exit;
  }

  $date = date("d/m");
  $time = date("H:i");
  $users = [$sender, $receiver];
  sort($users);
  $file = "chats/" . implode("_", $users) . ".txt";
  $content = file_exists($file) ? file_get_contents($file) : "";
  if (!str_contains($content, "# $date")) $content .= "\n# $date\n";
  $line = "$time|$sender:$message\n";
  file_put_contents($file, $content . $line);
  echo "OK";
}
?>
