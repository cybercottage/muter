<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreePBX call Mute control</title>
    <style>
        body { font-family: sans-serif; max-width: 200px; margin: 2em auto; }
        .form-group { margin-bottom: 1em; }
        label { display: block; margin-bottom: 0.25em; }
        input, select, button { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        #status-container { text-align: center; margin-bottom: 2em; }
        #statusImage { width: 75px; height: 75px; }
        #result { margin-top: 1em;  padding: 1em; background-color: #f0f0f0; border-radius: 5px; }
    </style>
</head>
<body>

<?php
require_once("./header.php");
//echo "$uexten";
?>

    <div id="status-container">
        <img src="unmute.png" alt="Unmuted" id="statusImage">
    </div>

    <form id="controlForm">
        <div class="form-group">
            <label for="number">Extension Number:</label>
            <input type="text" id="number" name="number" value="<?php echo $uexten; ?>" required>
        </div>

        <div class="form-group">
            <label for="action">Action:</label>
            <select id="action" name="action">
                <option value="mute">Mute</option>
                <option value="unmute">Unmute</option>
            </select>
        </div>

        <div class="form-group">
            <label for="party">Audio Channel:</label>
            <select id="party" name="party">
                <option value="both">Both</option>
		<option value="caller">Caller</option>
                <option value="callee">Called</option>
            </select>
        </div>

        <button type="submit">Submit</button>
    </form>

    <div id="result">
        Awaiting submission...
    </div>

<script>
    // Wait for the document to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        
        // Get the form element
        const controlForm = document.getElementById('controlForm');

        // Add a 'submit' event listener to the form
        controlForm.addEventListener('submit', function(event) {
            
            // Prevent the default form submission (which would reload the page)
            event.preventDefault();

            // Get the data from the form
            const formData = new FormData(controlForm);
            
            // Use the Fetch API to send the data to process.php
            fetch('process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()) // Get the response as plain text
            .then(textResponse => {
                // Get the elements to update
                const resultDiv = document.getElementById('result');
                const statusImage = document.getElementById('statusImage');

                // Update the result div with the response from the server
                resultDiv.textContent = textResponse;

                // Check if the response contains 'mute' or 'unmute' to change the image
                if (textResponse.toLowerCase().includes('to mute')) {
                    statusImage.src = 'mute.png';
                    statusImage.alt = 'Muted';
                } else if (textResponse.toLowerCase().includes('to unmute')) {
                    statusImage.src = 'unmute.png';
                    statusImage.alt = 'Unmuted';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('result').textContent = 'An error occurred.';
            });
        });
    });
</script>

</body>
</html>
