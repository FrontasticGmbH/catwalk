<?php
namespace Frontastic\Catwalk\DevVmBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class NgrokController
{
    public function tunnelAction(Context $context): array
    {
        try {
            $tunnels = json_decode(file_get_contents('http://localhost:4040/api/tunnels'));
            if (!$tunnels) {
                return [];
            }

            foreach ($tunnels->tunnels as $tunnel) {
                if ($tunnel->name === $context->project->projectId) {
                    return [$tunnel->public_url];
                }
            }
        } catch (\Exception $e) {
            // Just ignore failures
        }

        return [];
    }
}
