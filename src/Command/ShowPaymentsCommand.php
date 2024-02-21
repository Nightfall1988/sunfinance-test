<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\PaymentRepository;

class ShowPaymentsCommand extends Command
{
    private $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        parent::__construct();

        $this->paymentRepository = $paymentRepository;
    }

    protected function configure()
    {
        $this
            ->setName('app:report')
            ->setDescription('Show payments from a specific date')
            ->addArgument('date', InputArgument::REQUIRED, 'Date in YYYY-MM-DD format');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = $input->getArgument('date');

        try {
            $dateTime = new \DateTime($date);
        } catch (\Exception $e) {
            $output->writeln('<error>Invalid date format. Use YYYY-MM-DD.</error>');
            return Command::FAILURE;
        }

        $payments = $this->paymentRepository->findBy(['payment_date' => $dateTime]);

        if (empty($payments)) {
            $output->writeln('<info>No payments found for the specified date.</info>');
        } else {
            foreach ($payments as $payment) {
                $output->writeln(sprintf(
                    'Payment ID: %d, Payment Reference: %s, Amount: %s',
                    $payment->getId(),
                    $payment->getPaymentReference(),
                    $payment->getAmount()
                ));
            }
        }

        return Command::SUCCESS;
    }
}
