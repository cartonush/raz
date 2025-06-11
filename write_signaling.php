
<?php
$data = json_decode(file_get_contents("php://input"), true);
$from = $data["from"];
$to = $data["to"];
$content = $data["data"];

$file = "signaling.json";
$signaling = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if (!isset($signaling[$from])) $signaling[$from] = [];
$signaling[$from][$to] = array_merge($signaling[$from][$to] ?? [], [
  $content["type"] => $content["type"] === "ice" ? $content["candidate"] : $content["sdp"]
]);

file_put_contents($file, json_encode($signaling, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
?>
