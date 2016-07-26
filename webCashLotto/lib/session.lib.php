<?

function isSessionTimeout()
{
	// 방법 1
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    	// last request was more than 30 minutes ago
    	session_unset();     // unset $_SESSION variable for the run-time 
    	session_destroy();   // destroy session data in storage
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	
	// 방법 2
	// use an additional time stamp to regenerate the session ID periodically to avoid attacks on sessions like session fixation
	
	if (!isset($_SESSION['CREATED'])) {
    	$_SESSION['CREATED'] = time();
	} else if (time() - $_SESSION['CREATED'] > 1800) {
    	// session started more than 30 minutes ago
    	session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
    	$_SESSION['CREATED'] = time();  // update creation time
	}
}

?>