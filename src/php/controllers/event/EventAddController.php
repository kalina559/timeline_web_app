<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../event/EventService.php';

class EventAddController extends BaseController
{
    function execute()
    {
        $name = new InputField('Name');
        $title = new InputField('Title');
        $description = new InputField('Description');
        $startDate = new InputField('StartDate');
        $endDate = new InputField('EndDate');
        $categoryId = new InputField('CategoryId');
        $imageFile = new InputField('ImageFile');

        $eventService = new EventService();
        
        $eventService->addEvent($name->get(), $title->get(), $description->get(), $startDate->get(), $endDate->get(), $categoryId->get(), $imageFile->get());
    }
}

$controller = new EventAddController(requiresArguments: true, validateUserLoggedIn: true);
