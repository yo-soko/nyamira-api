<?php
$ip = "192.168.100.201";
$port = 4370;

// open socket
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$sock) {
    die("Socket creation failed: " . socket_strerror(socket_last_error()));
}

if (!socket_connect($sock, $ip, $port)) {
    die("Connection failed: " . socket_strerror(socket_last_error($sock)));
}

echo "Connected to ZKTeco device on $ip:$port\n";

// Example: send a "connect" command
// ZKProtocol packets must be formatted, here just testing
$msg = "Hello"; // placeholder (must use proper binary packet)
socket_write($sock, $msg, strlen($msg));

$response = socket_read($sock, 2048);
echo "Device responded: " . bin2hex($response) . "\n";

socket_close($sock);
?>
