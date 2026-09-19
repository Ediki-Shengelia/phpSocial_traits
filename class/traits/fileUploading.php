<?php

trait FileUploading
{
    public string $tmp_name = '';
    public string $image = '';
    public string $type = '';
    public int $size = 0;
    public array $errors = [];
    public string $directory;
    public string $placeholder = "https://cdn-icons-png.flaticon.com/512/428/428573.png";

    // Allowed extensions and MIME types
    protected array $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    protected array $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    public array $upload_errors_array = [
        UPLOAD_ERR_OK         => 'File uploaded successfully.',
        UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form.',
        UPLOAD_ERR_PARTIAL   => 'The file was only partially uploaded.',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write the file to disk.',
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
    ];

    public function ensure_directory_exists(string $path): bool
    {
        if (!is_dir($path)) {
            return mkdir($path, 0755, true);
        }
        return true;
    }

    public function image_or_placeholder(): string
    {
        if (empty($this->image)) {
            return $this->placeholder;
        }

        return rtrim($this->directory, DS) . DS . $this->image;
    }

    public function set_file(array $file): bool
    {
        if (empty($file) || !is_array($file)) {
            $this->errors[] = "File is not available.";
            return false;
        }

        if (isset($file['error']) && $file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->upload_errors_array[$file['error']] ?? 'Unknown upload error.';
            return false;
        }

        // Validate Extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowed_extensions, true)) {
            $this->errors[] = "Invalid file extension. Allowed: " . implode(', ', $this->allowed_extensions);
            return false;
        }

        // Validate MIME Type (prevents MIME spoofing)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime_type, $this->allowed_mime_types, true)) {
            $this->errors[] = "Invalid file type.";
            return false;
        }

        $this->tmp_name = $file['tmp_name'];
        // Unique filename to prevent overwrite bugs and security issues from unsafe characters
        $this->image = bin2hex(random_bytes(16)) . '.' . $extension;
        $this->type = $mime_type;
        $this->size = (int)$file['size'];

        return true;
    }

    public function save_with_photo(): bool
    {
        if (!empty($this->errors)) {
            return false;
        }

        if (empty($this->tmp_name) || empty($this->image)) {
            $this->errors[] = "The file is not available.";
            return false;
        }

        $target_dir = SITE_ROOT . DS . $this->directory;
        $this->ensure_directory_exists($target_dir);

        $target_path = $target_dir . DS . $this->image;

        if (file_exists($target_path)) {
            $this->errors[] = "The file {$this->image} already exists.";
            return false;
        }

        if (move_uploaded_file($this->tmp_name, $target_path)) {
            return true;
        }

        $this->errors[] = "Failed to move uploaded file.";
        return false;
    }

    public function delete_with_photo(): bool
    {
        $target_path = SITE_ROOT . DS . $this->directory . DS . $this->image;

        if (method_exists($this, 'delete') && !$this->delete()) {
            return false;
        }

        if (file_exists($target_path) && is_file($target_path)) {
            return unlink($target_path);
        }

        return true;
    }
}
