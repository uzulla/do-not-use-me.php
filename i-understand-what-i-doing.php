<?php
session_start();
$_SESSION['CSRF_TOKEN'] ??= bin2hex(random_bytes(32));
if ($_SERVER['REMOTE_ADDR'] !== getenv("SUPER_DANGER_USER_IP")) exit('forbidden');
?>
    <html>
    <head>
        <title>please delete me in production.</title>
    </head>
    </html>
    <body>
    <h1 style="color:red">PLEASE DELETE ME IMMEDIATE.</h1>
    <form action="" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['CSRF_TOKEN'] ?>">
        cwd: <input type="text" name="cwd" value="<?php echo htmlspecialchars($_POST["cwd"] ?? getcwd(), ENT_QUOTES) ?>" style="width: 100%;"><br>
        cmd: <input type="text" name="cmd" value="<?php echo htmlspecialchars($_POST["cmd"] ?? 'ls -al', ENT_QUOTES) ?>" style="width: 100%;"><br>
        pass: <input type="password" name="pass" value="<?php echo htmlspecialchars($_POST["pass"] ?? '', ENT_QUOTES) ?>"><br>
        <button type="submit">exec</button>
    </form>
    <pre>
<?php
if ($_SERVER['REQUEST_METHOD'] !== "POST") exit;
if ($_SESSION['CSRF_TOKEN'] !== $_POST['csrf_token'] ?? "-1") exit('invalid token');
if ($_POST['pass'] !== getenv("SUPER_DANGER_PASS")) exit('invalid pass');

echo "execute `" . htmlspecialchars($_POST["cmd"], ENT_QUOTES) . "` in `" . htmlspecialchars($_POST["cwd"], ENT_QUOTES) . "`" . PHP_EOL . PHP_EOL;
echo "<hr>" . PHP_EOL;
ini_set("max_execution_time", 300);
ini_set("memory_limit", "512m");

chdir($_POST['cwd']);
$cmd = $_POST["cmd"];
echo htmlspecialchars(`/usr/bin/env bash -c '$cmd' 2>&1`, ENT_QUOTES);

