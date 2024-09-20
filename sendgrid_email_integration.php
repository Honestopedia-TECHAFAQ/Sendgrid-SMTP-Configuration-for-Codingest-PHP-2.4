<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'smtp.sendgrid.net',    // SendGrid SMTP server
    'smtp_port' => 587,                    // SendGrid port for TLS
    'smtp_user' => 'apikey',               // SendGrid requires 'apikey' as the username
    'smtp_pass' => 'YOUR_SENDGRID_API_KEY',// Your SendGrid API Key
    'smtp_crypto' => 'tls',                // Encryption method: TLS
    'mailtype' => 'html',                  // Set the email format to HTML
    'charset'  => 'utf-8',                 // Set character encoding to UTF-8
    'wordwrap' => TRUE,                    // Enable word wrap
    'newline'  => "\r\n"                   // Ensure proper line endings
);
$this->load->library('email', $config);
function send_email($to, $subject, $message) {
    $this->email->from('your-email@domain.com', 'Your Company Name'); 
    $this->email->to($to);
    $this->email->subject($subject);
    $this->email->message($message);
    if ($this->email->send()) {
        log_message('info', "Email sent to: $to with subject: $subject");
        return TRUE; 
    } else {
        log_message('error', "Failed to send email to: $to. Error: " . $this->email->print_debugger());
        return FALSE; 
    }
}

function send_bulk_emails($recipients, $subject, $message) {
    $batch_size = 100;  
    $recipient_chunks = array_chunk($recipients, $batch_size);
    
    foreach ($recipient_chunks as $chunk) {
        foreach ($chunk as $recipient) {
            send_email($recipient, $subject, $message);
        }
        sleep(1);  
    }
}

function fetch_subscribers() {
    return array('recipient1@example.com', 'recipient2@example.com', 'recipient3@example.com');
}
function send_newsletter() {
    $recipients = fetch_subscribers();  
    $subject = 'Monthly Newsletter - September 2024';
    $message = "<h1>Welcome to our Newsletter!</h1><p>Here is the latest update for the month.</p>";
    
    send_bulk_emails($recipients, $subject, $message);
    
    log_message('info', "Newsletter sent to subscribers!");
}
function send_test_email() {
    $to = 'test@example.com';
    $subject = 'Test Email';
    $message = "<h1>This is a test email</h1><p>Testing the email functionality using SendGrid SMTP.</p>";
    
    if (send_email($to, $subject, $message)) {
        echo "Test email sent successfully!";
    } else {
        echo "Failed to send test email.";
    }
}

?>
