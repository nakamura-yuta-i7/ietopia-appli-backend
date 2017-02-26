<?php
$PATH_INFO = call_user_func(function() {
	return preg_replace("/\?.+/", "", $_SERVER["REQUEST_URI"]); 
});
echo "<pre>"; var_export(compact("PATH_INFO")); echo "</pre>";

echo "<pre>"; var_export($_SERVER); echo "</pre>";
phpinfo();
