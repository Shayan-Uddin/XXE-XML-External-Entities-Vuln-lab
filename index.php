<?php include 'includes/header.php'; ?>

        <div class="main-content">
            <h2>Welcome to the XXE Vulnerability Training Lab</h2>
            
            <p style="margin: 20px 0; line-height: 1.6;">
                This lab environment is designed to help cybersecurity students and penetration testers learn about 
                XML External Entity (XXE) vulnerabilities. XXE vulnerabilities occur when an application processes 
                XML input without proper security controls, allowing attackers to exploit external entity references 
                in XML documents.
            </p>

            <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; border-left: 3px solid #007bff; margin: 20px 0;">
                <h3>📚 What is XXE?</h3>
                <p>
                    XML External Entity injection is a type of attack against an application that parses XML input. 
                    XXE attacks can:
                </p>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>Read local files on the server</li>
                    <li>Perform SSRF attacks to access internal services</li>
                    <li>Execute Denial of Service attacks</li>
                    <li>Extract sensitive data from the server</li>
                </ul>
            </div>

            <h3>🚀 Available Lab Levels</h3>
            
            <div class="lab-grid">
                <div class="lab-card">
                    <h3>Level 1 - Basic XXE</h3>
                    <p>Simple XML parsing with basic external entity support</p>
                    <a href="labs/level1.php">Start Lab →</a>
                </div>

                <div class="lab-card">
                    <h3>Level 2 - Local File Disclosure</h3>
                    <p>Read sensitive local files like /etc/passwd</p>
                    <a href="labs/level2.php">Start Lab →</a>
                </div>

                <div class="lab-card">
                    <h3>Level 3 - Blind XXE</h3>
                    <p>XXE attacks without direct output in responses</p>
                    <a href="labs/level3.php">Start Lab →</a>
                </div>

                <div class="lab-card">
                    <h3>Level 4 - SSRF via XXE</h3>
                    <p>Access internal services through XXE</p>
                    <a href="labs/level4.php">Start Lab →</a>
                </div>

                <div class="lab-card">
                    <h3>Level 5 - Advanced XXE</h3>
                    <p>Parameter entities and complex entity resolution</p>
                    <a href="labs/level5.php">Start Lab →</a>
                </div>

                <div class="lab-card">
                    <h3>Level 6 - XXE Filter Bypass</h3>
                    <p>Bypass basic XXE input filters</p>
                    <a href="labs/level6.php">Start Lab →</a>
                </div>

                <div class="lab-card">
                    <h3>Level 7 - XXE Denial of Service</h3>
                    <p>Perform DoS attacks using XML entity expansion</p>
                    <a href="labs/level7.php">Start Lab →</a>
                </div>

                <div class="lab-card">
                    <h3>Level 8 - Secure Implementation</h3>
                    <p>Properly configured secure XML parsing</p>
                    <a href="labs/level8.php">View Implementation →</a>
                </div>
            </div>

            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
                <h3>📖 How to Use This Lab</h3>
                <ol style="margin-left: 20px; margin-top: 10px; line-height: 1.6;">
                    <li>Start with Level 1 and progress through each level</li>
                    <li>Read the lab description and understand the scenario</li>
                    <li>Try the example payloads provided</li>
                    <li>Experiment with your own payloads</li>
                    <li>Observe the results and understand how XXE works</li>
                    <li>Continue to more advanced levels as you learn</li>
                </ol>
            </div>
        </div>

<?php include 'includes/footer.php'; ?>
