#!/usr/bin/php
<?php
print "PHP runs under the user: [" . system('whoami') . "]\n\n\n";

if (!file_exists("/home/edxgtw/.ssh/id_rsa.pub")) {
        echo "~/.ssh/id_rsa.pub Does not exist \n";
}

if (!file_exists("/home/edxgtw/.ssh/id_rsa")) {
        echo "~/.ssh/id_rsa does not exist \n";
}


$files = array(
                array(
                        "local" => "/home/edxgtw/inbound/full_postal_by_cell_20120228.txt._tml",
                        "remote" => "/var/www/api/files/full_postal_by_cell_20120228.txt._tml"
        ),
        array(
                        "local" => "/home/edxgtw/inbound/iptv_postal_codes_20120228.txt._tml",
                        "remote" => "/var/www/api/files/iptv_postal_codes_20120228.txt._tml"
        ),
);

// Create connection the the remote host
$connection = ssh2_connect('ubuntu@ec2-23-20-15-147.compute-1.amazonaws.com', 22, array('hostkey'=>'ssh-rsa'));

if ($connection) {
        if (ssh2_auth_pubkey_file($connection, 'edxgtw', "/home/edxgtw/.ssh/id_rsa.pub", "/home/edxgtw/.ssh/id_rsa", 'm3rc3r')) {

                echo "Public Key Authentication Successful\n";

                // Create SFTP session
                $sftp = ssh2_sftp($connection);

                foreach($files as $file) {
                        $sftpStream = @fopen('ssh2.sftp://'.$sftp.$file["remote"], 'w');

                        try {

                                if (!$sftpStream) {
                                        throw new Exception("Could not open remote file: {$file["remote"]}");
                                }

                                $data_to_send = @file_get_contents($file["local"]);

                                if ($data_to_send === false) {
                                        throw new Exception("Could not open local file: {$file["local"]}.");
                                }

                                if (@fwrite($sftpStream, $data_to_send) === false) {
                                       throw new Exception("Could not send data from file: {$file["local"]}.");
                                }

                                fclose($sftpStream);
                        } catch (Exception $e) {
                            error_log('Exception: ' . $e->getMessage());
                            fclose($sftpStream);
                        }
                }
        } else {
                echo 'Public Key Authentication Failed';
        }
        ssh2_exec($connection, 'exit');
}
