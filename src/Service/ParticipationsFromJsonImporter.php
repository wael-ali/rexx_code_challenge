<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Participation;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ImportedParticipationFactory;
use Symfony\Component\Serializer\SerializerInterface;


class ParticipationsFromJsonImporter
{
    private $em;
    private $serializer;
    private $imports_directory;
    private $imports_file_name;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, string $imports_directory, string $imports_file_name) {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->imports_directory = $imports_directory;
        $this->imports_file_name = $imports_file_name;
    }

    public function import()
    {
        $fileContent = file_get_contents($this->imports_directory.'/'.$this->imports_file_name.'.json');
        $this->importFromJsonString($fileContent);
    }

    public function importFromJsonString(string $jsonData = null)
    {
        if (is_null($jsonData) || empty($jsonData)) {
            throw new \Exception("Not valid Json File", 1);
        }
        $deserializedData = [];
        try {
            $deserializedData = $this->deserializeJson($jsonData);
        } catch (\Throwable $th) {
            throw new \Exception("Error Importing Data From Json File", 1);
        }
        if (count($deserializedData) == 0) {
            throw new Exception("Not valid Content Json File", 1);
        }

        foreach($deserializedData as $deserilazedParticipationInfo) {
            if(!$deserilazedParticipationInfo->isValidParticipation()) continue;
            // check if event is already in db
            $event = ($this->em->getRepository(Event::class))->findOneBy(['id' => $deserilazedParticipationInfo->getEventId()]);
            if (is_null($event)) {
                // create the event
                $event = new Event();
                $event->setName($deserilazedParticipationInfo->getEventName());
                $event->setDate(new \DateTime($deserilazedParticipationInfo->getEventDate()));
                $this->em->persist($event);
                $this->em->flush();
                
            }
            // check if user is already in db
            $user = ($this->em->getRepository(User::class))->findOneBy(['email' => $deserilazedParticipationInfo->getEmployeeMail()]);
            if (is_null($user)) {
                // create the user
                $user = new User();
                $user->setName($deserilazedParticipationInfo->getEmployeeName());
                $user->setEmail($deserilazedParticipationInfo->getEmployeeMail());
                $this->em->persist($user);
                $this->em->flush();
            }
            // check if there is a participation already created
            $participation = ($this->em->getRepository(Participation::class))->findOneBy(['id' => $deserilazedParticipationInfo->getParticipationId()]);
            if (is_null($participation)) {
                // create the participation
                $participation = new Participation();
                $participation->setFee($deserilazedParticipationInfo->getParticipationFee());
                $participation->setAttendant($user);
                $participation->setEvent($event);
                $this->em->persist($participation);
                $this->em->flush();
            }
        }
    }

    private function deserializeJson(string $eventsJson = null): array
    {
        return $this->serializer->deserialize($eventsJson, ImportedParticipationFactory::class.'[]', 'json'); 
    }
    
}
