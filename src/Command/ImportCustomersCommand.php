<?php

// src/Command/ImportLoansCommand.php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Customer;

class ImportCustomersCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setName('app:import-customers')
            ->setDescription('Import customers from JSON data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonContent = file_get_contents(__DIR__ . "/../../exampleData/json/customers.json");
        $data = json_decode($jsonContent, true);

        foreach ($data as $customerData) {
            $customer = new Customer();

            $customer->setId($customerData['id']);
            $customer->setFirstname($customerData['firstname']);
            $customer->setLastname($customerData['lastname']);
            $customer->setSsn($customerData['ssn']);
            
            if (isset($customerData['phone'])) {
                $customer->setPhone($customerData['phone']);
            } 

            if (isset($customerData['email'])) {
                $customer->setEmail($customerData['email']);
            }

            try {
                // var_dump($customer);
                // die;
                $this->entityManager->persist($customer);
                $this->entityManager->flush();
            } catch (\Exception $e) {
                // Log or dump the exception for further investigation
                dump($e->getMessage());
            }

        }

        // Flush changes to the database
        $this->entityManager->flush();

        $output->writeln('Customers imported successfully.');

        return Command::SUCCESS;
    }
}
