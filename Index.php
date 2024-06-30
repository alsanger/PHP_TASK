<!DOCTYPE html>
<html>
<head>
    <title>Homework</title>
    <meta charset="utf-8" />
</head>
<body>
<?php
$sendDisabledStatus = "disabled";
$userName = "";
function ReadArrayFromFile($fileName)
{
    if (!is_writable($fileName))
        return NULL;

    $fd = fopen($fileName, 'r') or die("ERROR while opening file");
    $arrayJSON = file_get_contents($fileName);
    $array = json_decode($arrayJSON, true);
    fclose($fd);
    return $array;
}
function WriteArrayToFile($fileName, $array)
{
    $fd = fopen($fileName, 'w') or die("ERROR while writing file");
    fwrite($fd, json_encode($array));
    fclose($fd);
}
////////////////////////////////////////////////////////////////////////////////////
if (isset($_POST["name"]) && isset($_POST["password"]) && isset($_POST["register"]))
{
    $user["name"] = $_POST["name"];
    $user["password"] = $_POST["password"];

    $users = ReadArrayFromFile("users.txt");
    $users[] = $user;

    WriteArrayToFile("users.txt", $users);
    $sendDisabledStatus = "";
    $userName = $user["name"];
}

if (isset($_POST["name"]) && isset($_POST["login"]) && isset($_POST["login"]))
{
    $user["name"] = $_POST["name"];
    $user["password"] = $_POST["password"];

    $users = ReadArrayFromFile("users.txt");
    foreach ($users as $item)
    {
        if ($user["name"] == $item["name"] && $user["password"] == $item["password"])
        {
            $sendDisabledStatus = "";
            $userName = $user["name"];
            break;
        }
    }
}
if (isset($_POST["send"]) && isset($_POST["message"]) && isset($_POST["name"]))
{
    $message["name"] = $_POST["name"];
    $message["message"] = $_POST["message"];

    $messages = ReadArrayFromFile("messages.txt");
    $messages[time()] = $message;

    WriteArrayToFile("messages.txt", $messages);
}
?>
<form method="post">
    <p>
        <label>Name</label>
        <input type="text" name="name" placeholder="Name"/>
        <label>Password</label>
        <input type="password" name="password" placeholder="Password"/>
    </p>
    <p>
        <input type="submit" name="register" value="Register" />
        <input type="submit" name="login" value="Login" />
    </p>
</form>
<hr>
<form method="post">
    <p>
        <input type="hidden" name="name" value="<?php echo $userName?>"/>
        <input type="text" name="message" placeholder="Message"/>
    </p>
    <p>
        <input type="submit" name="send" value="Send" <?php echo $sendDisabledStatus ?> />
        <input type="submit" name="refresh" value="Refresh" />
    </p>
</form>
<hr>
<?php
if (isset($_POST["refresh"]))
{
    $messages = ReadArrayFromFile("messages.txt");
    ksort($messages);
    foreach ($messages as $mes)
    {
        $name = $mes["name"];
        $message = $mes["message"];
        echo "<p>$name: $message</p>";
    }
}
?>
</body>
</html>