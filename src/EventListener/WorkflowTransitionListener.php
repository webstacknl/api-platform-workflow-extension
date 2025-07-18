<?php

declare(strict_types=1);

namespace Webstack\ApiPlatformWorkflowBundle\EventListener;

use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Workflow\Registry;

final class WorkflowTransitionListener
{
    public function __construct(
        private readonly Registry $workflows,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->isMethod(Request::METHOD_PATCH)
            || !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !isset($attributes['item_operation_name'])
            || 'state_apply' !== $attributes['item_operation_name']
        ) {
            return;
        }

        /** @var object $requestContent */
        $requestContent = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);

        if (!isset($requestContent->transition) || !($transition = $requestContent->transition)) {
            throw new BadRequestHttpException('Transition is required.');
        }

        $class = $request->attributes->get('data');

        if ($request->attributes->has('previous_data')) {
            $class = $request->attributes->get('previous_data');

            // @phpstan-ignore-next-line
            $class = $this->entityManager->find(get_class($class), $class->getId());
        }

        $workflowName = null;

        if (!empty($requestContent->workflowName)) {
            $workflowName = $requestContent->workflowName;
        }

        /** @var object $class */
        $workflow = $this->workflows->get($class, $workflowName);
        $workflow->apply($class, $transition);

        $this->entityManager->flush();
    }
}
