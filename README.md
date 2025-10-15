# muter
simple php file to mute and unmute mixmonitor recordings in FreePBX

This verion has no login or security as it expected to be on secure network as it the customers server
muter.php is the file the user loads and enters their extension number in the box

Sucessful muting will change teh mic icon and update the response box

failed muting will respond with "Recording (un)mute failed on 'extension' for 'legs' channels, No recording id active

This version uses the freepbx user as the extension and password for logging in adding extra security
