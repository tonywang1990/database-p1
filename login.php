<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
<head>
<title>Database Login</title>
</head>

<body style="margin:20%; margin-left:35%; margin-right:35%">
<div class="w3-card-4 w3-large">
<div class="w3-container w3-light-green">
  <h2>Professor Research Database</h2>
</div>

<div class="w3-panel w3-padding-4"></div>
<form action="verify.php" method="post" class="w3-container">

<label class="w3-label">Host Sever</label>
<input class="w3-input w3-border" type="text" name="host">

<label class="w3-label">Username</label>
<input class="w3-input w3-border" type="text" name="username">

<label class="w3-label">Password</label>
<input class="w3-input w3-border" type="password" name="password">

<div class="w3-panel w3-padding-1"></div>
<input class="w3-btn-block w3-green" type="submit" name="submit" value="Login">
</form>
<div class="w3-panel w3-padding-4"></div>

</div>
</body>
</html>
