<?php
// ==== CONFIGURATION ==== //
require 'muteconfig.php';

$extension = $_POST['number']; //$_GET["name"]
$mode = $_POST['party']; //$_GET["name"]
$mute = $_POST['action']; //$_GET["name"]
$dir = $_POST['party']; //$_GET["name"]
$muted = $_POST['action']; //$_GET["name"]

if ($muted == mute){
    $mute = "1";
}elseif ($muted == unmute){
    $mute = "0";
}else{
    $mute = "1";
}

if ($dir=="caller"){
    $mode =  "read";
}elseif ($dir=="callee"){
    $mode = "write";
}else{
    $mode = "both";
}

// ==== CONNECT TO AMI ==== //
$socket = fsockopen($ami_host, $ami_port, $errno, $errstr, 3);
if (!$socket) {
    die("Could not connect to Asterisk Manager Interface: $errstr ($errno)");
}

stream_set_timeout($socket, 2);

// ==== LOGIN ==== //
fputs($socket, "Action: Login\r\n");
fputs($socket, "Username: $ami_user\r\n");
fputs($socket, "Secret: $ami_pass\r\n");
fputs($socket, "Events: off\r\n\r\n");

read_response($socket); // consume login response

// ==== REQUEST CHANNEL LIST ==== //
fputs($socket, "Action: CoreShowChannels\r\n\r\n");

$channels = [];
$full_channel = null;

while ($line = fgets($socket, 4096)) {
    $line = trim($line);

    if (stripos($line, "Event: CoreShowChannel") !== false) {
        $channel = [];
        // Read this event block
        while (($sub = trim(fgets($socket, 4096))) !== '') {
            if (stripos($sub, "Channel:") === 0) {
                $channel['Channel'] = trim(substr($sub, 8));
            }
        }
        if (!empty($channel['Channel'])) {
            $channels[] = $channel['Channel'];
        }
    }

    // End of event list
    if (stripos($line, "Event: CoreShowChannelsComplete") !== false) {
        break;
    }
}

// ==== FIND MATCHING CHANNEL ==== //
foreach ($channels as $ch) {
    if (preg_match("/^PJSIP\/{$extension}-[0-9A-Fa-f]+$/", $ch)) {
        $full_channel = $ch;
        break;
    }
}

if (!$full_channel) {
    fputs($socket, "Action: Logoff\r\n\r\n");
    fclose($socket);
    die("No active recording channel found for Exten: $extension\n");
}

//uncomment for debug
//echo "$muted requested on $dir legs of channel: $full_channel\n";

// ==== GET RECORD_ID ==== //
fputs($socket, "Action: GetVar\r\n");
fputs($socket, "Channel: $full_channel\r\n");
fputs($socket, "Variable: RECORD_ID\r\n\r\n");

$record_id = null;
while ($line = fgets($socket, 4096)) {
    $line = trim($line);
    if (stripos($line, "Value:") === 0) {
        $record_id = trim(substr($line, 6));
        break;
    }
    if (stripos($line, "Response:") === 0 && stripos($line, "Error") !== false) {
        break;
    }
    if (stripos($line, "ActionID:") === 0) {
        continue;
    }
    if (stripos($line, "Event:") === 0) {
        break;
    }
}


//fputs($socket, "Action: Login\r\nUsername: $managerUser\r\nSecret: $managerSecret\r\n\r\n");
fputs($socket, "Action: MixMonitorMute\r\nChannel: $record_id\r\nDirection: $mode\r\nState: $mute\r\n\r\n");

// ==== CLEANUP ==== //
fputs($socket, "Action: Logoff\r\n\r\n");
fclose($socket);

if ($record_id) {
// uncomment for debug
//    echo "RECORD_Channel = $record_id $dir legs $muted\n";

$response = "Recording on Extension $extension for $dir channels set to $muted";
echo $response;


} else {
$response = "Recording $muted failed on $extension for $dir channels, No recording id active";
echo $response;
}

// ==== HELPER ==== //
function read_response($socket)
{
    $out = "";
    while ($line = fgets($socket, 4096)) {
        $out .= $line;
        if (trim($line) === "") break;
    }
    return $out;
}
?>
