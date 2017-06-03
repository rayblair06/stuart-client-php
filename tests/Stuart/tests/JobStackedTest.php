<?php

namespace Stuart\tests;

use Stuart\Converters\StackedJobToJson;
use Stuart\JobStacked;

class JobStackedTest extends \PHPUnit_Framework_TestCase
{

    private $pickup_address = '12 rue de rivoli Paris';
    private $pickup_comment = 'comment';
    private $pickup_contact_company = 'company';
    private $pickup_contact_first_name = 'firstname';
    private $pickup_contact_last_name = 'lastname';
    private $pickup_contact_phone = '837746';

    private function pickup_at()
    {
        $pickupAt = new \DateTime('now', new \DateTimeZone('Europe/London'));
        $pickupAt->add(new \DateInterval('PT1H'));
        return $pickupAt;
    }

    private $drop_off_address = '148 rue de Charenton 75012 Paris';
    private $drop_off_comment = 'comment';
    private $drop_off_contact_company = 'company';
    private $drop_off_contact_first_name = 'firstname';
    private $drop_off_contact_last_name = 'lastname';
    private $drop_off_contact_phone = '837746';
    private $drop_off_client_reference = 'reference';
    private $drop_off_package_description = 'decription';
    private $drop_off_package_type = 'small';

    private function dropoff_at()
    {
        $pickupAt = new \DateTime('now', new \DateTimeZone('Europe/London'));
        $pickupAt->add(new \DateInterval('PT2H'));
        return $pickupAt;
    }

    private function expected_json_body()
    {
        return json_encode(
            array(
                'job' => array(
                    'pickup_at' => $this->pickup_at()->format(\DateTime::ATOM),
                    'dropoff_at' => $this->dropoff_at()->format(\DateTime::ATOM),
                    'pickups' => array(
                        array(
                            'address' => $this->pickup_address,
                            'comment' => $this->pickup_comment,
                            'contact' => array(
                                'firstname' => $this->pickup_contact_first_name,
                                'lastname' => $this->pickup_contact_last_name,
                                'phone' => $this->pickup_contact_phone,
                                'company' => $this->pickup_contact_company
                            )
                        )
                    ),
                    'dropoffs' => array(
                        array(
                            'address' => $this->drop_off_address,
                            'comment' => $this->drop_off_comment,
                            'contact' => array(
                                'firstname' => $this->drop_off_contact_first_name,
                                'lastname' => $this->drop_off_contact_last_name,
                                'phone' => $this->drop_off_contact_phone,
                                'company' => $this->drop_off_contact_company
                            ),
                            'package_type' => $this->drop_off_package_type,
                            'package_description' => $this->drop_off_package_description,
                            'client_reference' => $this->drop_off_client_reference
                        )
                    )
                )
            )
        );
    }

    public function test_it_produces_expected_json()
    {
        // given
        $job = new JobStacked();

        $job->addPickup($this->pickup_address)
            ->setPickupAt($this->pickup_at())
            ->setComment($this->pickup_comment)
            ->setContactCompany($this->pickup_contact_company)
            ->setContactFirstName($this->pickup_contact_first_name)
            ->setContactLastName($this->pickup_contact_last_name)
            ->setContactPhone($this->pickup_contact_phone);

        $job->addDropOff($this->drop_off_address)
            ->setDropOffAt($this->dropoff_at())
            ->setComment($this->drop_off_comment)
            ->setContactCompany($this->drop_off_contact_company)
            ->setContactFirstName($this->drop_off_contact_first_name)
            ->setContactLastName($this->drop_off_contact_last_name)
            ->setContactPhone($this->drop_off_contact_phone)
            ->setClientReference($this->drop_off_client_reference)
            ->setPackageDescription($this->drop_off_package_description)
            ->setPackageType($this->drop_off_package_type);

        // when
        self::assertEquals($this->expected_json_body(), StackedJobToJson::convert($job));
    }
}
