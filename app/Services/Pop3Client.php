<?php

namespace App\Services;

class Pop3Client
{
    private $connection;

    private $host;

    private $port;

    private $username;

    private $password;

    private $attachmentPath;

    public function __construct($host = 'pop.gmail.com', $port = 995, $username = null, $password = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->attachmentPath = storage_path('app/attachments');
    }

    public function connect()
    {
        imap_timeout(IMAP_OPENTIMEOUT, 3);
        $mailbox = sprintf('{%s:%s/pop3/ssl/novalidate-cert}INBOX', $this->host, $this->port);

        $this->connection = @imap_open(
            $mailbox,
            $this->username,
            $this->password,
            OP_DEBUG
        );

        if (! $this->connection) {
            throw new \Exception('Connection failed: '.imap_last_error());
        }

        return $this;
    }

    public function getEmails($limit = 10)
    {
        try {
            $emails = [];
            $totalEmails = imap_num_msg($this->connection);

            for ($i = $totalEmails; $i > max($totalEmails - $limit, 0); $i--) {
                $header = imap_headerinfo($this->connection, $i);
                $structure = imap_fetchstructure($this->connection, $i);

                $email = [
                    'subject' => $this->decodeSubject($header->subject ?? ''),
                    'from' => $header->fromaddress ?? '',
                    'date' => $header->date ?? '',
                    'message_id' => $header->message_id ?? '',
                    'attachments' => [],
                    'body' => '',
                ];

                // Handle multipart emails
                if (isset($structure->parts) && count($structure->parts)) {
                    $email = $this->parseEmailParts($i, $structure, $email);
                } else {
                    // Single part email
                    $email['body'] = quoted_printable_decode(imap_fetchbody($this->connection, $i, 1));
                }

                $emails[] = $email;
            }

            return $emails;
        } catch (\Exception $e) {
            \Log::error('POP3 Error: '.$e->getMessage());

            return [];
        }
    }

    private function parseEmailParts($msgNumber, $structure, $email, $partNumber = '')
    {
        $attachmentCount = 1;

        foreach ($structure->parts as $index => $part) {
            $currentPartNumber = $partNumber ? $partNumber.'.'.($index + 1) : ($index + 1);

            if ($this->isAttachment($part)) {
                $attachment = $this->saveAttachment($msgNumber, $currentPartNumber, $part);
                if ($attachment) {
                    $email['attachments'][] = $attachment;
                }
            } elseif ($part->type === 0) { // Text part
                $email['body'] .= $this->getEmailBody($msgNumber, $currentPartNumber, $part);
            } elseif (isset($part->parts) && count($part->parts)) {
                // Recursive call for nested parts
                $email = $this->parseEmailParts($msgNumber, $part, $email, $currentPartNumber);
            }
        }

        return $email;
    }

    private function isAttachment($part)
    {
        return (
            isset($part->disposition) &&
            strtolower($part->disposition) === 'attachment'
        ) || (
            isset($part->parameters) &&
            $this->hasFilenameParameter($part->parameters)
        );
    }

    private function hasFilenameParameter($parameters)
    {
        foreach ($parameters as $param) {
            if (strtolower($param->attribute) === 'name') {
                return true;
            }
        }

        return false;
    }

    private function saveAttachment($msgNumber, $partNumber, $part)
    {
        try {
            // Create attachments directory if it doesn't exist
            if (! file_exists($this->attachmentPath)) {
                mkdir($this->attachmentPath, 0777, true);
            }

            // Get filename
            $filename = '';
            if (isset($part->dparameters)) {
                foreach ($part->dparameters as $dparam) {
                    if (strtolower($dparam->attribute) === 'filename') {
                        $filename = $dparam->value;
                    }
                }
            }

            if (! $filename && isset($part->parameters)) {
                foreach ($part->parameters as $param) {
                    if (strtolower($param->attribute) === 'name') {
                        $filename = $param->value;
                    }
                }
            }

            if (! $filename) {
                $filename = 'attachment_'.uniqid().$this->getFileExtension($part);
            }

            // Sanitize filename
            $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $filename);

            // Get attachment data
            $data = imap_fetchbody($this->connection, $msgNumber, $partNumber);

            // Decode based on encoding type
            switch ($part->encoding) {
                case 3: // BASE64
                    $data = base64_decode($data);
                    break;
                case 4: // QUOTED-PRINTABLE
                    $data = quoted_printable_decode($data);
                    break;
            }

            $filepath = $this->attachmentPath.'/'.$filename;
            file_put_contents($filepath, $data);

            return [
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => strlen($data),
                'type' => $this->getAttachmentMimeType($part),
            ];

        } catch (\Exception $e) {
            \Log::error('Attachment save error: '.$e->getMessage());

            return null;
        }
    }

    private function getAttachmentMimeType($part)
    {
        $mimeType = 'application/octet-stream'; // default

        if (isset($part->subtype)) {
            $mainType = $this->getMainType($part->type);
            if ($mainType) {
                $mimeType = strtolower($mainType.'/'.$part->subtype);
            }
        }

        return $mimeType;
    }

    private function getMainType($type)
    {
        $types = [
            0 => 'text',
            1 => 'multipart',
            2 => 'message',
            3 => 'application',
            4 => 'audio',
            5 => 'image',
            6 => 'video',
        ];

        return $types[$type] ?? null;
    }

    private function getFileExtension($part)
    {
        $mimeType = $this->getAttachmentMimeType($part);
        $extensions = [
            'text/plain' => '.txt',
            'text/html' => '.html',
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'application/pdf' => '.pdf',
            'application/msword' => '.doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx',
        ];

        return $extensions[$mimeType] ?? '';
    }

    private function getEmailBody($msgNumber, $partNumber, $part)
    {
        $data = imap_fetchbody($this->connection, $msgNumber, $partNumber);

        switch ($part->encoding) {
            case 3: // BASE64
                $data = base64_decode($data);
                break;
            case 4: // QUOTED-PRINTABLE
                $data = quoted_printable_decode($data);
                break;
        }

        // Convert charset if needed
        if (isset($part->parameters)) {
            foreach ($part->parameters as $param) {
                if (strtolower($param->attribute) === 'charset') {
                    $charset = $param->value;
                    if (strtoupper($charset) !== 'UTF-8') {
                        $data = iconv($charset, 'UTF-8//IGNORE', $data);
                    }
                    break;
                }
            }
        }

        return $data;
    }

    private function decodeSubject($subject)
    {
        $elements = imap_mime_header_decode($subject);
        $decodedSubject = '';

        foreach ($elements as $element) {
            $decodedSubject .= $element->text;
        }

        return $decodedSubject;
    }

    public function disconnect()
    {
        if ($this->connection) {
            imap_close($this->connection);
        }
    }
}
