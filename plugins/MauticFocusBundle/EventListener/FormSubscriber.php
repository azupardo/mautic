<?php

namespace MauticPlugin\MauticFocusBundle\EventListener;

use Mautic\FormBundle\Event as Events;
use Mautic\FormBundle\FormEvents;
use MauticPlugin\MauticFocusBundle\Model\FocusModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FormSubscriber implements EventSubscriberInterface
{
<<<<<<< HEAD
    public function __construct(private FocusModel $model)
=======
    private \MauticPlugin\MauticFocusBundle\Model\FocusModel $model;

    public function __construct(FocusModel $model)
>>>>>>> 11b4805f88 ([type-declarations] Re-run rector rules on plugins, Report, Sms, User, Lead, Dynamic, Config bundles)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::FORM_POST_SAVE   => ['onFormPostSave', 0],
            FormEvents::FORM_POST_DELETE => ['onFormDelete', 0],
        ];
    }

    /**
     * Add an entry to the audit log.
     */
    public function onFormPostSave(Events\FormEvent $event): void
    {
        $form = $event->getForm();

        if ($event->isNew()) {
            return;
        }

        $foci = $this->model->getRepository()->findByForm($form->getId());

        if (empty($foci)) {
            return;
        }

        // Rebuild each focus
        /** @var \MauticPlugin\MauticFocusBundle\Entity\Focus $focus */
        foreach ($foci as $focus) {
            $focus->setCache(
                $this->model->generateJavascript($focus)
            );
        }

        $this->model->saveEntities($foci);
    }

    /**
     * Add a delete entry to the audit log.
     */
    public function onFormDelete(Events\FormEvent $event): void
    {
        $form   = $event->getForm();
        $formId = $form->deletedId;
        $foci   = $this->model->getRepository()->findByForm($formId);

        if (empty($foci)) {
            return;
        }

        // Rebuild each focus
        /** @var \MauticPlugin\MauticFocusBundle\Entity\Focus $focus */
        foreach ($foci as $focus) {
            $focus->setForm(null);
            $focus->setCache(
                $this->model->generateJavascript($focus)
            );
        }

        $this->model->saveEntities($foci);
    }
}
