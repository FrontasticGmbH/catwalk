<?php
namespace Frontastic\Catwalk\DevVmBundle\Domain;

class Archive
{
    /**
     * @var \ZipArchive
     */
    private $zip;

    /**
     * @var boolean
     */
    private $cleanupOnExit = false;

    /**
     * @var string
     */
    private $filename;

    public function __destruct()
    {
        if ($this->cleanupOnExit && file_exists($this->filename)) {
            unlink($this->filename);
        }
    }

    public function dump(): string
    {
        if (false === file_exists($this->filename)) {
            throw new \RuntimeException('No archive data exists.');
        }
        return base64_encode(file_get_contents($this->filename));
    }

    public function extract(string $directory): void
    {
        if (null === $this->zip) {
            throw new \RuntimeException('No archive data exists.');
        }
        if (false === file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        if (false === $this->zip->extractTo($directory)) {
            throw new \RuntimeException('Cannot extract archive.');
        }
    }

    protected function initFromDirectory(string $directory, ?string $filename = null): void
    {
        if (null === $filename) {
            $filename = sprintf('%s/%s.zip', sys_get_temp_dir(), uniqid(date('YmdHis_')));
            $this->cleanupOnExit = true;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        $this->zip = new \ZipArchive();
        $this->zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }

            $absolutePath = $file->getPathname();
            $relativePath = substr($file->getPathname(), strlen($directory) + 1);

            $this->zip->addFile($absolutePath, $relativePath);
        }
        $this->zip->close();

        $this->filename = $filename;
    }

    protected function initFromBinaryData(string $blob, ?string $filename = null): void
    {
        if (null === $filename) {
            $filename = sprintf('%s/%s.zip', sys_get_temp_dir(), date('YmdHis'));
            $this->cleanupOnExit = true;
        }

        file_put_contents($filename, base64_decode($blob));

        $this->zip = new \ZipArchive();
        $this->zip->open($filename);

        $this->filename = $filename;
    }

    public static function createFromDirectory(string $directory, ?string $filename = null): Archive
    {
        $archive = new Archive();
        $archive->initFromDirectory($directory, $filename);

        return $archive;
    }

    public static function createFromBinaryData(string $blob, ?string $filename = null): Archive
    {
        $archive = new Archive();
        $archive->initFromBinaryData($blob, $filename);

        return $archive;
    }

    public static function createFromExistingArchive(string $filename): Archive
    {
        return self::createFromBinaryData(base64_encode(file_get_contents($filename)), $filename);
    }
}
