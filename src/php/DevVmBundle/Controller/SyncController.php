<?php
namespace Frontastic\Catwalk\DevVmBundle\Controller;

use Frontastic\Catwalk\DevVmBundle\Domain\Archive;
use Frontastic\Common\ReplicatorBundle\Domain\RequestVerifier;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SyncController
{
    /**
     * @var \Frontastic\Common\ReplicatorBundle\Domain\RequestVerifier
     */
    private $requestVerifier;

    /**
     * @var string
     */
    private $sharedSecret;

    /**
     * @var string
     */
    private $webAssetDir;

    public function __construct(RequestVerifier $requestVerifier, string $sharedSecret, string $webAssetDir)
    {
        $this->requestVerifier = $requestVerifier;
        $this->sharedSecret = $sharedSecret;
        $this->webAssetDir = $webAssetDir;
    }

    public function syncAction(Request $request): JsonResponse
    {
        $filesystem = new Filesystem();

        $timestamp = date('YmdHis');
        $basedir = dirname($this->webAssetDir);

        $backupDir = uniqid(sprintf('%s/%s-backup-', $basedir, $timestamp));
        $updateDir = uniqid(sprintf('%s/%s-update-', $basedir, $timestamp));

        try {
            $this->requestVerifier->ensure($request, $this->sharedSecret);

            $archive = Archive::createFromBinaryData($request->getContent());

            $archive->extract($updateDir);

            $filesystem->rename($this->webAssetDir, $backupDir);
            $filesystem->rename($updateDir, $this->webAssetDir);

            $response = ['status' => 'success'];
            if (1 == $request->query->get('backup')) {
                $backup = Archive::createFromDirectory($backupDir);
                $response['backup'] = $backup->dump();
            }

            $filesystem->remove($backupDir);

            return new JsonResponse($response);
        } catch (\DomainException $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        } catch (IOException $e) {
            if (false === $filesystem->exists($this->webAssetDir) && $filesystem->exists($backupDir)) {
                $filesystem->rename($backupDir, $this->webAssetDir);
            }
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
