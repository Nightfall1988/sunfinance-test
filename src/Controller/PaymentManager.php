<?php
namespace App\Controller;
use \PDO;
use App\Entity\Payment;
use App\Entity\PaymentOrder;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LoanRepository;
use App\Repository\PaymentRepository;

class PaymentManager
{
    private $db;

    private $connection;

    private array $referenceList;

    private array $invalidReason = [];

    private EntityManagerInterface $entityManager;

    private LoanRepository $loanRepository;
    
    private PaymentRepository $paymentRepository;

    public function __construct(EntityManagerInterface $entityManager, 
                                LoanRepository $loanRepository, 
                                PaymentRepository $paymentRepository)
    {
        $this->entityManager = $entityManager;
        $this->loanRepository = $loanRepository;
        $this->paymentRepository = $paymentRepository;

        try {
            $this->db = new PDO("mysql:host=localhost;dbname=nordic", 'root', '');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function importPayment($data)
    {
        // Validate payment data
        if (!$this->validatePayment($data)) {
            return false;
        } else {
            // Save payment to the database
            $payment = new Payment();
            $payment->payment_reference = $data['paymentReference'];
            $payment->amount = $data['amount'];
            $payment->payment_date = $data['date'];
            $payment->description = $data['description'];
            $payment->national_security_number = $data['nationalSecurityNumber'];

            $this->entityManager->persist($payment);
            $this->entityManager->flush();
           
        }

        // Process payment
        $this->processPayment($data);

        return true;
    }

    public function importCSV($filePath)
    {
        $csvData = array_map('str_getcsv', file($filePath));
        $headers = array_shift($csvData);

        $this->referenceList = [];
        foreach ($csvData as $row) {

            $data = array_combine($headers, $row);
            $this->referenceList[] = $data['paymentReference'];

            if (!$this->validatePayment($data)) {
                $this->log(implode(', ', $this->invalidReason) . ' ' . implode(', ', $data));
                $this->invalidReason = [];
            } else {
                $dateObj = \DateTime::createFromFormat('YmdHis', $data['paymentDate']);
                $payment = new Payment();
                $payment->setPaymentReference($data['paymentReference']);
                $payment->setPayerName($data['payerName']);
                $payment->setPayerSurname($data['payerSurname']);
                $payment->setAmount($data['amount']);
                $payment->setDate($dateObj);
                $payment->setDescription($data['description']);
                $payment->setNationalSecurityNumber($data['nationalSecurityNumber']);
    
                $this->entityManager->persist($payment);
                $this->entityManager->flush();

                $this->processPayment($data);

            }

        }
    }

    private function validatePayment($data)
    {
        // Validate if duplicate references
        $payment = $this->paymentRepository->findByPaymentReference($data['paymentReference']);
        if (sizeof($payment) > 0) {
            $this->invalidReason[] = 'Invalid: Duplicate references';
        }

        // Check if the required fields are present
        if (!isset($data['amount']) || !isset($data['paymentDate']) || !isset($data['description'])) {
            $this->invalidReason[] = 'Invalid: Required fields are missing';
        }

        // Validate date format
        $dateObj = \DateTime::createFromFormat('YmdHis', $data['paymentDate']);

        if ($dateObj == false) {
            $this->invalidReason[] = 'Invalid: Date format';
        } 

        // Validate amount (non-negative value)
        if (!is_numeric($data['amount']) || $data['amount'] < 0) {
            $this->invalidReason[] = 'Invalid: Negative amount';
        }

        // If all checks pass, consider the payment valid

        if (sizeof($this->invalidReason) == 0) {
            return true;
        } else {
            return false;
        }
    }

    
    private function findLoanByReference($reference) {
        return $this->loanRepository->findByReference($reference);
    }

    private function processPayment($paymentData)
    {
        // Check if the payment is valid (this check is optional and depends on your requirements)
        $loanReference = $paymentData['description'];
        $loan = $this->findLoanByReference($loanReference);

        if ($loan) {
            $loanAmountToPay = $loan->getAmountToPay();
            $this->makePaymentOnLoan($paymentData, $loan);
        } else {
            $this->log('No matching loan found for payment reference: ' . $paymentData['paymentReference']);
        }
    }

    private function markPaymentAsAssigned() {
        
    }

    private function markPaymentAsPartiallyAssigned($paymentAmount, $loan) {
        
    }

    private function createRefundPaymentOrder($refundAmount, $paymentData) {
        $dateObj = \DateTime::createFromFormat('YmdHis', date('YmdHis'));

        $paymentOrder = new PaymentOrder();
        $paymentOrder->setAmount($refundAmount);
        $paymentOrder->setDate($dateObj);
        $paymentOrder->setReference($paymentData['paymentReference']);
        $paymentOrder->setPayerSurname($paymentData['payerName']);
        $paymentOrder->setPayerName($paymentData['payerSurname']);
        $this->entityManager->persist($paymentOrder);
        $this->entityManager->flush();
    }

    private function makePaymentOnLoan($paymentData,$loan) {
        $payments = $this->paymentRepository->findPaymentByDescription($loan->getReference());
        $fullAmount = array_sum(array_column($payments, 'amount'));
        $amountLeftToPay = $loan->getAmountToPay() - $fullAmount;

        if ($amountLeftToPay == 0) {
            $loan->markAsPaid();
            $this->entityManager->persist($loan);
            $this->entityManager->flush();
            $this->markPaymentAsAssigned($paymentData['paymentReference']);
            $this->log('Loan fully paid. Payment assigned for reference: ' . $paymentData['paymentReference'] . " " . $paymentData['description']);
        } elseif ($amountLeftToPay < 0) {
                $loan->markAsPaid();
                $this->entityManager->persist($loan);
                $this->entityManager->flush();
                $this->createRefundPaymentOrder(abs($amountLeftToPay),$paymentData);
                $this->log('Loan overpaid: ' . $paymentData['paymentReference'] . " " . $paymentData['description']);

        } else {
            $loan->setAmountToPay($amountLeftToPay);
            $this->entityManager->persist($loan);
            $this->entityManager->flush();
            $this->markPaymentAsAssigned($paymentData['paymentReference']);
            $this->log('Payment made: ' . $paymentData['paymentReference']);
        }
        
        $loan->setAmountToPay($amountLeftToPay);
        $loan->markAsPaid();
        $this->entityManager->persist($loan);
        $this->entityManager->flush();
    }

    private function log($message)
    {
        $file = __DIR__ . "/../../exampleData/error_log.csv";
        $logData = [date('Y-m-d H:i:s'), $message];        
        file_put_contents($file, implode(',', $logData) . PHP_EOL, FILE_APPEND);
    }
}

?>