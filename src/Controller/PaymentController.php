<?php

namespace App\Controller;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\PaymentManager;
use Doctrine\ORM\EntityManagerInterface;


class PaymentController extends AbstractController
{
    public PaymentManager $paymentManager;
    
    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * @Route("/payment", name="app_payment")
     * @return Response
     */
    public function index(): Response
    {
        $filePath = __DIR__ . '/../../exampleData/import/payments.csv';

        if (file_exists($filePath)) {
            $this->paymentManager->importCSV($filePath);
            return new Response('File found');
        } else {
            return new Response('File not found');
        }
    }

    private function api() {
        // Example API endpoint
        if (isset($_POST['payment'])) {
            $paymentData = json_decode($_POST['payment'], true);
            $paymentManager = new PaymentManager();
            if ($paymentManager->importPayment($paymentData)) {
                http_response_code(200); // All fine - 2XX
                echo json_encode(['status' => 'Success']);
            } else {
                http_response_code(400); // Bad Request - 400
                echo json_encode(['status' => 'Error']);
            }
        }
    }
}
