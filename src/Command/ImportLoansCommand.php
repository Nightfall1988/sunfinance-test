<?php

// src/Command/ImportLoansCommand.php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Loan;

class ImportLoansCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setName('app:import-loans')
            ->setDescription('Import loans from JSON data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonContent = file_get_contents(__DIR__ . "/../../exampleData/json/loans.json");
        $data = json_decode($jsonContent, true);

        foreach ($data as $loanData) {
            $loan = new Loan();
            $loan->setId($loanData['id']);
            $loan->setCustomerId($loanData['customerId']);
            $loan->setReference($loanData['reference']);
            $loan->setStatus($loanData['state']);
            $loan->setAmountIssued($loanData['amount_issued']);
            $loan->setAmountToPay($loanData['amount_to_pay']);
            try {
                $this->entityManager->persist($loan);
                $this->entityManager->flush();
            } catch (\Exception $e) {
                // Log or dump the exception for further investigation
                dump($e->getMessage());
            }

            $this->entityManager->persist($loan);
        }

        // Flush changes to the database
        $this->entityManager->flush();

        $output->writeln('Loans imported successfully.');

        return Command::SUCCESS;
    }
}
