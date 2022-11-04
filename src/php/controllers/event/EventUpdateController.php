<?php
include __DIR__ . '/../BaseController.php';
include __DIR__ . '/../../event/EventService.php';

class EventUpdateController extends BaseController
{
    function execute()
    {
        $eventService = new EventService();

        $id = new InputField('Id');
        $title = new InputField('Title');
        $description = new InputField('Description');
        $startDate = new InputField('StartDate');
        $endDate = new InputField('EndDate');
        $categoryId = new InputField('CategoryId');
        $imageFile = new InputField('ImageFile');

        $eventService->editEvent($id->get(), $title->get(), $description->get(), $startDate->get(), $endDate->get(), $categoryId->get(), $imageFile->get());
    }
}

$controller = new EventUpdateController(requiresArguments: true, validateUserLoggedIn: true);
